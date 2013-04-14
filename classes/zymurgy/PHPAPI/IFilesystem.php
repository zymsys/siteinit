<?php
namespace zymurgy\PHPAPI;

interface IFilesystem
{
    function fopen($filename, $mode, $use_include_path = null, $context = null);
    function fgets($handle, $length = null);
    function file_put_contents($filename, $data, $flags = null, $context = null);
    function fwrite($handle, $string, $length = null);
    function fclose($handle);
}