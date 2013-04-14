<?php
namespace zymurgy\PHPAPI;

class MockFileHandle
{
    public $mode;
    public $contents;

    function __construct($mode, $contents = '')
    {
        $this->mode = $mode;
        $this->contents = $contents;
    }

    public function write($string)
    {
        $this->contents .= $string;
    }
}