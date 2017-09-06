<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/3/12
 * Time: 9:32
 */

namespace app\controller;


use app\model\SiteConfigModel;
use app\model\SiteSetModel;
use app\model\CmsCategoryModel;
use app\model\FlinkModel;
use houduanniu\web\Controller;
use houduanniu\web\View;
use houduanniu\base\Application;

class BaseController extends Controller
{
    #面包屑导航
    protected $crumbHtml;
    public $imageSize;

    public $siteInfo;

    function __construct()
    {
        parent::__construct();
        $this->imageSize = C('IMAGE_SIZE');
        $this->siteInfo = $this->getSiteInfo();
    }

    /**
     * 面包屑导航
     * @param $crumb
     */
    public function crumb($crumbs = array())
    {
        $crumbsHtml = '';
        if (!empty($crumbs)) {
            $crumbsHtml .= '<li><a href="' . U('/Index/index') . '">首页</a></li>';
            $n = 0;
            foreach ($crumbs as $key => $value) {
                $n++;
                $link_s = !empty($value) ? '<a href="' . $value . '">' : '';
                $link_e = !empty($value) ? '</a>' : '';
                if ($n == count($crumbs)) {
                    $crumbsHtml .= '<li class="active color4">' . $key . '</li>';
                } else {
                    $crumbsHtml .= '<li>' . $link_s . $key . $link_e . '</li>';
                }
            }
        }
        //赋值给公共变量
        $this->crumbHtml = $crumbsHtml;
    }

    /**
     * 获取站点信息
     */
    public function getSiteInfo()
    {
        $siteConfigModel = new SiteConfigModel();
        $result = $siteConfigModel->getConfigList([], 'name,value');
        $siteInfo = [];
        foreach ($result as $value) {
            $siteInfo[$value['name']] = $value['value'];
        }
        return $siteInfo;
    }

    /**
     * 输出模版方法
     * @param type $view
     * @param type $data
     */
    public function display($view, $data = array(), $seoInfo = array())
    {
        #站点信息
        {
            $siteInfo = $this->siteInfo;
            $siteInfo['title'] = !empty($seoInfo['title']) ? $seoInfo['title'] . '_' . $siteInfo['site_name'] : $siteInfo['site_name'];
            $siteInfo['keyword'] = !empty($seoInfo['keyword']) ? $seoInfo['keyword'] : $siteInfo['site_keywords'];
            $siteInfo['description'] = !empty($seoInfo['description']) ? $seoInfo['description'] : $siteInfo['site_description'];

            $reg['siteInfo'] = $siteInfo;
            $reg['crumbs'] = $this->crumbHtml;
        }
        #获取头部导航
        {
            $cateModel = new CmsCategoryModel();
            $condition = [
                'where' => array('nav_display', $cateModel::NAV_DISPLAY),
            ];
            $field = 'id,pid,name,alias,jump_url,cate_type';
            $result = $cateModel->getColumnList($condition, $field);
            $navList = treeStructForLayer($result);
            $reg['navList'] = $navList;
        }
        #友情链接
        {
            $flinkModel = new FlinkModel();
            $result = $flinkModel->getFlinkList(0, 10);
            $reg['flink'] = $result;
        }
        View::addData($reg);
        View::setDirectory(__PROJECT__ . '/' . strtolower(Application::getModule()) . '/' . C('DIR_VIEW'));
        View::display($view, $data);
    }


} 