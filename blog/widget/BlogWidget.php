<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/4/24
 * Time: 16:46
 */
namespace app\widget;

use app\Controller\BaseController;
use app\model\CmsPostModel;
use houduanniu\base\Application;
use houduanniu\web\View;
use app\model\CmsTagModel;


class BlogWidget extends BaseController
{
    /**
     * 热门文章
     * @access public
     * @author furong
     * @param $limit
     * @param $cateId
     * @return \houduanniu\base\type
     * @since 2017年4月24日 16:55:34
     * @abstract
     */
    public function hotPost($limit, $cateId = '')
    {
        $postModel = new CmsPostModel();
        $result = $postModel->getHotPost($limit, 'a.id,a.title,a.title_alias,a.public_time,a.image_name', $cateId);
        foreach ($result as $key => $value) {
            $value['image_url'] = $value['image_name'] ? getImage($value['image_name'], $this->imageSize[1]) : '';
            $result[$key] = $value;
        }
        $reg['hotPost'] = $result;
        return View::render('Common/hotPost', $reg);
    }

    /**
     * 获取热门标签
     * @access public
     * @author furong
     * @param $limit
     * @return \houduanniu\base\type
     * @since 2017年4月24日 17:22:24
     * @abstract
     */
    public function hotTag($limit)
    {
        #获取热门标签
        $tagModel = new CmsTagModel();
        $result = $tagModel->getHotTagList($limit);
        $reg['hotTag'] = $result;
        return View::render('Common/hotTag', $reg);
    }
}