<?php
namespace zymurgy\PHPAPI\Production;

class POSIX implements \zymurgy\PHPAPI\IPOSIX
{
    public function posix_getuid()
    {
        return \posix_getuid();
    }
}