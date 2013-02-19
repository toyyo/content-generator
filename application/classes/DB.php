<?php

/**
 * Обёртка для работы с mysqli
 * e.g. Вернуть одну строку:     DB::getRow('SELECT * FROM table WHERE id=1');
 * вернуть одно значение:        DB::getOne('SELECT `name` * FROM table WHERE id=1');
 * вернуть объект mysqli_result  DB::instance()
 */
class DB {
    
    const ERROR_TEXT = 'Ошибка: сначала нужно вызвать DB::connect()';
    
    // DB instances
    protected static $_instance; 
    
    /*private static $_dbhost;
    private static $_dbuser;
    private static $_dbpass;*/
    private static $_dbname;
    
    public static function connect($aParams){

        $mysqli = @new mysqli($aParams['dbhost'], $aParams['dbuser'], $aParams['dbpass'], $aParams['dbname']);

        if (!$mysqli->connect_error){ 
            DB::$_instance = $mysqli;
            DB::$_dbname = $aParams['dbname'];
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
        return DB::$_dbname;
    }

    /**
     * Возвращает значение одного поля из результата запроса
     *
     * @param string $query
     * @return string
     */
    public static function getOne($query){
        $mysqli_result = self::instance()->query($query);
        if($mysqli_result){
            $row = $mysqli_result->fetch_row();
            if ($row)
                return $row[0];
        }
        return NULL;
    }

    /**
     * Возвращает один ряд из таблицы в виде массива
     *
     * @param string  $query
     * @param bool    $asArray
     * @return array | object
     */
    public static function getRow($query, $asArray = false){
        $mysqli_result = self::instance()->query($query);
        if($mysqli_result){
            $row = $mysqli_result->fetch_row();
            if ($row){
                if ($asArray)
                    return (array)$row;
                else
                    return $row;
            }
        }
        return array();
    }

    /**
     * Возвращает массив объектов рядов
     *
     * @param string  $query
     * @return string
     */
    public static function getAll($query){
        $mysqli_result = self::instance()->query($query);
        $aRes = array();
        if($mysqli_result){
            // Cycle through results
            while ($row = $mysqli_result->fetch_object()){
                $aRes[] = $row;
            }
        }
        return $aRes;
    }

    /** 
     * Реализация Singleton pattern 
     *
     * @return DB
     */
    public static function instance(){
        // see connect() 
        if ( ! isset(DB::$_instance)){ 
            die(self::ERROR_TEXT);   // м.б. только на этапе разработки
        } 
        return DB::$_instance;
    }
} 