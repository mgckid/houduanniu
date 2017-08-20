<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 20:23
 */

namespace app\controller;


use app\model\CmsCategoryModel;
use app\model\CmsPostExtendAttributeModel;
use app\model\CmsPostModel;
use houduanniu\api\Controller;
use houduanniu\base\Page;

class ArticleController extends Controller
{
    /**
     * 获取文章列表
     * @access public
     * @author furong
     * @return void
     * @since
     * @abstract
     */
    public function articleList()
    {
        $cmsPostModel = new CmsPostModel();
        $rules = [
            'p' => 'required|integer',
            'page_size' => 'required|integer',
        ];
        $map = [
            'p' => '当前页数',
            'page_size' => '每页记录条数',
        ];
        $validate = $cmsPostModel->validate()->make($_REQUEST, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $p = $_REQUEST['p'];
        $page_size = $_REQUEST['page_size'];
        $count = $cmsPostModel->getPostList('', '', '', true);
        $page = new Page($count, $p, $page_size);
        $result = $cmsPostModel->getPostList('', $page->getOffset(), $page->getPageSize(), false);
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

    public function article()
    {
        $cmsPostModel = new CmsPostModel();
        $rules = [
            'field_name' => 'in:post_id,title_alias',
            'field_value' => 'required',
        ];
        $map = [
            'field_name' => '字段名',
            'field_value' => '字段值'
        ];
        $validate = $cmsPostModel->validate()->make($_REQUEST, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $field_name = $_REQUEST['field_name'];
        $field_value = $_REQUEST['field_value'];
        $post_id = '';
        if ($field_name == 'title_alias') {
            $cmsPostExtendAttributeModel = new CmsPostExtendAttributeModel();
            $orm = $cmsPostExtendAttributeModel->orm()->where(['field' => $field_name, 'value' => $field_value]);
            $result = $cmsPostExtendAttributeModel->getRecordInfo($orm);
            if (!$result) {
                $this->response(null, self::S400_BAD_REQUEST);
            }
            $post_id = $result['post_id'];
        } elseif ($field_name = 'post_id') {
            $post_id = $field_value;
        }
        $result = $cmsPostModel->getPostsInfo($post_id);
        $cmsCategoryModel = new CmsCategoryModel();
        $category_result = $cmsCategoryModel->getRecordInfoById($result['category_id']);
        $result['category_name']=$category_result['category_name'];
        if ($result) {
            $this->response($result, self::S200_OK, null, true);
        } else {
            $this->response(null, self::S404_NOT_FOUND);
        }
    }
}