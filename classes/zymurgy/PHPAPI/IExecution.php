<?php
namespace zymurgy\PHPAPI;

interface IExecution
{
    function system($command, &$return_var = null);
}