<?php

return array(
    'LAYOUT_ON' => true,
    'LAYOUT_NAME' => 'layout',
    //'SHOW_PAGE_TRACE' => true, //开启页面跟踪
    'URL_CASE_INSENSITIVE' => true, //忽略URL中大小写
    'URL_MODEL' => 1, //PATHINFO模式
    //数据库配置
    'DB_TYPE' => 'mysql',
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'socialwork',
    'DB_USER' => 'root',
    //'DB_PWD' => '57419',
    'DB_PWD' => 'root',
    //'DB_PWD' => 'cuitcuit',
    'DB_PORT' => '3306',
    'DB_PREFIX' => 'sjf_',
    //默认模块与控制器为登陆页面
    'DEFAULT_MODULE' => 'Login',
    'DEFAULT_ACTION' => 'login',
    'TMPL_CACHE_ON' => false,
    'TMPL_PARSE_STRING' => array(
        //项目目录下的Public存放image,css,js
        //css取名以控制器+操作为规范，如Admin_index.css
        //js同样如此
        '__JS__' => '/socialwork/Public/Js',
        '__CSS__' => '/socialwork/Public/Css',
        '__IMAGE__' => '/socialwork/Public/Image',
    ),
    //routes
    'URL_ROUTER_ON' => true,
    'URL_ROUTE_RULES' => array(
        //controller: Street
        'Street/edit/:id\d' => 'Street/edit',
        //controller: Yard
        'Yard/:id\d' => 'Yard/read',
        'Yard/edit/:id\d' => 'Yard/edit',
        'Yard/delete/:id\d' => 'Yard/delete',
        //controller: house
        'House/:id\d' => 'House/read',
        'House/edit/:id\d' => 'House/edit',
        'House/delete/:id\d' => 'House/delete',
        'House/toexcel/:id\d' => 'House/toexcel',
        //yardid  address_1  address_2  address_3
        '/^House\/table_(\d+)$/' => 'House/table?yardid=:1',
        '/^House\/table_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2',
        '/^House\/table_(\d+)_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&address_2=:3',
        '/^House\/table_(\d+)_(\d+)_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&address_2=:3&address_3=:4',
        //廉租房的router
        '/^House\/table\/lowrent_(\d+)$/' => 'House/table?yardid=:1&lowrent=1',
        '/^House\/table\/lowrent_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&lowrent=1',
        '/^House\/table\/lowrent_(\d+)_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&address_2=:3&lowrent=1',
        '/^House\/table\/lowrent_(\d+)_(\d+)_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&address_2=:3&address_3=:4&lowrent=1',
        //享受燃煤补贴的router
        '/^House\/table\/fuel_(\d+)$/' => 'House/table?yardid=:1&fuel=1',
        '/^House\/table\/fuel_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&fuel=1',
        '/^House\/table\/fuel_(\d+)_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&address_2=:3&fuel=1',
        '/^House\/table\/fuel_(\d+)_(\d+)_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&address_2=:3&address_3=:4&fuel=1',
        //台属房屋的router
        '/^House\/table\/taiwan_(\d+)$/' => 'House/table?yardid=:1&taiwan=1',
        '/^House\/table\/taiwan_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&taiwan=1',
        '/^House\/table\/taiwan_(\d+)_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&address_2=:3&taiwan=1',
        '/^House\/table\/taiwan_(\d+)_(\d+)_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&address_2=:3&address_3=:4&taiwan=1',
        //军属房屋的router
        '/^House\/table\/army_(\d+)$/' => 'House/table?yardid=:1&army=1',
        '/^House\/table\/army_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&army=1',
        '/^House\/table\/army_(\d+)_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&address_2=:3&army=1',
        '/^House\/table\/army_(\d+)_(\d+)_(\d+)_(\d+)$/' => 'House/table?yardid=:1&address_1=:2&address_2=:3&address_3=:4&army=1',
        //居民统计表格
        '/^House\/ctable_(\d+)$/' => 'House/ctable?yardid=:1',
        '/^House\/ctable_(\d+)_(\d+)$/' => 'House/ctable?yardid=:1&address_1=:2',
        '/^House\/ctable_(\d+)_(\d+)_(\d+)$/' => 'House/ctable?yardid=:1&address_1=:2&address_2=:3',
        '/^House\/ctable_(\d+)_(\d+)_(\d+)_(\d+)$/' => 'House/ctable?yardid=:1&address_1=:2&address_2=:3&address_3=:4',
        //controller: Organization
        'Organization/:id\d' => 'Organization/read',
        'Organization/edit/:id\d' => 'Organization/edit',
        'Organization/delete/:id\d' => 'Organization/delete',
        //controller: Store
        'Store/:id\d' => 'Store/read',
        'Store/edit/:id\d' => 'Store/edit',
        'Store/delete/:id\d' => 'Store/delete',
        //controller: Ngo
        'Ngo/:id\d' => 'Ngo/read',
        'Ngo/edit/:id\d' => 'Ngo/edit',
        'Ngo/delete/:id\d' => 'Ngo/delete',
        //controller: Party
        'Party/:id\d' => 'Party/read',
        'Party/edit/:id\d' => 'Party/edit',
        'Party/delete/:id\d' => 'Party/delete',
        //controller: Parter
        'Parter/:id\d' => 'Parter/read',
        'Parter/edit/:id\d' => 'Parter/edit',
        'Parter/delete/:id\d' => 'Parter/delete',
        //controller: Citizen
        'Citizen/:id\d' => 'Citizen/read',
        'Citizen/edit/:id\d' => 'Citizen/edit',
        'Citizen/delete/:id\d' => 'Citizen/delete',
        'Citizen/inhouse/:id\d' => 'Citizen/newone',
        'Citizen/toexcel/:id\d' => 'Citizen/toexcel',
        ////controller: Camera
        'Camera/:id\d' => 'Camera/read',
        'Camera/edit/:id\d' => 'Camera/edit',
        'Camera/delete/:id\d' => 'Camera/delete',
        //controller: Old
        'Old/:id\d' => 'Old/read',
        'Old/edit/:id\d' => 'Old/edit',
        'Old/delete/:id\d' => 'Old/delete',
        'Old/toexcel/:id\d' => 'Old/toexcel',
        //controller: Service
        'Service/:id\d' => 'Service/read',
        'Service/edit/:id\d' => 'Service/edit',
        'Service/delete/:id\d' => 'Service/delete',
    ),
);
?>
