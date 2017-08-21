<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/20
 * Time: 10:15
 */

namespace app\controller;

use houduanniu\base\Page;
class PostController extends BaseController
{
    public function detail()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (empty($id)) {
            die('页面不存在');
        }
        #获取文章详情
        {
            $param = [
                'field_name' => 'title_alias',
                'field_value' => $id,
            ];
            $result = $this->apiRequest('Article/article', $param, 'Api');
            if ($result['code'] != 200) {
                die('页面不存在');
            }
            $result['data']['post_tag']=explode(',',$result['data']['post_tag']);
            $reg['info'] = $result['data'];
        }
        #seo标题
        {
            $seoInfo = [
                'title' => $reg['info']['title'],
                'keyword' => $reg['info']['keyword'],
                'description' => $reg['info']['description']
            ];

        }
        $this->display('Post/detail', $reg, $seoInfo);
    }

    public function tags(){
        $tag_name = isset($_GET)?trim($_GET['tag_name']):'';
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $page_size = 10;
        #获取最近更新文章
        {
            $param = [
                'p' => $p,
                'page_size' => $page_size,
                'tag_name'=>$tag_name
            ];
            $result = $this->apiRequest('Post/tags', $param, 'Api');

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