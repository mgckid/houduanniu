<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 20:23
 */

namespace app\controller;


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
        $result = $cmsPostModel->getPostList('', $page->getOffset(), $page->getPageSize(), false, true);
        $return =[
            'count'=>$count,
            'list'=>$result,
        ];
        $this->response($return, self::S200_OK, null, true);
    }

    public function article()
    {
        $cmsPostModel = new CmsPostModel();
        $rules = [
            'post_id' => 'required|numeric',
        ];
        $map = [
            'post_id' => '文档id'
        ];
        $validate = $cmsPostModel->validate()->make($_REQUEST, $rules, [], $map);
        if (false == $validate->passes()) {
            $this->response(null, self::S400_BAD_REQUEST, $validate->messages()->first());
        }
        $post_id = $_REQUEST['post_id'];
        $result = $cmsPostModel->getPostsInfo($post_id);
        if ($result) {
            $this->response($result, self::S200_OK, null, false);
        } else {
            $this->response(null, self::S404_NOT_FOUND);
        }
    }
}