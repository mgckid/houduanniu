<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/4/22
 * Time: 11:45
 */

namespace app\controller;


use app\model\CmsCategoryModel;
use houduanniu\base\Page;


class IndexController extends BaseController
{
    /**
     * 博客首页
     */
    public function index()
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $page_size = 10;
        #获取最近更新文章
        {
            $param = [
                'p' => $p,
                'page_size' => $page_size
            ];
            $result = $this->apiRequest('Article/articleList', $param, 'Api');

            if ($result['code'] == 200) {
                $count = $result['data']['count'];
                $list_data = $result['data']['list'];
                $page = new Page($count, $p, $page_size);
                $reg['pages'] = $page->getPageStruct();
                $reg['list_data'] = $list_data;
            }
        }
        #seo标题
        {
            $seoInfo = [
                'title' => $this->siteInfo['site_short_name'] . '首页',
                'keyword' => $this->siteInfo['site_keywords'],
                'description' => $this->siteInfo['site_description'],
            ];
        }
        $this->display('Index/index', $reg, $seoInfo);

    }

    public function category()
    {
        $cate = isset($_GET['cate']) ? trim($_GET['cate']) : '';
        $page_size = 10;
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        if (!$cate) {
            die('页面不存在');
        }
        #获取栏目数据
        {
            $param = [
                'category_alias' => $cate,
                'page_size' => $page_size,
                'p' => $p
            ];
            $result = $this->apiRequest('Post/category', $param, 'Api');
            if ($result['code'] == 200) {
                $count = $result['data']['count'];
                $list_data = $result['data']['list'];
                $category_info = $result['data']['category_info'];
                $page = new Page($count, $p, $page_size);
                $reg['pages'] = $page->getPageStruct();
                $reg['list_data'] = $list_data;
                $reg['category_info'] = $category_info;
            }
        }
        #seo标题
        {
            $seoInfo = [
                'title' => $category_info['category_name'],
                'keyword' => $category_info['keywords'],
                'description' => $category_info['description'],
            ];
        }
        $this->display('Index/category', $reg, $seoInfo);
    }


}