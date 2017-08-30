<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21
 * Time: 0:01
 */

namespace app\controller;


use app\model\CmsPostExtendAttributeModel;
use houduanniu\api\Controller;
use app\model\CmsPostModel;
use houduanniu\base\Page;

class PostController extends Controller
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
        $validate = $cmsPostModel->validate()->make($_REQUEST, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $page_size = intval($_REQUEST['page_size']);
        $result = $cmsPostModel->getPostList(null, 0, $page_size, false, 'a.click');
        foreach ($result as $key => $value) {
            $extend_data = $cmsPostModel->getPostExtendAttrbute($value['post_id']);
            $value = !empty($extend_data) ? array_merge($value, $extend_data) : $value;
            $result[$key] = $value;
        }
        $this->response($result, self::S200_OK, null, true);
    }

    public function tags()
    {
        $cmsPostModel = new CmsPostModel();
        $cmsPostExtendAttributeModel = new CmsPostExtendAttributeModel();
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
        $validate = $cmsPostModel->validate()->make($_REQUEST, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $p = $_REQUEST['p'];
        $page_size = $_REQUEST['page_size'];
        $tag_name = $_REQUEST['tag_name'];
        $orm = $cmsPostExtendAttributeModel->orm()->where('field', 'post_tag')->where_like('value', '%' . $tag_name . '%');
        $count = $cmsPostExtendAttributeModel->getRecordList($orm, '', '', true);
        $page = new Page($count, $p, $page_size);
        $result = $cmsPostExtendAttributeModel->getRecordList($orm, $page->getOffset(), $page->getPageSize(), false);
        $post_ids = array_column($result, 'post_id');

        $orm = $cmsPostModel->orm()->where_in('a.post_id', $post_ids);
        $result = $cmsPostModel->getPostList($orm, '', $page_size, false);
        foreach ($result as $key => $value) {
            $extend_data = $cmsPostModel->getPostExtendAttrbute($value['post_id']);
            $value = !empty($extend_data) ? array_merge($value, $extend_data) : $value;
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
        $validate = $cmsPostModel->validate()->make($_REQUEST, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $post_id = $_REQUEST['post_id'];
        $page_size = $_REQUEST['page_size'];
        $post_result = $cmsPostModel->getPostsInfo($post_id);
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
            $orm = $cmsPostModel->orm()->where('category_id', $post_result['category_id'])
                ->where_not_in('post_id', $post_ids);
            $result = $cmsPostModel->getRecordList($orm, 0, $page_size);
            if ($result) {
                $result = array_column($result, 'post_id');
                $post_ids = array_merge($post_ids, $result);
            }
        } else {
            $post_ids = array_slice($post_ids, 0, 6);
        }
        $result = [];
        foreach ($post_ids as $value) {
            $result[] = $cmsPostModel->getPostsInfo($value);
        }
        $this->response($result, self::S200_OK, '', true);
    }
}