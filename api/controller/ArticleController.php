<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 20:23
 */

namespace app\controller;


use app\model\CmsPostModel;
use houduanniu\base\Controller;
use houduanniu\base\Page;

class ArticleController extends Controller
{
    public function articleList()
    {
        $cmsPostModel = new CmsPostModel();
        $rules = [
            'p' => 'required|integer',
            'page_size' => 'required|integer',
            'model_type' => 'required|in:article',
            'inclue_extend' => 'integer|in:0,1',
        ];
        $map = [
            'p' => '当前页数',
            'page_size' => '每页记录条数',
            'model_type' => '模型类型'
        ];
        $validate = $cmsPostModel->validate()->make($_REQUEST, $rules, [], $map);
        if (false == $validate->passes()) {
            die($validate->messages()->first());
        }
        $p = $_REQUEST['p'];
        $page_size = $_REQUEST['page_size'];
        $inclue_extend = isset($_REQUEST['inclue_extend']) ? intval($_REQUEST['inclue_extend']) : 0;
        $count = $cmsPostModel->getPostList('', '', '', true);
        $page = new Page($count, $p, $page_size);
        $result = $cmsPostModel->getPostList('', $page->getOffset(), $page->getPageSize(), false, $inclue_extend);
    }
}