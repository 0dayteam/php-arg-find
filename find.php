<?php
/**
 * Created by PhpStorm.
 * Date: 2019/3/23
 * Time: 15:42
 */
use think\Request;
require_once 'hook/hook.php';


/**
 * @param $config
 * @return mixed
 */
function find($config){
    $method = "find_".$config["project"]["scan"];
    return $method($config["project"]["dir"], $config["find"][$config["project"]["find"]]);
}

function find_file($dir, $config){
    $_GET = new ArrayHook($_GET);
    $_POST = new ArrayHook($_POST);
    $_COOKIE = new ArrayHook($_COOKIE);
    set_include_path($dir);
    include $_SERVER['REQUEST_URI'];
    return array($_GET,$_POST,$_COOKIE);
}

function find_tp5($dir, $config){
    $arg = array(
        "get"=>array(),
        "post"=>array(),
        "cookie"=>array(),

    );
    function get_arg($arg){
        $arg["get"][]=$arg;
    }
    function post_arg($arg){
        $arg["post"][]=$arg;
    }
    function cookie_arg($arg){
        $arg["cookie"][]=$arg;
    }
    set_include_path($dir);
    require_once 'thinkphp/base.php';
    $req = Request::instance();
    $req::hook("get", "get_arg");
    $req::hook("post", "post_arg");
    try{
        # todo 加入配置
        include "public/index.php";
      //  include "index.php";
    }catch (Exception $e){
        # todo 输出异常信息
    }
    return $arg;
}

function pathjoin($dir, $filename){
    return $dir.DIRECTORY_SEPARATOR.$filename;
}