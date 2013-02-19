<?php

/**
 * Класс для работы с таблицами, индексами
 * @use DB
 */
class Struct {
    
    public static function getTables(){ 
        $dbres = DB::getAll("SHOW TABLES");
        $property = 'Tables_in_' . DB::getDatabaseName();
        $aTables = array();
        foreach ($dbres as $oRow){
            $aTables[] = $oRow->$property;
        }
        return $aTables;
    }

    /**
     * Return array:
     * ключ массива - имя индексного поля
     * значение - объект индекса
     *
     * @param string $tableName
     * @return array
     */
    public static function getTableIndexes($tableName){
        $mysqli_result = DB::instance()->query('SHOW INDEX FROM '.$tableName);
        $aIndexes = array();
        if($mysqli_result){
            // Cycle through results
            while ($oRow = $mysqli_result->fetch_object()){
                $aIndexes[$oRow->Column_name] = $oRow;
            }
        }
        return $aIndexes;
    }

    /**
     * @param string $tableName
     * @return array
     */
    public static function getTableColumns($tableName){
        return DB::getAll('SHOW COLUMNS FROM '.$tableName);
    }

}