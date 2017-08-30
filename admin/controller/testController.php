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
        $m= $cmsPostModel->for_table('cms_post_tag', 'pro');
        $result = $m->table_alias('pt')
            ->select_expr('a.*,GROUP_CONCAT(t.tag_name) AS tags')
            ->left_join('cms_tag', ['pt.tag_id', '=', 't.tag_id'], 't')
            ->left_join('cms_post', ['pt.post_id', '=', 'a.id'], 'a')
            ->group_by_expr('pt.post_id')
            ->find_array();

        foreach ($result as $value) {
            $post_data = [
                'category_id' => 1,
                'model_id' => 1,
                'title' => $value['title'],
                'title_alias' => $value['title_alias'],
                'keywords' => $value['keyword'],
                'description' => $value['description'],
                'post_id' => getItemId(),
                'content' => $value['content'],
                'author' => $value['editor'],
                'click' => $value['click'],
                'main_image' => $value['image_name'],
                'post_tag'=>$value['tags']
            ];
            if ($cmsPostModel->orm()->where('title', $result['title'])->find_one()) {
                continue;
            }
             $cmsPostModel->addRecord($post_data);
        }


    }
}