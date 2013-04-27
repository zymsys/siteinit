<?php
namespace zymurgy\PHPAPI\Production;

class Filesystem implements \zymurgy\PHPAPI\IFilesystem
{
    public function fopen($filename, $mode, $use_include_path = null,
                          $context = null)
    {
        return call_user_func_array(__FUNCTION__, func_get_args());
    }

    function fgets($handle, $length = null)
    {
        return call_user_func_array(__FUNCTION__, func_get_args());
    }

    function file_put_contents($filename, $data, $flags = null, $context = null)
    {
        return call_user_func_array(__FUNCTION__, func_get_args());
    }

    function fwrite($handle, $string, $length = null)
    {
        return call_user_func_array(__FUNCTION__, func_get_args());
    }

    function fclose($handle)
    {
        return call_user_func_array(__FUNCTION__, func_get_args());
    }
}