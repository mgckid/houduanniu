<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 11:36
 */

namespace app\controller;


use app\model\CmsPostModel;

class testController extends BaseController
{

    public function index()
    {
        $cmsPostModel = new CmsPostModel();
        $postModel = $cmsPostModel->for_table('cms_post')->use_id_column('id');
        $ids = $postModel->select_expr('id')->find_array();
        $ids = array_column($ids,'id');
        foreach($ids as $value){
            $result =  $cmsPostModel->for_table('cms_post')->use_id_column('id')->find_one($value);
            if(!$result){
                continue;
            }
            $result = $result->as_array();
//            var_dump($result,$ids);
            $post_data = [
                'category_id'=>1,
                'model_id'=>1,
                'title'=>$result['title'],
                'title_alias'=>$result['title_alias'],
                'keywords'=>$result['keyword'],
                'description'=>$result['description'],
                'post_id'=>getItemId(),
                'content'=>$result['content'],
                'author'=>$result['editor'],
                'click'=>$result['click'],
                'main_image'=>$result['image_name'],
            ];
            if($cmsPostModel->orm()->where('title',$result['title'])->find_one()){
                continue;
            }
           // $cmsPostModel->addRecord($post_data);
            sleep(5);
        }


    }
}