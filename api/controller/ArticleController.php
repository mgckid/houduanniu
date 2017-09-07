<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 20:23
 */

namespace app\controller;


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
        if ($field_name == 'title_alias') {
            $post_result = $cmsPostModel->getRecordInfoByTitleAlias($field_value);
        } elseif ($field_name = 'post_id') {
            $post_result = $cmsPostModel->getRecordInfoByPostid($field_value);
        }
        $article = $cmsPostModel->getPostInfoById($post_result['id']);
        if (!$article) {
            $this->response(null, self::S404_NOT_FOUND);
        }else{
            $post_id = $article['post_id'];
            $pre_result = $cmsPostModel->getPre($post_id, $article['category_id']);
            $next_result = $cmsPostModel->getNext($post_id, $article['category_id']);
            $pre = [];
            if ($pre_result) {
                $pre = [
                    'title_alias' => $pre_result['title_alias'],
                    'title' => $pre_result['title'],
                    'id'=>$pre_result['id'],
                    'post_id'=>$pre_result['post_id'],
                    'main_image'=>$pre_result['main_image'],
                ];
            }
            $next = [];
            if ($next_result) {
                $next = [
                    'title_alias' => $next_result['title_alias'],
                    'title' => $next_result['title'],
                    'id'=>$next_result['id'],
                    'post_id'=>$next_result['post_id'],
                    'main_image'=>$next_result['main_image'],
                ];
            }
            $result['article'] = $article;
            $result['pre'] = $pre;
            $result['next'] = $next;
            $this->response($result, self::S200_OK, null, true);
        }
    }
}