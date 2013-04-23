<?php
namespace zymurgy\PHPAPI\Mock;

class MySQL extends Base implements \zymurgy\PHPAPI\IMySQL
{
    function mysql_connect($server = null, $username = null, $password = null,
                           $new_link = false, $client_flags = 0)
    {
        $this->mockLog(__FUNCTION__, func_get_args());
        if ($this->hasMockReturn(__FUNCTION__)) {
            return $this->getMockReturn(__FUNCTION__, func_get_args());
        }
        return true;
    }

    function mysql_query($query, $link_identifier = null)
    {
        $this->mockLog(__FUNCTION__, func_get_args());
        if ($this->hasMockReturn(__FUNCTION__)) {
            return $this->getMockReturn(__FUNCTION__, func_get_args());
        }
        return true;
    }

    function mysql_close($link_identifier = null)
    {
        $this->mockLog(__FUNCTION__, func_get_args());
        if ($this->hasMockReturn(__FUNCTION__)) {
            return $this->getMockReturn(__FUNCTION__, func_get_args());
        }
        return true;
    }
}
