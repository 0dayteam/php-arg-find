<?php
/**
 * Created by PhpStorm.
 * Date: 2019/3/14
 * Time: 1:11
 */

require_once "scan.php";
use PHPUnit\Framework\TestCase;

class TestScan extends TestCase
{
    function test_scan(){
        $config = array(
            "project" => array(
                "scan" => "test",
                "dir" => __DIR__,
            ),
            "scan" => array(
              "test" => array(

              )
            ),
        );
        function scan_test(){
            return array();
        }
        $result = scan($config);
        $this->assertEquals($result, array(), "scan method call error");
    }

    function test_scanfile(){
        $config = array(
                    "black_dir" => array(),
                    "black_file"=> array(),
                    "ext" => array("php"),
        );
        $result = scan_file("not exist", $config);
        $this->assertEquals($result,array(),"scan not exist dir should return array");
        $config["black_dir"][] = "black";
        $testdir = "testcase". DIRECTORY_SEPARATOR  . "scanfile";
        $result = scan_file($testdir, $config);
        $this->assertNotTrue(in_array($testdir.DIRECTORY_SEPARATOR."black".DIRECTORY_SEPARATOR."blacktest.php", $result, "error blackdir"));
        $config["black_file"][] = "black.php";
        $result = scan_file($testdir, $config);
        $this->assertNotTrue(in_array($testdir.DIRECTORY_SEPARATOR."black.php", $result, "error blackfile"));
        $this->assertNotFalse(in_array($testdir.DIRECTORY_SEPARATOR."white.php", $result, "error blackfile"));
    }

    # testcase too big
    /*
    function test_scantp5(){
        $tp5path = pathjoin("testcase", "tp5");
        $result = scan_tp5($tp5path,array());
        var_dump($result);
        $this->assertNotEmpty($result);
    }
    */
}
