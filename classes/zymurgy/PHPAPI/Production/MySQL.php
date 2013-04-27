<?php
namespace zymurgy\PHPAPI\Production;

class MySQL implements \zymurgy\PHPAPI\IMySQL
{
    function mysql_connect($server = null, $username = null, $password = null,
                           $new_link = false, $client_flags = 0)
    {
        return call_user_func_array(__FUNCTION__, func_get_args());
    }

    function mysql_query($query, $link_identifier = null)
    {
        return call_user_func_array(__FUNCTION__, func_get_args());
    }

    function mysql_close($link_identifier = null)
    {
        return call_user_func_array(__FUNCTION__, func_get_args());
    }
}
