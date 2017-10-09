<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21
 * Time: 0:01
 */

namespace app\controller;


use app\logic\BaseLogic;
use app\model\CmsPostExtendAttributeModel;
use app\model\CmsPostModel;
use houduanniu\base\Page;
use app\model\CmsCategoryModel;
use app\model\DictionaryModelModel;

class PostController extends BaseController
{
    public function tagList()
    {
        $cmsPostExtendAttributeModel = new CmsPostExtendAttributeModel();
        $orm = $cmsPostExtendAttributeModel->orm()->where('field', 'post_tag');
        $result = $cmsPostExtendAttributeModel->getAllRecord($orm, 'value');
        $tags = [];
        foreach ($result as $value) {
            $tags = array_merge($tags, explode(',', $value['value']));
        }
        $tags = array_values(array_unique($tags));
        $this->response($tags, self::S200_OK, null, true);
    }

    public function hotPost()
    {
        $cmsPostModel = new CmsPostModel();
        $rules = [
            'page_size' => 'required|integer',
        ];
        $map = [
            'page_size' => '每页记录条数',
        ];
        $request_param =$this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $page_size = $request_param['page_size'];
        $result = $cmsPostModel->getModelRecordList(1, '', 0, $page_size, false, 'click');
        $this->response($result, self::S200_OK, null, true);
    }

    public function tags()
    {
        $cmsPostModel = new CmsPostModel();
        $rules = [
            'p' => 'required|integer',
            'page_size' => 'required|integer',
            'tag_name' => 'required'
        ];
        $map = [
            'p' => '当前页数',
            'page_size' => '每页记录条数',
            'tag_name' => '标签名称'
        ];
        $request_param =$this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $p = $request_param['p'];
        $page_size = $request_param['page_size'];
        $tag_name = $request_param['tag_name'];
        $orm = $cmsPostModel->orm()->where_like('post_tag', '%' . $tag_name . '%');
        $count = $cmsPostModel->getModelRecordList(1, $orm, '', '', true);
        $page = new Page($count, $p, $page_size);
        $result = $cmsPostModel->getModelRecordList(1, $orm, $page->getOffset(), $page->getPageSize(), false);
        $cmsCategoryModel = new CmsCategoryModel();
        foreach ($result as $key => $value) {
            $category_result = $cmsCategoryModel->getRecordInfoById($value['category_id']);
            $value['category_name'] = $category_result['category_name'];
            $value['category_alias'] = $category_result['category_alias'];
            $result[$key] = $value;
        }
        $return = [
            'count' => $count,
            'list' => $result,
        ];
        $this->response($return, self::S200_OK, null, true);
    }

    public function relatedArticles()
    {
        $cmsPostModel = new CmsPostModel();
        $cmsPostExtendAttributeModel = new CmsPostExtendAttributeModel();
        $rules = [
            'post_id' => 'required|numeric',
            'page_size' => 'required|integer',
        ];
        $map = [
            'post_id' => '内容id',
            'page_size' => '列表记录数'
        ];
        $request_param =$this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $post_id = $request_param['post_id'];
        $page_size = $request_param['page_size'];
        $post_result = $cmsPostModel->getModelRecordInfoByPostId($post_id);
        if (!$post_result) {
            $this->response(null, self::S400_BAD_REQUEST, '文档不存在');
        }
        $tag = explode(',', $post_result['post_tag']);
        $post_ids = [];
        foreach ($tag as $value) {
            $orm = $cmsPostExtendAttributeModel->orm()->where(['field' => 'post_tag', 'value' => $value]);
            $result = $cmsPostExtendAttributeModel->getAllRecord($orm, 'post_id');
            if ($result) {
                $result = array_column($result, 'post_id');
                $post_ids = array_merge($post_ids, $result);
            }
        }
        if (count($post_ids) < $page_size) {
            $page_size = $page_size - count($post_ids);
            $orm = $cmsPostModel->orm()->where('category_id', $post_result['category_id']);
            if ($post_ids) {
                $orm = $orm->where_not_in('post_id', $post_ids);
            }
            $result = $cmsPostModel->getRecordList($orm, 0, $page_size);
            if ($result) {
                $result = array_column($result, 'post_id');
                $post_ids = array_merge($post_ids, $result);
            }
        } else {
            $post_ids = array_slice($post_ids, 0, 6);
        }
        $orm = $cmsPostModel->orm()->where_in('cms_post.post_id', $post_ids);
        $result = $cmsPostModel->getModelRecordList(1, $orm);
        $this->response($result, self::S200_OK, '', true);
    }

    public function siteNavigation()
    {
        $model = new CmsCategoryModel();
        $dictionaryModelModel = new DictionaryModelModel();
        $cateResult = $model->getAllRecord();
        $this->response($cateResult, self::S200_OK, null, true);
    }

    public function category()
    {
        $cmsCategoryModel = new CmsCategoryModel();
        $rules = [
            'category_alias' => 'required',
        ];
        $map = [
            'category_alias' => '栏目标识',
        ];
        $request_param =$this->getRequestParam();
        $validate = $cmsCategoryModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $category_alias = $request_param['category_alias'];
        #获取栏目信息
        {
            $logic = new BaseLogic();
            $orm = $cmsCategoryModel->orm()->where('category_alias', $category_alias);
            $category_result = $cmsCategoryModel->getRecordInfo($orm);
            $model_result = $logic->getModelInfo($category_result['model_id']);
            $category_result['dictionary_value'] = $model_result['dictionary_value'];
        }
        $return = [
            'category_info' => $category_result,
        ];
        $this->response($return, self::S200_OK, null, true);

    }

    public function latestPost()
    {
        $cmsPostModel = new CmsPostModel();
        $rules = [
            'dictionary_value' => 'required|not_in:page',
            'page_size' => 'required|integer',
            'p' => 'required|integer',
        ];
        $map = [
            'dictionary_value' => '文档模型',
            'p' => '当前页数',
            'page_size' => '每页记录条数',
        ];
        $request_param =$this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $dictionary_value = $request_param['dictionary_value'];
        $p = isset($request_param['p']) && !empty($request_param['p']) ? intval($request_param['p']) : 0;
        $page_size = isset($request_param['page_size']) && !empty($request_param['page_size']) ? intval($request_param['page_size']) : 0;

        $orm = $cmsPostModel->orm()->table_alias('p')->right_join('dictionary_model', ['p.model_id', '=', 'm.id'], 'm')->where(['m.dictionary_value' => $dictionary_value]);
        $field = 'p.*,m.dictionary_value';
        $count = $cmsPostModel->getModelRecordList(1, '', '', '', true);
        $page = new Page($count, $p, $page_size);
        $result = $cmsPostModel->getModelRecordList(1, '', $page->getOffset(), $page->getPageSize(), false, 'created', 'desc');
        $list = [];
        $cmsCategoryModel = new CmsCategoryModel();
        foreach ($result as $key => $value) {
            $category_result = $cmsCategoryModel->getRecordInfoById($value['category_id']);
            $value['category_name'] = $category_result['category_name'];
            $value['category_alias'] = $category_result['category_alias'];
            $result[$key] = $value;
        }
        $return = [
            'count' => $count,
            'list' => $result,
        ];
        $this->response($return, self::S200_OK, null, true);
    }

    public function categoryPostList()
    {
        $cmsCategoryModel = new CmsCategoryModel();
        $map = [
            'category_id' => '栏目id',
            'p' => '当前页数',
            'page_size' => '每页记录条数',
        ];
        $rules = [
            'category_id' => 'required|integer',
        ];
        $request_param =$this->getRequestParam();
        $validate = $cmsCategoryModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $category_id = isset($request_param['category_id']) && !empty($request_param['category_id']) ? intval($request_param['category_id']) : 0;
        $orm = $cmsCategoryModel->orm()->table_alias('c')->right_join('dictionary_model', ['c.model_id', '=', 'm.id'], 'm')->where(['c.id' => $category_id]);
        $field = 'c.*,m.dictionary_value';
        $category_info = $cmsCategoryModel->getRecordInfo($orm, $field);

        if($category_info['dictionary_value']=='page'){
            $cmsPostModel = new CmsPostModel();
            $orm = $cmsPostModel->orm()->where(['category_id' => $category_id]);
            $count = $cmsPostModel->getModelRecordList($category_info['model_id'], $orm, '', '', true);
            $result = $cmsPostModel->getModelRecordList($category_info['model_id'],$orm, 0, $count, false);
            $return = [
                'list' => $result,
            ];
        }elseif($category_info['dictionary_value']=='article'){
            $rules = [
                'p' => 'required|integer',
                'page_size' => 'required|integer',
            ];
            $validate = $cmsCategoryModel->validate()->make($request_param, $rules, [], $map);
            if (false == $validate->passes()) {
                $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
            }

            $p = isset($request_param['p']) && !empty($request_param['p']) ? intval($request_param['p']) : 0;
            $page_size = isset($request_param['page_size']) && !empty($request_param['page_size']) ? intval($request_param['page_size']) : 0;
            $cmsPostModel = new CmsPostModel();
            $orm = $cmsPostModel->orm()->where(['category_id' => $category_id]);
            $count = $cmsPostModel->getModelRecordList($category_info['model_id'], $orm, '', '', true);
            $page = new Page($count, $p, $page_size);
            $result = $cmsPostModel->getModelRecordList($category_info['model_id'], $orm, $page->getOffset(), $page->getPageSize(), false);
            $cmsCategoryModel = new CmsCategoryModel();
            foreach ($result as $key => $value) {
                $category_result = $cmsCategoryModel->getRecordInfoById($value['category_id']);
                $value['category_name'] = $category_result['category_name'];
                $value['category_alias'] = $category_result['category_alias'];
                $result[$key] = $value;
            }
            $return = [
                'count' => $count,
                'list' => $result,
            ];
        }
        $this->response($return, self::S200_OK, null, true);
    }

    public function search()
    {
        $cmsPostModel = new CmsPostModel();
        $map = [
            'keyword' => '关键字',
            'p' => '当前页数',
            'page_size' => '每页记录条数',
        ];
        $rules = [
            'keyword' => 'required',
            'p' => 'required|integer',
            'page_size' => 'required|integer',
        ];
        $request_param =$this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $keyword = $request_param['keyword'];
        $p = intval($request_param['p']);
        $page_size = intval($request_param['page_size']);
        $sql = "SELECT  post_id FROM cms_post_extend_attribute WHERE `value` LIKE '%{$keyword}%' UNION SELECT  post_id FROM cms_post_extend_text WHERE `value` LIKE '%{$keyword}%'";
        $result = $cmsPostModel->orm()->raw_query($sql)->find_array();
        if (!$result) {
            $this->response(null, self::S200_OK, '没有搜索到数据');
        }
        $post_ids = array_column($result, 'post_id');
        $orm = $cmsPostModel->orm()->where_in('cms_post.post_id', $post_ids);
        $count = $cmsPostModel->getModelRecordList(1, $orm, '', '', true);
        $page = new Page($count, $p, $page_size);
        $result = $result = $cmsPostModel->getModelRecordList(1, $orm, $page->getOffset(), $page->getPageSize(), false);
        $cmsCategoryModel = new CmsCategoryModel();
        foreach ($result as $key => $value) {
            $category_result = $cmsCategoryModel->getRecordInfoById($value['category_id']);
            $value['category_name'] = $category_result['category_name'];
            $value['category_alias'] = $category_result['category_alias'];
            $result[$key] = $value;
        }
        $return = [
            'count' => $count,
            'list' => $result,
        ];
        $this->response($return, self::S200_OK, null, true);
    }

    public function post()
    {
        $cmsPostModel = new CmsPostModel();
        $cmsCategoryModel = new CmsCategoryModel();
        $rules = [
            'post_id' => 'required|numeric'
        ];
        $map = [
            'post_id' => '文档id',
        ];
        $request_param =$this->getRequestParam();
        $validate = $cmsPostModel->validate()->make($request_param, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $post_id = $request_param['post_id'];
        $article = $cmsPostModel->getModelRecordInfoByPostId($post_id);
        $category_result = $cmsCategoryModel->getRecordInfoById($article['category_id']);
        $article['category_name'] = $category_result['category_name'];
        $article['category_alias'] = $category_result['category_alias'];
        if (!$article) {
            $this->response(null, self::S404_NOT_FOUND);
        } else {
            $id = $article['id'];
            $pre_result = $cmsPostModel->getPre($id, 'title_alias,id,post_id,title,main_image');
            $next_result = $cmsPostModel->getNext($id, 'title_alias,id,post_id,title,main_image');
            $result['article'] = $article;
            $result['pre'] = $pre_result;
            $result['next'] = $next_result;
            $this->response($result, self::S200_OK, null, true);
        }
    }

}