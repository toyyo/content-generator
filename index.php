<?php

header('Content-Type: text/html; charset=utf-8');

define('SYSPATH', 'aa'); 
define('ROOT', dirname(__FILE__));
// var_dump($_SERVER['SERVER_NAME']);  

define('URL_ROOT', 'http://' . $_SERVER['SERVER_NAME'] . '/index.php');  
define('URL_CSS', 'http://' . $_SERVER['SERVER_NAME'] . '/css/');     

require_once ROOT . '/system/classes/View.php';
require_once ROOT . '/application/classes/DB.php';  
require_once ROOT . '/application/classes/Struct.php';
require_once ROOT . '/application/classes/Generator.php';

DB::connect();

$do = isset($_GET['q']) ? $_GET['q'] : 'db_connect';   

$body = NULL; 

// DB::instance();    

switch ($do){ 
    case 'db_connect':
        $body = View::factory('db_connect_form'); 
            // ->set('body', 'aaaaaaaaaaa!!!!!!!!');
        break; 

    case 'show_struct': 
        if (isset($_POST['count_generate']) && isset($_GET['table'])){
            $oGen = new Generator($_GET['table']); 
            $oGen->generate($_POST['count_generate']); // генерируем $_POST['count_generate'] рядов
        }
        $body = View::factory('show_struct')->set('aTableNames', Struct::getTables()); 
        if (isset($_GET['table'])){
            $body->set('tableName', $_GET['table']);      
            $body->set('aColumns', Struct::getTableColumns($_GET['table']));    
            $body->set('aIndexes', Struct::getTableIndexes($_GET['table']));       
        }
        break;
}

// require_once ROOT . '/application/classes/views/main_layout.php';    
// try{
//     $link = mysqli_connect("localhost", "root", "", "gens");  
// }

echo View::factory('main_layout')
    ->set('body', $body)
    ->set('styles', array(URL_CSS . 'css.css')); 
    

/* check connection */
// if (mysqli_connect_errno()){ 
//     printf("Connect failed: %s\n", mysqli_connect_error());
//     exit();
// } 
// else{
//     // echo 'oke db connect';  
// } 
  