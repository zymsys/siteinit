<?php
namespace zymurgy\PHPAPI\Production;

class Filesystem implements \zymurgy\PHPAPI\IFilesystem
{
    public function fopen($filename, $mode, $use_include_path = null,
                          $context = null)
    {
        return \fopen($filename, $mode, $use_include_path, $context);
    }

    function fgets($handle, $length = null)
    {
        return call_user_func_array(__FUNCTION__, func_get_args());
    }

    function file_put_contents($filename, $data, $flags = null, $context = null)
    {
        return \file_put_contents($filename, $data, $flags, $context);
    }

    function fwrite($handle, $string, $length = null)
    {
        return \fwrite($handle, $string, $length);
    }

    function fclose($handle)
    {
        return \fclose($handle);
    }
}