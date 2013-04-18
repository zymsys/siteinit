<?php
namespace zymurgy\SiteInit;

class Config
{
    private static $_config;

    public static function getConfig()
    {
        if (!isset(self::$_config)) {
            self::$_config = new \stdClass();
            $filename = getenv('HOME') . '/.siteinit/config.json';
            if (file_exists($filename)) {
                self::$_config = json_decode(file_get_contents($filename));
            }
        }
        return self::$_config;
    }
}
