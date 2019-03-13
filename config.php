<?php
/**
 * Created by PhpStorm.
 * Date: 2019/3/12
 * Time: 18:11
 */

return array(
    # project setting
    "project" => array(

        "name" => "",

        # Project directory , defualt current directory
        "dir" => __DIR__,

        # Scan method
        "scan" => "file",
    ),


    "scan" => array(
        "file" => array(

            "black_dir" => array(
                "vendor",
                "libs",
                "lib",
                "include",
                "includes",
            ),

            "black_file"=> array(
                "api.php",
                "config.php",
                "scan.php",
            ),

            "ext" => array(
                "php",
            )
        ),
        "tp5" => array(

        )
    ),

);