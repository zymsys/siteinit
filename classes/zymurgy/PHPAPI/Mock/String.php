<?php
namespace zymurgy\PHPAPI\Mock;

class String extends Base implements \zymurgy\PHPAPI\IString
{
    public function echoWrapper()
    {
        $this->mockLog(__FUNCTION__, func_get_args());
    }
}
