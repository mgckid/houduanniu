<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/5/5
 * Time: 9:38
 */

namespace app\controller;


use app\model\CmsPostModel;
use app\model\PostModel;
use houduanniu\base\Application;
use houduanniu\base\Cache;
use houduanniu\base\Models;
use idiorm\orm\ORM;

class TestController
{
    public function index()
    {
        $this->sesion();
    }

    public function cache()
    {
//      $aaa=  'http://img.my.csdn.net/uploads/201107/14/0_1310654533q26t.png';
//      $re = get_headers($aaa,1);
//      var_dump($re);
        $cache = Application::cache();
//        $cache->setCachePath(__PROJECT__ . '/cache/');
        $cache->eraseExpired();
        $cache->store('data', '11111');
        $m = new CmsPostModel();
        if (!$news = $cache->retrieve('news')) {
            $news = $m->getArticleList([], 0, 100, false, 'a.id,a.title');
            $cache->store('news', $news, 5);
        }
        var_dump($news);
    }

    public function orm()
    {
//        Models::selectDb('default');
        $info = Models::factory('cms_post', Models::selectDb())->select_expr('id,title')->find_array();
//        $aa = Models::factory('cms_category')->find_array();
        print_g($info);
    }

    public function test()
    {
//        $aa = new CmsPostModel();
//        $aa->orm('cms_post')->select_expr('id')->find_many();
//        $info = ORM::for_table('cms_post', Models::selectDb('default'))->select_expr('id,title')->find_array();
//        print_g(ORM::get_connection_names());
        $m = new Models();
//        $m->get_db()->commit();
        $m->for_table('cms_post')->select_expr('id,title')->where_gt('id', 50)->count();
        $m->for_table('cms_post')->find_one(187)->as_array();
        $a = $m->for_table('article_type', 'cms')->find_array();
    }

    public function testorm()
    {
        $m = new CmsPostModel();
    }

    public function sesion(){

    }
}