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
#是否ajax请求
define('IS_AJAX', isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest" ? true : FALSE);
#是否get请求
define('IS_GET', strtolower($_SERVER['REQUEST_METHOD']) == 'get' ? true : false);
#是否post请求
define('IS_POST', ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? true : FALSE);
#框架运行开发模式
defined('__ENVIRONMENT__') || define('__ENVIRONMENT__', 'develop');
#项目路径
defined('__PROJECT__') or define('__PROJECT__', dirname(dirname($_SERVER['DOCUMENT_ROOT'])));
#框架组件路径
defined('__FRAMEWORK__') or define('__FRAMEWORK__', __DIR__);
#框架组件路径
defined('__VENDOR__') or define('__VENDOR__', dirname(__FRAMEWORK__));
#当前域名
defined('__HOST__') or define('__HOST__', $_SERVER['HTTP_HOST']);
/*框架常量设置 结束*/

#载入函数库
require __DIR__ . '/function.php';


#错误报告级别(默认全部)
if (__ENVIRONMENT__ == 'develop') {
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    ini_set('error_log', __PROJECT__ . '/log/phperror.txt');
} elseif (__ENVIRONMENT__ == 'product') {
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', false);
    ini_set('error_log', __PROJECT__ . '/log/phperror.txt');
}

#时区设置
date_default_timezone_set('PRC');
#注册类
require __FRAMEWORK__ . '/base/Register.php';
$register = new \houduanniu\base\Register();


#注册自动加载类
require __VENDOR__ . '/Aura.Autoload-2.x/src/Loader.php';
$register->set('autoloader', new \Aura\Autoload\Loader());
$register->get('autoloader')->register();
$register->get('autoloader')->setPrefixes(require(__VENDOR__ . '/classMap.php'));

try {
    \houduanniu\base\Application::run($register);
} catch (\Exception $e) {
    \houduanniu\web\View::getEngine()->setDirectory(__DIR__ . '/templates/');
    \houduanniu\web\View::getEngine()->setFileExtension('tpl');
    \houduanniu\web\View::getEngine()->addData([
        'e' => [
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ],
    ]);
    send_http_status($e->getCode());
    die (\houduanniu\web\View::getEngine()->render('think_exception'));
};
