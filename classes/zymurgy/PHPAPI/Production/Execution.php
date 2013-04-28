<?php
namespace zymurgy\PHPAPI\Production;

class Execution implements \zymurgy\PHPAPI\IExecution
{
    function system($command, &$return_var = null)
    {
        return call_user_func_array(__FUNCTION__, func_get_args());
    }
}