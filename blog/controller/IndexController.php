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


}