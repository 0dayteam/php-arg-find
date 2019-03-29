<?php
/**
 * Created by PhpStorm.
 * Date: 2019/3/12
 * Time: 18:16
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * @param $config
 * @return mixed
 */
function scan($config)
{
    $method = "scan_" . $config["project"]["scan"];
    return $method($config["project"]["dir"], $config["scan"][$config["project"]["scan"]]);
}

/**
 * @param $dir
 * @param $config
 * @return array
 */
function scan_file($dir, $config)
{
    if (!is_dir($dir)) {
        return array();
    }
    $len = 0;
    $files = array();
    $dir_name = explode(DIRECTORY_SEPARATOR, $dir);
    if (in_array(end($dir_name), $config["black_dir"])) {
        echo "black";
        return array();
    }
    $handle = opendir($dir);
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
            if ($file == "." || $file == "..") {
                continue;
            }
            $filename = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_file($filename)) {
                if (in_array($file, $config["black_file"])) {
                    continue;
                }
                $ext = explode(".", $filename);
                if (in_array(end($ext), $config["ext"])) {
                    $real_name = substr($filename, $len);
                    if (in_array($real_name, $config["black_file"])) {
                        continue;
                    }
                    $files[] = $real_name;
                }
            } else {
                $ret = scan_file($filename, $config);
                $files = array_merge($files, $ret);
            }
        }
    }
    closedir($handle);
    return $files;
}

/**
 * @param $dir
 * @param $config
 * @return array
 * @throws ReflectionException
 */
function scan_tp5($dir, $config)
{
    $files = array();
    set_include_path($dir);
    try {
        # todo 加入配置
        include "public/index.php";
        //  include "index.php";
    } catch (Exception $e) {
        # todo 输出异常信息
    }
    $apppath = defined("APP_PATH") ? APP_PATH : pathjoin($dir, "application");
    $dir = _scan_dir($apppath);
    foreach ($dir as $dirname) {
        $result = _scan_dir($dirname);
        if (in_array(pathjoin($dirname, "controller"), $result)) {
            $controllers = _scan_file(pathjoin($dirname, "controller"));
            foreach ($controllers as $controller) {
                include_once $controller;
                // TODO  处理异常
                /*
            try {
            include $controller;
            }catch (Exception $e){
            echo $e;
            }*/
            }

        }
        $dir = explode(DIRECTORY_SEPARATOR, $dirname);
        # todo 使用thinkphp5常量
        $namespace = "app\\" . end($dir) . "\\controller";
        foreach (\get_declared_classes() as $class) {
            if (strpos($class, $namespace) === 0) {
                //  $c = substr($class, strlen($namespace));

                # $methods = get_class_methods($class);
                $controller = new ReflectionClass($class);

                $methods = $controller->getMethods(ReflectionMethod::IS_PUBLIC);
                foreach ($methods as $method) {
                    if ($method->name[1] != "_") {
                        $tmp = explode("\\", $method->class);
                        $files[] = "index.php/" . $tmp[1] . "/" . end($tmp) . "/" . $method->name;
                    }
                }

            }
        }
    }

    #$files = array();
    return $files;
}

/**
 * scan dir function
 *
 * @param string $dir
 * @return array
 */
function _scan_dir($dir)
{
    $files = array();
    $handle = opendir($dir);
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
            if ($file == "." || $file == "..") {
                continue;
            }
            $filename = pathjoin($dir, $file);
            if (!is_file($filename)) {
                $files[] = $filename;
            }
        }
    }
    return $files;
}

/**
 * scan file fucntion
 *
 * @param String $dir
 * @return Array
 */
function _scan_file($dir)
{
    $files = array();
    $handle = opendir($dir);
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
            if ($file == "." || $file == "..") {
                continue;
            }
            $filename = pathjoin($dir, $file);
            if (is_file($filename)) {
                $files[] = $filename;
            }
        }
    }
    return $files;
}

function pathjoin($dir, $filename)
{
    return $dir . DIRECTORY_SEPARATOR . $filename;
}
