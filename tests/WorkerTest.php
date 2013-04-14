<?php
require_once('../bootstrap.php');

putenv('HOME=' . __DIR__);

class WorkerTest extends PHPUnit_Framework_TestCase {
    public function testMustRunAsRootSuccess()
    {
        $phpAPI = new zymurgy\PHPAPI\Repository(true);
        $phpAPI->getPOSIX()->mockSetReturn('posix_getuid', 0);
        $worker = new zymurgy\SiteInit\Worker($phpAPI);
        $worker->writeSite();
        $log = $phpAPI->getFilesystem()->mockGetLog();
        $this->assertTrue(isset($log['fopen']),
            "Files were opened when writing the site");
    }

    /**
     * @expectedException \zymurgy\PHPAPI\Exceptions\InvalidUser
     */
    public function testMustRunAsRootFailure()
    {
        $phpAPI = new zymurgy\PHPAPI\Repository(true);
        $phpAPI->getPOSIX()->mockSetReturn('posix_getuid', 100);
        $worker = new zymurgy\SiteInit\Worker($phpAPI);
        $worker->writeSite();
        $log = $phpAPI->getFilesystem()->mockGetLog();
        $this->assertTrue(isset($log['fopen']),
            "Files were opened when writing the site");
    }

    public function testAsksForMissingVariables()
    {
        $phpAPI = new zymurgy\PHPAPI\Repository(true);
        $phpAPI->getPOSIX()->mockSetReturn('posix_getuid', 0);
        $expectedTitle = "Test Title";
        $expectedHost = "host";
        $expectedUserName = "user";
        $expectedPassword = "password";
        $phpAPI->getFilesystem()->mockSetReturn(
            'fgets',
            new zymurgy\PHPAPI\MockReturnSet(array(
                $expectedTitle,
                $expectedHost,
                $expectedUserName,
                $expectedPassword,
            ))
        );
        //Delete environment variables
        putenv('TITLE');
        putenv('HOSTNAME');
        putenv('USERNAME');
        putenv('PASSWORD');
        $worker = new zymurgy\SiteInit\Worker($phpAPI);
        $worker->writeSite();
        $log = $phpAPI->getFilesystem()->mockGetLog();
        $this->assertTrue(isset($log['fopen']),
            "Files were opened when writing the site");
        $this->assertEquals($expectedTitle, getenv('TITLE'),
            "Asks for and sets title");
        $this->assertEquals($expectedHost, getenv('HOSTNAME'),
            "Asks for and sets host");
        $this->assertEquals($expectedUserName, getenv('USERNAME'),
            "Asks for and sets user name");
        $this->assertEquals($expectedPassword, getenv('PASSWORD'),
            "Asks for and sets password");
    }

    public function testWriteApacheConfig()
    {
        $phpAPI = new zymurgy\PHPAPI\Repository(true);
        $phpAPI->getPOSIX()->mockSetReturn('posix_getuid', 0);
        $worker = new zymurgy\SiteInit\Worker($phpAPI);
        $worker->writeSite();
        $fs = $phpAPI->getFilesystem()->mockGetFiles();
        $filename = __DIR__ . '/.siteinit/vhosts/host.conf';
        $this->assertTrue(isset($fs[$filename]),
            "Worker created apache virtual host config");
        //Not testing all contents since configurator tests test contents.
        $this->assertContains('ServerName host.local', $fs[$filename]->contents,
            "Apache config for server name");
    }

}
