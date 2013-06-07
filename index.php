<?php
define("APP_PATH",dirname(__FILE__));
define("SP_PATH",dirname(__FILE__).'/SpeedPHP');
$spConfig = array(

);
$apiConfig = include_once('./config/apiConfig.php');
require(SP_PATH."/SpeedPHP.php");
spRun();