<?php 

class Struct extends DB{
    
    public static function getTables(){ 
        $dbres = DB::instance()->query("SHOW TABLES");  
        return self::getRows($dbres); 
    }
    
    public static function isTableExists(){
        var_dump(DB::instance()->query("SHOW TABLES WHERE ``"));  
    }
    
    public static function test(){  
        $result = DB::instance()->query("SELECT * FROM test1 ");
        
        if($result){ 
            // Cycle through results
            while ($row = $result->fetch_object()){
                $user_arr[] = $row;
            }
             
//             // Free result set
//             $result->close();
//             DB::instance()->next_result();
        } 
//         var_dump($user_arr[0]->name);  
        return $user_arr;
        
//         var_dump($res);
//         return DB::instance()->query('SHOW TABLES');
//         $result = DB::instance()->query('SELECT * FROM test1');  
//         if($result){
//             // Cycle through results
//             while ($row = $result->fetch_object()){
//                 $user_arr[] = $row;
//             }
//             // Free result set
//             $result->close();
//             DB::instance()->next_result();
//         }
//         return $user_arr;
    }
    
    public static function getTableIndexes($tableName){
        $dbres = DB::instance()->query('SHOW INDEX FROM '.$tableName);
        return self::getRows($dbres);
    }
    
    public static function getTableColumns($tableName){
        $dbres = DB::instance()->query('SHOW COLUMNS FROM '.$tableName);
        return self::getRows($dbres);
    }
    
    public static function getRows($mysqli_result){ 
        $aRes = array(); 
        if($mysqli_result){
            // Cycle through results
            while ($row = $mysqli_result->fetch_object()){
                $aRes[] = $row;
            }
        }
        return $aRes; 
    }
    
    
} 