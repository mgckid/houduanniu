<?php
namespace app\Controller;

use app\model\AdvertisementListModel;
use app\model\CmsPostModel;
use app\model\CmsCategoryModel;
use houduanniu\base\Page;


class IndexController extends BaseController
{
    public function index()
    {


        $cate = new CmsCategoryModel();
        #获取广告
        {
            $adList = $this->getSiteAllAdvertisement();
            $data['adList'] = $adList;
        }
        #核心业务
        {
            $condition = [
                'where' => array('pid', 45),
            ];
            $data['businessType'] = $cate->getColumnList($condition);
        }
        #获取新闻
        {
            $newsModel = new CmsPostModel();
            $filed = 'a.id,a.title,a.public_time,a.description,a.image_name';
            $condition=[
                'where'=>['is_image',10]
            ];
            $result = $newsModel->getArticleList($condition, 0, 6, false, $filed);
            $data['newsList'] = $result;
        }
        $this->display('Index/index', $data, array('title' => '首页'));
    }

    public function lists()
    {
        $id = isset($_GET['cate']) ? intval($_GET['cate']) : 0;
        $cateModel = new CmsCategoryModel();
        $result = $cateModel->getCateInfo($id);
        if (!$result) {
            $this->redirect('/');
        }
        #文章列表
        if ($result['cate_type'] == 10) {
            $this->listArticle($id);
        } elseif ($result['cate_type'] == 20) {#单页
            $this->listPage($id);
        }
    }

    /**
     * 文章详情
     *
     * @access public
     * @author furong
     * @return void
     * @since 2017年3月12日 14:47:05
     * @abstract
     */
    public function detail()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $newsModel = new CmsPostModel();
        $cateModel = new CmsCategoryModel();
        #获取文章详情
        {
            $result = $newsModel->getArticleInfo($id);
            if (!$result) {
                $this->redirect('/');
            }
            $data['info'] = $result;
        }
        #获取栏目信息
        {
            $allCate = $cateModel->getColumnList([], 'name,id,pid');
            $result = $cateModel->getCateInfo($result['column_id']);
            $layerInfo = $cateModel::unlimitedForLayer($allCate, 'child', 0);
            $cateInfo = [];
            foreach ($layerInfo as $value) {
                if ($value['id'] == $result['pid']) {
                    $cateInfo = $value;
                }
            }
            if (empty($cateInfo)) {
                $cateInfo = current($cateModel::getParents($allCate, $result['id']));
                $cateInfo['child'] = $cateModel::getChilds($allCate, $result['id']);
            }
            $data['cateInfo'] = $cateInfo;
            #seo信息
            $seoInfo = [
                'title' => $data['info']['title'] . '_' . $result['name'],
                'keyword' => $result['keyword'],
                'description' => $result['description']
            ];
        }
        $cateIdIn = array_merge([$result['id']], $cateModel::getChildsId($allCate, $result['id']));// array_merge($subId, [$cateId]);
        #推荐文章
        {
            $condition = [
                'where_in' => array('a.column_id', $cateIdIn),
                'where' => array('a.is_recommend', 10),
            ];
            $arcList = $newsModel->getRecommendArticleList($condition, 5, 'a.id,a.title,a.public_time');
            $data['recommendList'] = $arcList;
        }
        #上一篇，下一篇文章
        {
            $data['nextInfo'] = $newsModel->getNext($id, $result['id']);
            $data['preInfo'] = $newsModel->getPre($id, $result['id']);
        }
        $this->display('Index/detail', $data, $seoInfo);
    }

    /**
     * 获取网站全站广告
     * @access protected
     * @author furong
     * @return array
     * @since 2017年3月12日 11:33:38
     * @abstract
     */
    protected function getSiteAllAdvertisement()
    {
        $model = new AdvertisementListModel();
        $list = $model->getAdvertisementList([], 0, 999, false, 'al.*,ap.position_name,ap.position_key');
        $data = [];
        foreach ($list as $value) {
            $value['image_name'] = getImage($value['ad_image']);
            $data[$value['position_key']][] = $value;
        }
        return $data;
    }

    protected function listArticle($cateId)
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $pageSize = 15;
        $cateModel = new CmsCategoryModel();
        $articleModel = new CmsPostModel();
        #获取栏目信息
        {
            $allCate = $cateModel->getColumnList([], 'name,id,pid,keyword,description');
            $result = $cateModel->getCateInfo($cateId);
            $layerInfo = $cateModel::unlimitedForLayer($allCate, 'child', 0);
            $cateInfo = [];
            foreach ($layerInfo as $value) {
                if ($value['id'] == $result['pid']) {
                    $cateInfo = $value;
                }
            }
            if (empty($cateInfo)) {
                $cateInfo = current($cateModel::getParents($allCate, $result['id']));
                $cateInfo['child'] = $cateModel::getChilds($allCate, $result['id']);
            }
            $data['cateInfo'] = $cateInfo;
            #seo信息
            $seoInfo = [
                'title' => $result['name'],
                'keyword' => $result['keyword'],
                'description' => $result['description']
            ];
        }
        $cateIdIn = array_merge([$result['id']], $cateModel::getChildsId($allCate, $result['id']));// array_merge($subId, [$cateId]);
        #获取图文
        {
            $condition = [
                'where_in' => array('column_id', $cateIdIn),
                'where' => array('is_image', 10),
            ];
            $field = 'a.id,a.title,a.image_name';
            $result = $articleModel->getArticleList($condition, 0, 3, false, $field);
            $data['imageNews'] = $result;
        }
        #获取推荐文章
        {
            $condition = [
                'where_in' => array('column_id', $cateIdIn),
                'where' => array('is_recommend', 10),
            ];
            $field = 'a.id,a.title,a.description,a.public_time';
            $result = $articleModel->getArticleList($condition, 0, 3, false, $field);
            $data['recommendList'] = $result;
        }
        #文章列表
        {
            $condition = [
                'where_in' => array('column_id', $cateIdIn),
            ];
            $count = $articleModel->getArticleList($condition, '', '', true);
            $page = new Page($count, $p, $pageSize);
            $field = 'a.id,a.title,a.description,a.public_time,a.image_name';
            $result = $articleModel->getArticleList($condition, $page->getOffset(), $pageSize, false, $field);
            $data['articleList'] = $result;
            $data['pages'] = $page->getPageStruct(2);
        }
        $this->display('Index/articleList', $data, $seoInfo);
    }

    public function listPage($cateId)
    {
        $cateModel = new CmsCategoryModel();
        $pageInfo = $cateModel->getCateInfo($cateId);
        #获取栏目信息
        {
            $allCate = $cateModel->getColumnList([], 'name,id,pid,keyword,description');
            $result = $cateModel->getCateInfo($cateId);
            $layerInfo = $cateModel::unlimitedForLayer($allCate, 'child', 0);
            $cateInfo = [];
            foreach ($layerInfo as $value) {
                if ($value['id'] == $result['pid']) {
                    $cateInfo = $value;
                }
            }
            if (empty($cateInfo)) {
                $cateInfo = current($cateModel::getParents($allCate, $result['id']));
                $cateInfo['child'] = $cateModel::getChilds($allCate, $result['id']);
            }
            $data['pageInfo'] = $pageInfo;
            $data['cateInfo'] = $cateInfo;
            #seo信息
            $seoInfo = [
                'title' => $result['name'],
                'keyword' => $result['keyword'],
                'description' => $result['description']
            ];
        }
        #网站个性化 关于我们41，核心业务45
        {
            if (in_array($result['id'], array(41, 45))) {
                $condition = [
                    'where' => array('pid', 45),
                ];
                $data['businessType'] = $cateModel->getColumnList($condition);
            }
        }
        $this->display('Index/' . $pageInfo['list_template'], $data, $seoInfo);
    }


}

?>