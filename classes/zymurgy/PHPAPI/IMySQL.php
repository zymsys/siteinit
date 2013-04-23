<?php
namespace zymurgy\PHPAPI;

interface IMySQL
{
    function mysql_connect($server = null, $username = null, $password = null,
                           $new_link = false, $client_flags = 0);
    function mysql_query ($query, $link_identifier = null);
    function mysql_close ($link_identifier = null);
}