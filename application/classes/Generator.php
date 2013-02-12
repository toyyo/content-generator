<?php 

class Generator/* extends DB*/{ 
    
    /**
     * Кол-во строк, которые будут вставляться за один запрос  
     * 
     * @var integer 
     */
    const COUNT_ROW_INSERTING = 100;
    
    const KEY_PRIMARY = 'PRI';
    const KEY_MULTIPLY = 'MUL'; 
    
    /**
     * Кол-во записей, которые нужно сгенерировать
     * 
     * @var integer
     */
    protected $_countRow; 
    
    /**
     * Имя таблицы, в которую будем генерировать записи
     * 
     * @var string 
     */
    protected $_tableName;
    
    /**
     * Структура таблицы
     * 
     * @var array 
     */
    protected $_tableStruct;
    
    /**
     * Индексы таблицы
     * 
     * @var array 
     */
    protected $_tableIndexes; 
    
    public function __construct($tableName){  
        $this->setTableName($tableName);
        $this->setTableStruct(Struct::getTableColumns($tableName));  
        $this->setTableIndexes(Struct::getTableIndexes($tableName));
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
    
    public function getCountRow(){
        return $this->_countRow;
    }
    
    public function getTableName(){
        return $this->_tableName;
    } 
    
    public function generate($count = 0){
        if (!$this->isReadyToUse())
            return array();
        $aData = array();   
        foreach ($this->getTableStruct() as $oField){ 
            var_dump($oField);
            if ($oField->Key == self::KEY_PRIMARY){
                var_dump($this->getTableIndexes());
                
            } 
            
            
        }
    }
    
    
    
    
    
} 