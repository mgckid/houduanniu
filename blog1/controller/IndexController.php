<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/4/22
 * Time: 11:45
 */

namespace app\controller;


use app\model\CmsCategoryModel;
use app\model\CmsPostModel;
use app\model\CmsTagModel;
use houduanniu\base\Page;
use app\model\AdvertisementListModel;

class IndexController extends BaseController
{
    /**
     * 博客首页
     */
    public function index()
    {
        $p = isset($_GET['p']) && !empty($_GET['p']) ? intval($_GET['p']) : 1;
        $pageSize = 10;
        $reg = [];
        #获取广告
        {
            $adList = $this->getSiteAllAdvertisement();
            $reg['adList'] = $adList;
        }
        #获取最近更新
        {
            $postModel = new CmsPostModel();
            $tagModel = new CmsTagModel();
            $count = $postModel->getArticleList([], '', '', true);
            $page = new Page($count, $p, $pageSize);
            $field = 'a.id,a.title,a.title_alias,a.description,public_time,image_name,editor,click,c.name as category,c.alias as category_alias';
            $result = $postModel->getArticleList([], $page->getOffset(), $page->getPageSize(), false, $field);
            foreach ($result as $key => $value) {
                $tags = $tagModel->getTagByPostId($value['id']);
                $value['tags'] = $tags ? array_column($tags, 'tag_name') : [];
                $value['image_url'] = $value['image_name'] ? getImage($value['image_name'], $this->imageSize[1]) : '';
                $result[$key] = $value;
            }
            $reg['latestList'] = $result;
            $reg['pages'] = $page->getPageStruct();
        }
        #seo信息
        {
            $seoInfo['title'] = $this->siteInfo['site_short_name'] . '首页';
        }
        $this->display('Index/index', $reg, $seoInfo);
    }

    /**
     * 博客详情
     */
    public function detail()
    {
        $titleAlias = isset($_GET['id']) && !empty($_GET['id']) ? htmlspecialchars($_GET['id']) : 0;
        $postId = '';
        $reg = [];
        #获取文章详情
        {
            $postModel = new CmsPostModel();
            $tagModel = new CmsTagModel();
            $field = 'a.*,c.name as category,c.alias as category_alias';
            $condition = [
                'where' => ['a.title_alias', $titleAlias],
            ];
            $result = $postModel->getPostInfo($condition, $field);
            $postId = $result['id'];
            if (!$result) {
                $this->redirect('/');
            }
            #获取文章标签信息
            $tags = $tagModel->getTagByPostId($result['id']);
            $result['tags'] = $tags ? array_column($tags, 'tag_name') : [];
            $reg['info'] = $result;
        }
        #获取栏目信息
        {
            $cateModel = new CmsCategoryModel();
            $allCate = $cateModel->getColumnList([], 'name,id,pid');
            $result = $cateModel->getCateInfoById($result['column_id']);
            $layerInfo = treeStructForLevel($allCate, 'child', 0);
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
            $reg['cateInfo'] = $cateInfo;
        }
        $cateIdIn = array_merge([$result['id']], $cateModel::getChildsId($allCate, $result['id']));// array_merge($subId, [$cateId]);
        #推荐文章
        {
            $condition = [
                'where_in' => array('a.column_id', $cateIdIn),
                'where' => array('a.is_recommend', 10),
            ];
            $arcList = $postModel->getRecommendArticleList($condition, 5, 'a.id,a.title,a.title_alias,a.public_time');
            $reg['recommendList'] = $arcList;
        }
        #上一篇，下一篇文章
        {
            $reg['nextInfo'] = $postModel->getNext($postId, $result['id']);
            $reg['preInfo'] = $postModel->getPre($postId, $result['id']);
        }
        #相关文章
        {
            $field = 'p.id,p.title,p.title_alias,p.image_name';
            $reg['relatedPost'] = $postModel->getRelatedPost($postId, $field);
        }
        #seo信息
        {
            $seoInfo = [
                'title' => $reg['info']['title'] . '_' . $reg['cateInfo']['name'],
                'keyword' => $reg['info']['keyword'],
                'description' => $reg['info']['description']
            ];
        }
        $this->display('Index/detail', $reg, $seoInfo);
    }

    /**
     * 标签文章列表
     * @access public
     * @author furong
     * @return void
     * @since 2017年5月3日 10:54:04
     * @abstract
     */
    public function tag()
    {
        $tagName = isset($_GET['tag_name']) && !empty($_GET['tag_name']) ? htmlspecialchars($_GET['tag_name']) : '';
        $p = isset($_GET['p']) && !empty($_GET['p']) ? intval($_GET['p']) : 1;
        $pageSize = 10;
        $reg = [];
        #获取tag 文章列表
        {
            $tagModel = new CmsTagModel();
            $condition = [
                'where' => ['t.tag_name', $tagName],
            ];
            $count = $tagModel->getTagPostList($condition, '', '', true);
            $page = new   Page($count, $p, $pageSize);
            $field = 'p.id,p.title,p.title_alias,p.description,p.public_time,p.image_name,p.editor,p.click';
            $result = $tagModel->getTagPostList($condition, $page->getOffset(), $page->getPageSize(), false, $field);
            foreach ($result as $key => $value) {
                $value['image_url'] = $value['image_name'] ? getImage($value['image_name'], $this->imageSize[1]) : '';
                $result[$key] = $value;
            }
            $reg['postList'] = $result;
            $reg['pages'] = $page->getPageStruct();
            $reg['tagName'] = $tagName;
        }
        $seoInfo = [
            'title' => '标签:' . $tagName,
        ];
        $this->display('Index/tag', $reg, $seoInfo);
    }

    /**
     * 标签集合
     * @access public
     * @author furong
     * @return void
     * @since 2017年5月3日 11:20:32
     * @abstract
     */
    public function tagList()
    {
        #获取热门标签
        $tagModel = new CmsTagModel();
        $result = $tagModel->getHotTagList(999);
        $reg['tagList'] = $result;
        $seoInfo = [
            'title' => '标签云',
        ];
        $this->display('Index/tagList', $reg, $seoInfo);
    }


    /**
     * 栏目分类
     * @access public
     * @author furong
     * @return void
     * @since ${DATE}
     * @abstract
     */
    public function category()
    {
        $categoryName = isset($_GET['cate']) && !empty($_GET['cate']) ? htmlspecialchars($_GET['cate']) : '';
        #获取栏目信息
        {
            $cateModel = new CmsCategoryModel();
            $condition = [
                'where' => ['alias', $categoryName],
            ];
            $cateInfo = $cateModel->getCateInfo($condition);
            if (!$cateInfo) {
                die('栏目不存在');
            }
        }
        switch ($cateInfo['cate_type']) {
            #文章列表
            case 10:
                $this->cateList($cateInfo);
                break;
            #单页
            case 20:
                $this->catePage($cateInfo);
                break;
            default:
                die('栏目类型错误');
        }

    }

    /**
     * 栏目-文章列表
     * @access protected
     * @author furong
     * @param $cateInfo
     * @return void
     * @since 2017年4月25日 14:12:02
     * @abstract
     */
    protected function cateList($cateInfo)
    {
        $categoryName = $cateInfo['alias'];
        $p = isset($_GET['p']) && !empty($_GET['p']) ? intval($_GET['p']) : 1;
        $pageSize = 10;

        $reg = [];
        $reg['cateInfo'] = $cateInfo;
        #获取栏目列表
        {
            $postModel = new CmsPostModel();
            $tagModel = new CmsTagModel();
            $condition = [
                'where' => ['c.alias', $categoryName],
            ];
            $count = $postModel->getArticleList($condition, '', '', true);
            $page = new Page($count, $p, $pageSize);
            $field = 'a.id,a.title,a.title_alias,a.description,public_time,image_name,editor,click,c.name as category,c.alias as category_alias';
            $result = $postModel->getArticleList($condition, $page->getOffset(), $page->getPageSize(), false, $field);
            foreach ($result as $key => $value) {
                $tags = $tagModel->getTagByPostId($value['id']);
                $value['tags'] = $tags ? array_column($tags, 'tag_name') : [];
                $value['image_url'] = $value['image_name'] ? getImage($value['image_name'], $this->imageSize[1]) : '';
                $result[$key] = $value;
            }
            $reg['latestList'] = $result;
            $reg['pages'] = $page->getPageStruct();
        }
        #seo信息
        {
            $seoInfo = [
                'title' => $cateInfo['name'],
                'keyword' => $cateInfo['keyword'],
                'description' => $cateInfo['keyword']
            ];
        }
        $this->display('Index/cateList', $reg, $seoInfo);
    }

    /**
     * 栏目-单页
     * @access public
     * @author furong
     * @param $cateInfo
     * @return void
     * @since 2017年5月3日 10:54:42
     * @abstract
     */
    protected function catePage($cateInfo)
    {
        $reg = [];
        $reg['cateInfo'] = $cateInfo;
        #左侧菜单
        {
            $cateModel = new CmsCategoryModel();
            $condition = [
                'where' => ['cate_type', $cateModel::CATE_TYPE_PAGE],
            ];
            $cates = $cateModel->getColumnList($condition);
            $reg['cates'] = $cates;
        }
        #seo信息
        {
            $seoInfo = [
                'title' => $cateInfo['name'],
                'keyword' => $cateInfo['keyword'],
                'description' => $cateInfo['keyword']
            ];
        }
        $this->display('Index/catePage', $reg, $seoInfo);
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
            $value['image_url'] = getImage($value['ad_image']);
            $data[$value['position_key']][] = $value;
        }
        return $data;
    }

    /**
     * 统计阅读数
     */
    public function ajaxCountView()
    {
        if (!IS_POST) {
            die('非法访问');
        }
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if (!$id) {
            $this->ajaxFail('访问出错');
        }
        $articleModel = new CmsPostModel();
        $model = $articleModel->orm()->find_one($id);
        if (!$model) {
            $this->ajaxFail('文章不存在');
        }
        $info = $model->as_array();
        $click = ++$info['click'];
        $model->set('click', $click);
        if ($model->save()) {
            $this->ajaxSuccess('执行成功', array('click' => $click));
        } else {
            $this->ajaxFail('执行失败');
        }
    }


}