<?php
namespace zymurgy\PHPAPI\Production;

class MySQL implements \zymurgy\PHPAPI\IMySQL
{
    function mysql_connect($server = null, $username = null, $password = null,
                           $new_link = false, $client_flags = 0)
    {
        if (!$server) $server = ini_get("mysql.default_host");
        if (!$username) $username = ini_get("mysql.default_user");
        if (!$password) $password = ini_get("mysql.default_password");
        return mysql_connect(
            $server,
            $username,
            $password,
            $new_link,
            $client_flags
        );
    }

    function mysql_query($query, $link_identifier = null)
    {
        return mysql_query($query, $link_identifier);
    }

    function mysql_close($link_identifier = null)
    {
        return mysql_close($link_identifier);
    }
}
