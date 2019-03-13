<?php
/**
 * Created by PhpStorm.
 * Date: 2019/3/14
 * Time: 0:55
 */
$config = require_once "config.php";


if(!isset($_GET["action"]) && !is_string($_GET["action"]) ){
    exit();
}

switch ($_GET["action"]){
    case "scan":
        require_once "scan.php";
        scan($config);
        break;
    default:
        exit();
}


