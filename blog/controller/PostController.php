<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/20
 * Time: 10:15
 */

namespace app\controller;


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
            if($result['code']!=200){
                die('页面不存在');
            }
            $reg['info']=$result['data'];
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
}