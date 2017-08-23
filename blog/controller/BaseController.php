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
use GuzzleHttp\Client;
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
        $result = $this->apiRequest('Site/siteConfig', [], 'Api');
        $siteInfo = [];
        foreach ($result['data'] as $value) {
            $siteInfo[$value['name']] = $value['value'];
        }
        return $siteInfo;
    }

    public function apiRequest($url, $data = [], $mode = 'Api', $method = 'get')
    {
        if ($mode == 'Api') {
            $host = C('API_URL');
        }
        if (empty($host)) {
            die('主机地址不存在');
        }
        if (!class_exists('\Requests')) {
            require_once(__VENDOR__ . '/Requests-master/library/Requests.php');
            \Requests::register_autoloader();
        }
        if ($method == 'get') {
            $response = \Requests::get($host . U($url, $data));
        }
        if (!$response->success) {
            die('接口请求错误');
        }
        $result = is_array(json_decode($response->body, true)) ? json_decode($response->body, true) : $response->body;
        if ($result['cached']) {
            Application::cache($this->getCacheName())->store($this->getCacheKey(), $return, 300);
        }
        return $result;
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
//        #获取头部导航
//        {
//            $cateModel = new CmsCategoryModel();
//            $condition = [
//                'where' => array('nav_display', $cateModel::NAV_DISPLAY),
//            ];
//            $filed = 'id,pid,name,alias,jump_url,cate_type';
//            $result = $cateModel->getColumnList($condition, $filed);
//            $navList = treeStructForLayer($result);
//            $reg['navList'] = $navList;
//        }
        #友情链接
        {

            $result =   $this->apiRequest('Site/flink', [], 'Api');
            $reg['flink'] = $result['data'];
        }
        View::addData($reg);
        View::setDirectory(__PROJECT__ . '/' . strtolower(Application::getModule()) . '/' . C('DIR_VIEW'));
        View::display($view, $data);
    }


} 