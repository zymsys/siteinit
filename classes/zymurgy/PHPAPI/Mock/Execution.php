<?php
namespace zymurgy\PHPAPI\Mock;

class Execution extends Base implements \zymurgy\PHPAPI\IExecution
{
    function system($command, &$return_var = null)
    {
        $this->mockLog(__FUNCTION__, func_get_args());
        if ($this->hasMockReturn(__FUNCTION__)) {
            return $this->getMockReturn(__FUNCTION__, func_get_args());
        }
        return '';
    }
}