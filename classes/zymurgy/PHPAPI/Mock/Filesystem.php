<?php
namespace zymurgy\PHPAPI\Mock;

class Filesystem extends Base
{
    public function fopen ($filename, $mode, $use_include_path = null, $context = null)
    {
        $this->mockLog(__FUNCTION__, func_get_args());
    }
}