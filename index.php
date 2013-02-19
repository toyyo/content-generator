<?php

define('ROOT', dirname(__FILE__));
require_once (ROOT . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'bootstrap.php');

$q = isset($_GET['q']) ? $_GET['q'] : 'db_connect';

$body = NULL;

switch ($q){
    case 'db_connect':
        if (isset($_POST) && $_POST){
            $mysqli = DB::connect($_POST);
            if (!$mysqli->connect_error){ // если коннект прошёл
                Messages::put('Коннект с БД успешно установлен');
                $_SESSION['db_connect'] = array(    //  запоминаем параметры коннекта к БД
                    'dbhost' => Arr::get($_POST, 'dbhost'),
                    'dbuser' => Arr::get($_POST, 'dbuser'),
                    'dbpass' => Arr::get($_POST, 'dbpass'),
                    'dbname' => Arr::get($_POST, 'dbname'),
                );

                header('Location: '. URL_ROOT . '?q=show_struct');
                exit;
            }
            else{
                Messages::put('Указаны неверные данные для входа' , Messages::TYPE_ERROR);
            }
        }
        $body = View::factory('form_db_connect');
            // ->set('body', 'aaaaaaaaaaa!!!!!!!!');
        break;

    case 'logout':
        unset($_SESSION['db_connect']);
        header('Location: '. URL_ROOT);
        exit;
        break;
    case 'show_struct':
    default:

        if (!Arr::get($_SESSION, 'db_connect')){
            header('Location:' . URL_ROOT);
        }
        DB::connect(Arr::get($_SESSION, 'db_connect'));
        $body = View::factory('show_struct')->set('aTables', Struct::getTables());

        if (isset($_POST['count_generate']) && isset($_GET['table'])){
            $oGen = new Generator($_GET['table']);
            $oGen->generate($_POST['count_generate']); // генерируем $_POST['count_generate'] рядов
            $body->set('aInsertedData', $oGen->getInsertedData());
        }
        if (isset($_GET['table'])){
            $body->set('tableName', $_GET['table']);      
            $body->set('aColumns', Struct::getTableColumns($_GET['table']));    
            $body->set('aIndexes', Struct::getTableIndexes($_GET['table']));
            $body->set('dbName', DB::getDatabaseName());
        }
        break;
}

echo View::factory('main_layout')
    ->set('body', $body)
    ->set('styles', array(URL_CSS . 'css.css'))
    ->render();

