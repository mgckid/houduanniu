<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/3/22
 * Time: 15:54
 */

namespace houduanniu\base;


class Config extends \Noodlehaus\Config
{
    protected function getDefaults()
    {
        return array(
            /*框架自定义配置 开始*/
            /*http请求组件依赖注入*/
            'request' => function ($c) {
                return new \houduanniu\base\Request($c['config']->all());
            },
            /*http请求路由数据*/
            'request_data' => function ($c) {
                return $c['request']->run();
            },
            /*模版引擎组件依赖注入*/
            'templateEngine' => function ($c) {
                return new \League\Plates\Engine();
            },
            /*验证器组件依赖注入*/
            'validation' => function ($c) {
                require VENDOR_PATH . '/overtrue/validation/src/helpers.php';
                $lang = require VENDOR_PATH . '/overtrue/zh-CN/validation.php';
                return new \Overtrue\Validation\Factory(new \Overtrue\Validation\Translator($lang));
            },
            /*缓存组件依赖注入*/
            'cache' => function ($c) {
                $cache_dir = PROJECT_PATH . '/cache/';
                if (!is_dir($cache_dir)) {
                    mkdir($cache_dir);
                }
                $cache = new Cache();
                return $cache->setCachePath($cache_dir);
            },
            /*hooks钩子组件注入*/
            'hooks' => function ($c) {
                $hooks = new  \houduanniu\base\Hooks();
                $hooks->add_action('After_Hooks_Setup',$hooks);
                return $hooks;
            },
            /*流程设置*/
            'flow_set'=>function($container){
                #当前模块名称常量
                defined('MODULE_NAME') or define('MODULE_NAME', $container['request_data']['module']);
                #当前控制器名称常量
                defined('CONTROLLER_NAME') or define('CONTROLLER_NAME', $container['request_data']['controller']);
                #当前方法名称常量
                defined('ACTION_NAME') or define('ACTION_NAME', $container['request_data']['action']);
                #当前模块路径
                defined('APP_PATH') or define('APP_PATH', PROJECT_PATH . '/' . strtolower(MODULE_NAME));

                $loader = $container['loader'];
                #添加应用类文件加载位置
                $appPath = array(
                    APP_PATH,
                    COMMON_PATH,
                );
                $loader->addPrefix('app', $appPath);
                #添加公共第三方扩展类夹在位置
                $common_vendor_class_map = COMMON_PATH . '/vendor/class_map.php';
                if (file_exists($common_vendor_class_map)) {
                    $class_map_result = require($common_vendor_class_map);
                    if (is_array($class_map_result) && !empty($class_map_result)) {
                        foreach ($class_map_result as $key => $value) {
                            $loader->addPrefix($key, $value);
                        }
                    }
                }
                #添加应用第三方扩展类夹在位置
                $app_vendor_class_map = APP_PATH . '/vendor/class_map.php';
                if (file_exists($app_vendor_class_map)) {
                    $class_map_result = require($app_vendor_class_map);
                    if (is_array($class_map_result) && !empty($class_map_result)) {
                        foreach ($class_map_result as $key => $value) {
                            $loader->addPrefix($key, $value);
                        }
                    }
                }
                #添加应用配置
                if (is_dir(APP_PATH . '/config')) {
                    unset($container['config']);
                    $container['config'] = function ($c) {
                        $config_path = [
                            COMMON_PATH . '/config',
                            APP_PATH . '/config',
                        ];
                        return new \houduanniu\base\Config($config_path);
                    };
                }
                #添加应用依赖注入
                $app_container = $container['config']->get('DEPENDENCY_INJECTION_MAP');
                if (!empty($app_container)) {
                    foreach ($app_container as $key => $value) {
                        $container[$key] = $value;
                    }
                }
                #加载应用依赖脚本
                $require_script = $container['config']->get('REQUIRE_SCRIPT_MAP');
                if (!empty($require_script)) {
                    foreach ($require_script as $value) {
                        require $value;
                    }
                }
                #运行程序
                $controller_name = 'app\\' . $container['config']->get('DIR_CONTROLLER') . '\\' . CONTROLLER_NAME . $container['config']->get('EXT_CONTROLLER');
                Application::container($container);
                if (!class_exists($controller_name)) {
                    throw new NotFoundException('控制器不存在');
                } elseif (!method_exists($controller_name, ACTION_NAME)) {
                    throw new NotFoundException('方法不存在');
                } else {
                    #执行方法
                    call_user_func(array(new $controller_name, ACTION_NAME));
                }
            },
            /*时区设置*/
            'timezone_set'=>function($c){
                date_default_timezone_set('PRC');
             },
            /*错误处理组件*/
            'error_handler_set'=>function($c){
                set_error_handler('errorHandle');
            },
            /*应用组件依赖注入*/
            'DEPENDENCY_INJECTION_MAP' => [],
            /*应用加载脚本*/
            'REQUIRE_SCRIPT_MAP' => [],
            /*框架自定义配置 结束*/

            /* 数据库设置 开始 */
            'DB' => array(
                'default' => array(
                    'connection_string' => 'sqlite::memory:',
                    'id_column' => 'id',
                    'id_column_overrides' => array(),
                    'error_mode' => \PDO::ERRMODE_EXCEPTION,
                    'username' => null,
                    'password' => null,
                    'driver_options' => [
                        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                        \PDO::ATTR_PERSISTENT => true,
                    ],
                    'logging' => true,
                    'caching' => false,
                    'caching_auto_clear' => false,
                    'return_result_sets' => false,
                    #下面三个配置打开会报错
                    /*
                    'identifier_quote_character' => null, // if this is null, will be autodetected
                    'limit_clause_style'         => null, // if this is null, will be autodetected
                    'logger'                     => null,
                    */
                )
            ),
            /* 数据库设置 结束*/

            /*应用设置 开始*/
            'EXT_CONTROLLER' => 'Controller',
            'EXT_MODEL' => 'Model',
            'DIR_CONTROLLER' => 'controller',
            'DIR_MODEL' => 'model',
            'DIR_VIEW' => 'view',
            '404_PAGE' => '',
            /*应用设置 结束*/

            /*http请求设置 开始*/
            /* URL设置 */
            'URL_MODE' => 0, //url访问模式  0：默认动态url传参模式 1：pathinfo模式 2:兼容模式
            /*默认设置*/
            'ALLOW_MODULE_LIST' => 'home',
            'DEFAULT_MODULE' => 'home',
            'DEFAULT_CONTROLLER' => 'Index',
            'DEFAULT_ACTION' => 'index',
            /* 系统变量名设置 */
            'VAR_CONTROLLER' => 'c',
            'VAR_ACTION' => 'a',
            'VAR_MODULE' => 'm',
            'VAR_ROUTE' => 'route',
            /*子域名泛解析设置*/
            'MAIN_DOMAIN' => '',
            'SUB_DOMAIN_OPEN' => true,
            'SUB_DOMAIN_RULES' => [
                'www' => 'home'
            ],
            /*http请求设置 结束*/
            /*模版主题*/
            'THEME' => 'default',
        );
    }
}