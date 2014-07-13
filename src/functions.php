<?php

/**
 * Better print_r
 *
 * @param  array   $array  Array to print
 * @param  boolean $return Return or print
 * @return string
 */
function print_pre($array=array(), $return=false)
{
    $out = "<pre>";
    $out .= print_r($array, true);
    $out .= "</pre>";
    if ($return) {
        return $out;
    } else {
        echo $out;
    }
}
