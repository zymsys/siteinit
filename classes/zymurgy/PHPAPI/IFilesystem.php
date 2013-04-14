<?php
namespace zymurgy\PHPAPI;

interface IFilesystem
{
    function fopen($filename, $mode, $use_include_path = null, $context = null);
}