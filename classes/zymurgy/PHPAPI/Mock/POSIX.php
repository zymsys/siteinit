<?php
namespace zymurgy\PHPAPI\Mock;

class POSIX extends Base implements \zymurgy\PHPAPI\IPOSIX
{

    public function posix_getuid()
    {
        $this->mockLog(__FUNCTION__, func_get_args());
        if ($this->hasMockReturn(__FUNCTION__)) {
            return $this->getMockReturn(__FUNCTION__, func_get_args());
        }
        return 100; //Sane predictable UID value for mock
    }
}