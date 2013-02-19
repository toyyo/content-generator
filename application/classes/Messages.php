<?php

class Messages {

    const TYPE_ERROR   = 'error';
    const TYPE_SUCCESS = 'success';

    private static $_buffer;

    private static function getBuffer(){
        if (!isset($_SESSION['_messages']))
            $_SESSION['_messages'] = array();
        Messages::$_buffer=&$_SESSION['_messages'];
    }

    public static function clear(){
        Messages::getBuffer();
        Messages::$_buffer = array();
    }

    /**
     * Add message to buffer
     *
     * @param string $type
     * @param string $code
     * @return boolean
     */
    public static function put($message, $type = self::TYPE_SUCCESS){
        Messages::getBuffer();
        if ($message === false)
            return false;

        Messages::$_buffer[$type][] = $message;
        return TRUE;
    }

    /**
     * Get all messages array
     *
     * @return array
     */
    public static function getAll(){
        Messages::getBuffer();
        return Messages::$_buffer;
    }

    /**
     * View stored messages
     * @return string
     */
    public static function view(){
        Messages::getBuffer();
        $sHtml = '';
        if (count(Messages::$_buffer)){
            foreach (Messages::$_buffer as $sType=>$aMessages){
                if (count($aMessages)){
                    $sHtml .= View::factory('messages')
                        ->bind('sType',$sType)
                        ->bind('aMessages',$aMessages)
                        ->render();
                }
            }
            Messages::clear();
        }
        return $sHtml;
    }

}
