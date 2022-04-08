<?php

class var_glob {
    public static $var = 0;
}

function trace_debug()
{
    $trace = array_map(static function($x) {
        return 'L' . $x['line'] . ' ' . $x['function'] . ' ' . pathinfo($x['file'], PATHINFO_BASENAME) . ' ' . $x['file'];
    }, debug_backtrace());

    for ($i=0; $i<9; $i++) {
        array_pop($trace);
    }

    array_shift($trace);

    if (var_glob::$var++ > 20) {
        // use case recursion error
        exit;
    }

    echo implode(PHP_EOL, $trace) . PHP_EOL;
}

function see($mixed, $text = '')
{
    echo "################ $text ################" . PHP_EOL;
    echo '<pre>';
    var_dump($mixed);
    echo '</pre>' . PHP_EOL;
}

function pointerAssignMin(&$ptr, $value)
{
    if (!isset($ptr)) {
        $ptr = $value;
    } elseif ($ptr > $value) {
        $ptr = $value;
    }
}

function pointerAssignMax(&$ptr, $value)
{
    if (!isset($ptr)) {
        $ptr = $value;
    } elseif ($ptr < $value) {
        $ptr = $value;
    }
}