<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21
 * Time: 0:01
 */

namespace app\controller;


use app\model\CmsPostExtendAttributeModel;
use houduanniu\api\Controller;

class PostController extends Controller
{
    public function tagList()
    {
        $cmsPostExtendAttributeModel = new CmsPostExtendAttributeModel();
        $orm = $cmsPostExtendAttributeModel->orm()->where('field', 'post_tag');
        $result = $cmsPostExtendAttributeModel->getAllRecord($orm, 'value');
        $tags=[];
        foreach($result as $value){
            $tags=array_merge($tags,explode(',',$value['value']));
        }
        $tags = array_values(array_unique($tags));
        $this->response($tags, self::S200_OK, null, true);
    }
}