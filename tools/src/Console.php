<?php

namespace Sbj\tools;

class Console
{
    public static function error(string $str = ''): void
    {
        self::bell();
        self::log($str, 'e');
        exit(1);
    }

    public static function success(string $str = ''): void
    {
        self::log($str, 's');
        exit(0);
    }

    public static function info(string $str = ''): void
    {
        self::log($str, 'i');
    }

    public static function log(string $str, string $type = 'i'): void
    {
        $colors = [
            'e' => 31, //error
            's' => 32, //success
            'w' => 33, //warning
            'i' => 39,  //info
        ];
        $color  = $colors[$type] ?? 0;
        echo "\033[".$color."m".$str."\033[0m".PHP_EOL;
    }

    /**
     * Plays a bell sound in console (if available)
     *
     * @param integer $count Bell play count
     *
     * @return void         Bell play string
     */
    public static function bell(int $count = 1): void
    {
        echo str_repeat("\007", $count);
    }

}