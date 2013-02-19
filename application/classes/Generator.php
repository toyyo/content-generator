<?php 

class Generator{
    
    /**
     * Кол-во строк, которые будут вставляться за один запрос  
     * 
     * @var integer 
     */
    const COUNT_ROW_INSERTING = 100;

    /**
     * Максимальная длина генерируемой строки
     */
    const MAX_STRLEN = 50;
    
    const KEY_PRIMARY = 'PRI';
    const KEY_MULTIPLY = 'MUL';
    const KEY_UNI = 'UNI';
    const KEY_NONE = '';

    const TYPE_FIELD_INT = 'int';
    const TYPE_FIELD_VARCHAR = 'varchar';

    const AUTO_INCREMENT = 'auto_increment';
    const UNSIGNED = 'unsigned';

    /**
     * Кол-во записей, которые нужно сгенерировать
     * 
     * @var integer
     */
    //protected $_countRow;
    
    /**
     * Имя таблицы, в которую будем генерировать записи
     * 
     * @var string 
     */
    private $_tableName;
    
    /**
     * Структура таблицы
     * 
     * @var array 
     */
    private $_tableStruct;
    
    /**
     * Индексы таблицы
     * 
     * @var array 
     */
    private $_tableIndexes;

    /**
     * Значение AI ключа таблицы
     *
     * @var int
     */
    //protected $_maxAI;

    /**
     * Массив, в котором хранятся все вставленные данные
     *
     * @var array
     */
    private $_aInsertedRows = array();
    
    public function __construct($tableName){  
        $this->setTableName($tableName);
        $this->setTableStruct(Struct::getTableColumns($tableName));  
        $this->setTableIndexes(Struct::getTableIndexes($tableName));
    }

    private function addDataSuccess($aData){
        if (is_array($aData) && count($aData)){
            foreach ($aData as $row)
                array_push($this->_aInsertedRows, $row);
        }
    }

    /**
     * Get inserted data
     *
     * @return array
     */
    public function getInsertedData(){
//        if ($asHTML){
//            $count = count($this->_aInsertedRows);
//            $return = '<p>Вставлено '. $count . ' строк.</p>';
//            if (!$count)
//                return $return;
//            $return .= 'INSERT INTO '.$this->getTableName().' VALUES<br><ul>';
//            foreach ($this->_aInsertedRows as $aRow){
//                $return .= '<li>('.implode(', ', $aRow) . ')</li>';
//            }
//            $return .= '</ul>';
//            return $return;
//        }
        return $this->_aInsertedRows;
    }
    
    public function isReadyToUse(){
        return (bool)$this->getTableStruct();
    }
    
    private function setTableStruct($aStruct){
        $this->_tableStruct = $aStruct;  
    }
    
    private function setTableIndexes($aIndexes){
        $this->_tableIndexes = $aIndexes;  
    }
    
    private function setTableName($tableName){
        $this->_tableName = $tableName;
    }
    
    public function setCountRow($count){
        $this->_countRow = $count;
    }
    
    public function getTableStruct(){ 
        return $this->_tableStruct; 
    }
    
    public function getTableIndexes(){ 
        return $this->_tableIndexes; 
    }
    
//    public function getCountRow(){
//        return $this->_countRow;
//    }
    
    public function getTableName(){
        return $this->_tableName;
    }

    /*private function setAI($value){
        $this->_maxAI = (int)$value;
    }

    public function getNextAI(){
        if (!$this->_maxAI)
            return false;
        return (int)((int)$this->_maxAI + 1);
    }*/

    public function generate($count = 0){
        if (!$this->isReadyToUse())
            return array();
//        var_dump($this->getTableIndexes());
        $aData = array(); // массив рядов для вставки
        for ($i=0; $i<$count; $i++){
            $aRow = array(); // массив со значениями для вставки одного ряда
            foreach ($this->getTableStruct() as $k=>$oField){
                if ($this->isAI($oField)){    // если поле является AI
                    $aRow[$k] = 0;
//                elseif ($this->isUniq($oField))
                }else{  // все остальные поля
                    $aRow[$k] = '\''.$this->generateRandomValue($oField).'\'';
//                    if ($this->isUni()){
//
//
//                    }
                }
            }
            $aData[$i] = '('.implode(', ', $aRow).')';
            if (count($aData) >= self::COUNT_ROW_INSERTING){
                $this->inserting($aData);
                unset($aData);
            }
        }
        $this->inserting($aData);
    }

    /**
     * Вставка в БД
     *
     * @param array $aData      массив, элементами которого являются готовые строки
     * @return bool
     */
    public function inserting($aData){
        if (!count($aData))
            return 0;

        $values = implode(',', $aData);
        if (DB::instance()->query('INSERT INTO '.$this->getTableName().' VALUES '.$values ))
            $this->addDataSuccess($aData);
        return true;
    }

    /**
     * Return field type
     * e.g.: varchar, int, text etc.
     *
     * @param string $type
     * @return string
     */
    public function getFieldType($type){
        $posEnd = strpos($type, '(');
        if ($posEnd)
            return substr($type, 0, $posEnd);
        else
            return $type;
    }

    /**
     * Return max field length
     *
     * @param string $str
     * @return int
     */
    public function getFieldSize($str){
        $posStart = strpos($str, '(');
        $posEnd = strpos($str, ')');
        if ($posStart && $posEnd)
            return substr($str, $posStart+1, $posEnd-$posStart-1);
        else
            return self::MAX_STRLEN; // если не указан размер
    }

    /**
     * Return field attr
     * e.g. unsigned
     *
     * @param string $str
     * @return string
     */
    public function getFieldAttr($str){
        $posStart = strpos($str, ' ');
        return substr($str, $posStart+1);
    }

    public function isUnsigned($type){
        return ($this->getFieldAttr($type) === self::UNSIGNED);
    }

    public function isAI($oField){
        return ($oField->Extra === self::AUTO_INCREMENT);
    }

    public function isUni($oField){
        return ($oField->Key === self::KEY_UNI);
    }

    public function generateRandomValue($oField){
        $method = 'gen' . ucfirst($this->getFieldType($oField->Type));
        if (method_exists($this, $method)){
            if ($this->isUni($oField)){
                while (true){   // бесконечный цикл, пока не будет сгенерировано уникальное значение, или пока не вылетит пых
                    $newValue = $this->$method($oField);
                    if ($this->isNewValueisUni($oField, $newValue));
                        break;
                }
            }
            else{
                $newValue = $this->$method($oField);
            }
            return $newValue;
        }
        else{
            die('<br>Метод ' . $method . ' не существует. Допишите:)');
        }
    }

    /**
     * Проверяет, является ли значение уникальным
     *
     * @param object    $oField
     * @param string    $newValue
     * @return bool
     */
    public function isNewValueisUni($oField, $newValue){
        $isExists = (bool)DB::getOne('SELECT `'.$oField->Field.'` FROM '.$this->getTableName().' WHERE `'.$oField->Field.'`= '.$newValue.' ');
        if ($isExists){
            return false;
        }
        else{   // надо еще проверить в строках для вставки, которые еще не вставлены в БД

        }

    }

    /*public function genAI($oField){
        var_dump($this->getNextAI());
        if ($this->getNextAI()){    // если уже известно значение, берём его
//            $aRow[] = $this->getNextAI();
            $this->setAI($this->getNextAI());
            return $this->getNextAI();
        }
        else{  // иначе запрос в БД
            $maxAI = (int)DB::getOne('SELECT MAX('.$oField->Field.') FROM ' . $this->getTableName());
            $this->setAI($maxAI + 1);
            return ($maxAI + 1);
        }
    }*/

    public function genInt($oField){
        $maxStrlen = $this->getFieldSize($oField->Type);
        if (!$this->isUnsigned($oField->Type))
            $maxStrlen -= 1; // один символ под знак

        $maxValue = NULL;
        for ($i=0; $i<$maxStrlen; $i++){
            $maxValue .= '9';
        }
        return rand(0, (int)$maxValue);
    }

    public function genVarchar($oField){
        $maxStrlen = $this->getFieldSize($oField->Type);
        if (!$maxStrlen || ($maxStrlen > self::MAX_STRLEN))
            $maxStrlen = self::MAX_STRLEN;

        return $this->random(1, $maxStrlen);

    }

    public function genChar($oField){
            $maxStrlen = $this->getFieldSize($oField->Type);
        if (!$maxStrlen || ($maxStrlen > self::MAX_STRLEN))
            $maxStrlen = self::MAX_STRLEN;

        return $this->random(1, $maxStrlen);
    }

    public function genText($oField){
        $maxStrlen = $this->getFieldSize($oField->Type);
        if (!$maxStrlen || ($maxStrlen > self::MAX_STRLEN))
            $maxStrlen = self::MAX_STRLEN;

        return $this->random(1, $maxStrlen);
    }

    /**
     * Generates a random string of a given type and length.
     *
     *
     *     $str = Text::random(); // 8 character random string
     *
     * The following types are supported:
     *
     * alnum
     * :  Upper and lower case a-z, 0-9 (default)
     *
     * alpha
     * :  Upper and lower case a-z
     *
     * hexdec
     * :  Hexadecimal characters a-f, 0-9
     *
     * distinct
     * :  Uppercase characters and numbers that cannot be confused
     *
     *
     * @param   integer $min_length
     * @param   integer $max_length length of string to return
     * @param   string  $type
     * @return  string
     */
    public static function random($min_length = 1, $max_length = 8, $type = 'alnum')
    {
        switch ($type)
        {
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'hexdec':
                $pool = '0123456789abcdef';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'nozero':
                $pool = '123456789';
                break;
            case 'distinct':
                $pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
                break;
        }

        // Split the pool into an array of characters
        $pool = str_split($pool, 1);

        // Largest pool key
        $max = count($pool) - 1;
        $length = rand($min_length, $max_length);
        $str = '';
        for ($i = 0; $i < $length; $i++)
        {
            // Select a random character from the pool and add it to the string
            $str .= $pool[mt_rand(0, $max)];
        }

        // Make sure alnum strings contain at least one letter and one digit
        if ($type === 'alnum' AND $length > 1)
        {
            if (ctype_alpha($str))
            {
                // Add a random digit
                $str[mt_rand(0, $length - 1)] = chr(mt_rand(48, 57));
            }
            elseif (ctype_digit($str))
            {
                // Add a random letter
                $str[mt_rand(0, $length - 1)] = chr(mt_rand(65, 90));
            }
        }

        return $str;
    }
    
    
    
    
} 