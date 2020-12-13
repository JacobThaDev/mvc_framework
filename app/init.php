<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    session_start();
    use Illuminate\Database\Capsule\Manager as DB;

    require_once 'config.php';
    include 'vendor/autoload.php';
    
    $dirs = [
        "app/core/",
        "app/core/acl/",
        "app/controllers/",
        "app/models/",
        "app/plugins/"
    ];

    foreach($dirs as $dir) {
        foreach (glob($dir.'*.php') as $filename) {
            include_once(''.$filename.'');
        }
    }

    $db = new DB;

    foreach (sql_databases as $key => $value) {
        $db->addConnection([
            "driver"   => "mysql",
            "host"     => $value['host'],
            "database" => $value['database'],
            "username" => $value['username'],
            "password" => $value['password'],
        ], $key);
    }
    
    $db->setAsGlobal();
    $db->bootEloquent();