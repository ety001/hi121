<?php
define("APP_PATH",dirname(__FILE__));
define("SP_PATH",dirname(__FILE__).'/SpeedPHP');
$spConfig = array(
	"db" => array(
        'driver' => 'mysql',
        'host' => 'localhost',
        'login' => 'root',
        'password' => '',
        'database' => 'club121',
        'prefix' => 'club121_'
    ),
	'view' => array( // 视图配置
        'enabled' => TRUE, // 开启视图
        'config' =>array(
                'template_dir' => APP_PATH.'/tpl', // 模板目录
        ),
        'engine_name' => 'speedy', // 模板引擎的类名称
        'engine_path' => SP_PATH.'/Drivers/speedy.php', // 模板引擎主类路径
	),
);
$apiConfig = include_once('./config/apiConfig.php');
require(SP_PATH."/SpeedPHP.php");
spRun();