<?php
namespace zymurgy\PHPAPI\Production;

class POSIX implements \zymurgy\PHPAPI\IPOSIX
{
    public function posix_getuid()
    {
        return call_user_func_array(__FUNCTION__, func_get_args());
    }
}