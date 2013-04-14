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
        return \fgets($handle, $length);
    }
}