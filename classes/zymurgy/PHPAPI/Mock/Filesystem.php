<?php
namespace zymurgy\PHPAPI\Mock;

class Filesystem extends Base implements \zymurgy\PHPAPI\IFilesystem
{
    public function fopen($filename, $mode, $use_include_path = null,
                          $context = null)
    {
        $this->mockLog(__FUNCTION__, func_get_args());
        if ($this->hasMockReturn(__FUNCTION__)) {
            return $this->getMockReturn(__FUNCTION__, func_get_args());
        }
        return true;
    }

    function fgets($handle, $length = null)
    {
        $this->mockLog(__FUNCTION__, func_get_args());
        if ($this->hasMockReturn(__FUNCTION__)) {
            return $this->getMockReturn(__FUNCTION__, func_get_args());
        }
        return false;
    }
}