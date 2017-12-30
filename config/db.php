<?php
if (ENVIRONMENT == 'develop') {
    //开发环境
    return [
        //主域名
        'MAIN_DOMAIN' => 'houduanniu.me',
        //链接地址
        'HOME_URL' => 'http://blog.houduanniu.me',
        'ADMIN_URL' => 'http://admin.houduanniu.me',
        'API_URL' => 'http://api.houduanniu.me',
        //数据配置
        'DB' => [
            /**
             * 默认数据库配置
             */
            'default' => array(
                'connection_string' => 'mysql:host=houduanniu.com;dbname=houduanniu_dev;port=3306',
                'id_column' => 'id',
                'id_column_overrides' => array(),
                'error_mode' => \PDO::ERRMODE_EXCEPTION,
                'username' => 'root',
                'password' => 'fr1314520',
                'driver_options' => [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    \PDO::ATTR_PERSISTENT => false
                ],
                'logging' => true,
                'caching' => false,
                'caching_auto_clear' => false,
                'return_result_sets' => false
            ),
        ],
    ];
} elseif (ENVIRONMENT == 'product') {
    //生成环境
    return [
        //主域名
        'MAIN_DOMAIN' => 'houduanniu.com',
        //链接地址
        'HOME_URL' => 'http://blog.houduanniu.com',
        'ADMIN_URL' => 'http://admin.houduanniu.com',
        'API_URL' => 'http://api.houduanniu.com',
        //主域名
        'MAIN_DOMAIN' => 'houduanniu.me',
        //链接地址
        'HOME_URL' => 'http://blog.houduanniu.me',
        'ADMIN_URL' => 'http://admin.houduanniu.me',
        'API_URL' => 'http://api.houduanniu.me',
        //数据配置
        'DB' => [
            /**
             * 默认数据库配置
             */
            'default' => array(
                'connection_string' => 'mysql:host=127.0.0.1;dbname=houduanniu_pro;port=3306',
                'id_column' => 'id',
                'id_column_overrides' => array(),
                'error_mode' => \PDO::ERRMODE_EXCEPTION,
                'username' => 'root',
                'password' => 'fr1314520',
                'driver_options' => [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    \PDO::ATTR_PERSISTENT => true,
                ],
                'logging' => true,
                'caching' => false,
                'caching_auto_clear' => false,
                'return_result_sets' => false
            ),
        ],
    ];
}

