<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/19
 * Time: 23:53
 */

namespace app\controller;


use app\model\SiteConfigModel;
use app\model\SiteFlinkModel;

class SiteController extends BaseController
{
    public function siteConfig()
    {
        $siteConfigModel = new SiteConfigModel();
        $orm = $siteConfigModel->orm()->where('deleted', 0);
        $result = $siteConfigModel->getAllRecord($orm, 'name,value,description');
        $this->response($result, self::S200_OK, null, true);
    }

    public function flink()
    {
        $siteConfigModel = new SiteFlinkModel();
        $orm = $siteConfigModel->orm()->where('deleted', 0);
        $result = $siteConfigModel->getAllRecord($orm, 'fname,furl,fdesc');
        $this->response($result, self::S200_OK, null, true);
    }


}