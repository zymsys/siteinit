<?php
namespace zymurgy\PHPAPI\Production;

class String implements \zymurgy\PHPAPI\IString
{
    public function echoWrapper()
    {
        echo implode(func_get_args());
    }
}
