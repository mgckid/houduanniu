<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 20:23
 */

namespace app\controller;


use app\logic\BaseLogic;
use app\model\CmsCategoryModel;
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
        $count = $cmsPostModel->getRecordList('', '', '', true);
        $page = new Page($count, $p, $page_size);
        $result = $cmsPostModel->getRecordList('', $page->getOffset(), $page->getPageSize(), false);
        $list = [];
        $cmsCategoryModel = new CmsCategoryModel();
        foreach ($result as $key => $value) {
            $post = $cmsPostModel->getRecordInfoById($value['id']);
            $category_result = $cmsCategoryModel->getRecordInfoById($post['category_id']);
            $post['category_name'] = $category_result['category_name'];
            $list[] =$post;
        }
        $return = [
            'count' => $count,
            'list' => $list,
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
        if ($field_name == 'title_alias') {
            $article = $cmsPostModel->getRecordInfoByTitleAlias($field_value);
        } elseif ($field_name = 'post_id') {
            $article = $cmsPostModel->getRecordInfoByPostid($field_value);
        }
        if (!$field_value) {
            $this->response(null, self::S404_NOT_FOUND);
        } else {
            $post_id = $article['post_id'];
            $pre_result = $cmsPostModel->getPre($post_id, 'title_alias,id,post_id,title,main_image');
            $next_result = $cmsPostModel->getNext($post_id, 'title_alias,id,post_id,title,main_image');
            $result['article'] = $article;
            $result['pre'] = $pre_result;
            $result['next'] = $next_result;
            $this->response($result, self::S200_OK, null, true);
        }
    }
}