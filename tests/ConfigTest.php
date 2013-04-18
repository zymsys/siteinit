<?php
require_once('../bootstrap.php');

class ConfigTest extends PHPUnit_Framework_TestCase {

    public function testReadMySQLConfig()
    {
        $config = zymurgy\SiteInit\Config::getConfig();
        $this->assertObjectHasAttribute('mysql', $config,
            "Config has a mysql section");
    }
}
