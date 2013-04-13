<?php

putenv('HOSTNAME=host');
putenv('USERNAME=user');
putenv('PASSWORD=password');
putenv('TITLE=Test Title');
putenv('HOME=' . __DIR__);

require_once('../bootstrap.php');

class TestSiteInit extends PHPUnit_Framework_TestCase
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
}
