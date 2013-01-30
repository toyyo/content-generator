<?php 

class DB {
    
    const ERROR_TEXT = 'Ошибка: сначала нужно вызвать DB::connect() c параметрами';
    
    // DB instances
    protected static $_instance; 
    
//     protected static $_dbhost;
//     protected static $_dbuser;
//     protected static $_dbpass;
//     protected static $_dbname; 
    
//     public static function getDbHost(){
//         return self::$_dbhost; 
//     }
    
    public static function connect($dbhost = 'localhost', $dbuser = 'dbuser', $dbpass = 'dbpass', $dbname = 'dbname'){    
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $dbname = 'gen';
//         self::$_dbhost = $dbhost;
//         self::$_dbhost = $dbuser;
//         self::$_dbhost = $dbpass;
//         self::$_dbhost = $dbname;   

        $mysqli = @new mysqli($dbhost, $dbuser, $dbpass, $dbname);     

        if (!$mysqli->connect_error){ 
            DB::$_instance = $mysqli;
        }           
//        /**
//         * Это "официальный" объектно-ориентированный способ сделать это 
//         * однако $connect_error не работал вплоть до версий PHP 5.2.9 и 5.3.0.
//         */
//         if ($mysqli->connect_error) {
//             die('Ошибка подключения (' . $mysqli->connect_errno . '): '
//                     . $mysqli->connect_error);
//         }   

        return $mysqli;  
    } 
    
    public static function getDatabaseName(){ 
        if ( ! isset(DB::$_instance)){
            die(self::ERROR_TEXT);  
        }
    }
    
    /** 
     * Реализация Singleton pattern 
     *
     * @return DB
     */
    public static function instance(){
        // see connect() 
        if ( ! isset(DB::$_instance)){ 
            die('Ошибка: сначала нужно вызвать DB::connect()');    
        } 
        return DB::$_instance;  
    }
} 