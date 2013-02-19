<?php

/**
 * Helpers to work with arrays
 */
class Arr{

    /**
     * Fill an array with a range of numbers.
     *
     *     // Fill an array with values 5, 10, 15, 20
     *     $values = Arr::range(5, 20);
     *
     * @param   integer $step   stepping
     * @param   integer $max    ending number
     * @return  array
     */
    public static function range($step = 10, $max = 100)
    {
        if ($step < 1)
            return array();

        $array = array();
        for ($i = $step; $i <= $max; $i += $step)
        {
            $array[$i] = $i;
        }

        return $array;
    }

/**
 * Retrieve a single key from an array. If the key does not exist in the
 * array, the default value will be returned instead.
 *
 *     // Get the value "username" from $_POST, if it exists
 *     $username = Arr::get($_POST, 'username');
 *
 *     // Get the value "sorting" from $_GET, if it exists
 *     $sorting = Arr::get($_GET, 'sorting');
 *
 * @param   array   $array      array to extract from
 * @param   string  $key        key name
 * @param   mixed   $default    default value
 * @return  mixed
 */
public static function get($array, $key, $default = NULL)
{
    return isset($array[$key]) ? $array[$key] : $default;
}

}