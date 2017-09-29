<?php
/**
 * 框架启动文件
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/5/26
 * Time: 14:44
 */
#设置页面字符编码
header("content-type:text/html; charset=utf-8");

/*框架常量设置 开始*/
#框架运行开发模式
defined('ENVIRONMENT') || define('ENVIRONMENT', 'develop');
#是否ajax请求
define('IS_AJAX', isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest" ? true : FALSE);
#是否get请求
define('IS_GET', strtolower($_SERVER['REQUEST_METHOD']) == 'get' ? true : false);
#是否post请求
define('IS_POST', ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? true : FALSE);
#项目路径
defined('PROJECT_PATH') or define('PROJECT_PATH', dirname($_SERVER['DOCUMENT_ROOT']));
#框架组件路径
defined('FRAMEWORK_PATH') or define('FRAMEWORK_PATH', __DIR__);
#框架组件路径
defined('VENDOR_PATH') or define('VENDOR_PATH', dirname(FRAMEWORK_PATH));
#当前域名
defined('HTTP_HOST') or define('HTTP_HOST', $_SERVER['HTTP_HOST']);
#公共模块路径
defined('COMMON_PATH') or define('COMMON_PATH', PROJECT_PATH . '/common');
/*框架常量设置 结束*/

#载入函数库
require FRAMEWORK_PATH . '/function.php';

#错误报告级别(默认全部)
if (ENVIRONMENT == 'develop') {
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    ini_set('error_log', PROJECT_PATH . '/log/phperror.txt');
} elseif (ENVIRONMENT == 'product') {
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', false);
    ini_set('error_log', PROJECT_PATH . '/log/phperror.txt');
}

try {
    require VENDOR_PATH . '/Aura.Autoload-2.x/src/Loader.php';
    require VENDOR_PATH . '/Pimple-master/src/Pimple/Container.php';
    $container = new \Pimple\Container();

    #注册框架配置组件
    $container['config'] = function ($c) {
        $common_config = is_dir(COMMON_PATH . '/config') ? COMMON_PATH . '/config' : [];
        return new \houduanniu\base\Config($common_config);
    };
    #注册自动加载类
    $container['loader'] = function ($c) {
        return new \Aura\Autoload\Loader();
    };

    $loader = $container['loader'];
    $loader->register();
    $loader->setPrefixes(require(VENDOR_PATH . '/class_map.php'));

    /*时区设置*/
    $container['timezone_set']=$container['config']->get('timezone_set');

    /*错误处理设置*/
    $container['error_handler_set']=$container['config']->get('error_handler_set');

    #注册缓存组件
    $container['cache'] = $container['config']->get('cache');

    #注册模版引擎组件
    $container['templateEngine'] = $container['config']->get('templateEngine');

    #注册验证器组件
    $container['validation'] = $container['config']->get('validation');

    #注册http请求打包组件
    $container['request'] = $container['config']->get('request');

    #注册路由数据
    $container['request_data'] = $container['config']->get('request_data');

    $container['run_application'] = $container['config']->get('run_application');

    $container['error_handler_set'];
    $container['timezone_set'];
    $container['flow_set'];
} catch (\Exception $e) {
    errorPage($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
};


