<?php
/** Dump and die
 * @param $var
 */
function dd($var) {
    echo '<pre>',var_dump($var),'</pre>';
    die();
}

/** Print and die
 * @param $var
 */
function pd($var) {
    echo '<pre>',print_r($var),'</pre>';
    die();
}