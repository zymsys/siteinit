<?php

putenv(\zymurgy\SiteInit\SiteInit::ENV_HOSTNAME . '=host');
putenv(\zymurgy\SiteInit\SiteInit::ENV_USERNAME . '=user');
putenv(\zymurgy\SiteInit\SiteInit::ENV_PASSWORD . '=password');
putenv(\zymurgy\SiteInit\SiteInit::ENV_TITLE    . '=Test Title');
putenv('HOME=' . __DIR__);

require_once('../bootstrap.php');

class ConfiguratorTest extends PHPUnit_Framework_TestCase
{
    public function testBuildApacheConfig()
    {
        $configurator = new zymurgy\SiteInit\Configurator();
        $configuration = $configurator->buildApacheConfig();
        $this->assertContains('ServerName host.local', $configuration,
            "Apache config for server name");
        $this->assertContains('SetEnv SITE_TITLE "Test Title"', $configuration,
            "Apache config for site title");
        $this->assertContains('SetEnv DB_USER "user"', $configuration,
            "Apache config for the database name");
        $this->assertContains('SetEnv DB_PASSWORD "password"', $configuration,
            "Apache config for the password");
    }

    public function testMySQLConfig()
    {
        $configurator = new zymurgy\SiteInit\Configurator();
        $sql = $configurator->buildSetupSQL();
        $this->assertTrue(is_array($sql),
            "Setup SQL is returned as an array");
        $this->assertContains("create database user", $sql,
            "SQL creates database");
        $this->assertContains("grant all on user.* to 'user'@'localhost' " .
            "identified by 'password'", $sql,
            "SQL grants rights");
    }

    public function testHosts()
    {
        $configurator = new zymurgy\SiteInit\Configurator();
        $hosts = $configurator->buildHosts();
        $this->assertContains("127.0.0.1       host.local", $hosts,
            "Hosts file contains IPV4 entry");
        $this->assertContains("fe80::1%lo0     host.local", $hosts,
            "Hosts file contains IPV6 entry");
    }
}
