<?php
require_once('../bootstrap.php');

putenv('HOME=' . __DIR__);

class WorkerTest extends PHPUnit_Framework_TestCase {
    protected function setUp()
    {
        $link = getenv('HOME') . '/.siteinit/vhosts';
        @unlink($link);
        @symlink('.', $link);
    }

    public function testWriteSite()
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

    public function testWriteEtcHosts()
    {
        $phpAPI = new zymurgy\PHPAPI\Repository(true);
        $phpAPI->getPOSIX()->mockSetReturn('posix_getuid', 0);
        $worker = new zymurgy\SiteInit\Worker($phpAPI);
        $worker->writeSite();
        $mfs = $phpAPI->getFilesystem();
        $log = $mfs->mockGetLog();
        $this->assertTrue(isset($log['fopen']),
            "Worker called fopen()");
        $etcHostsOpenParams = $mfs->mockSearchLogOne('fopen', 0, '/etc/hosts');
        $this->assertTrue(isset($etcHostsOpenParams[1]),
            "Worker sent 2nd parameter to fopen");
        $this->assertEquals('a', $etcHostsOpenParams[1],
            "Worker opened /etc/hosts for append");
        $fs = $mfs->mockGetFiles();
        $this->assertTrue(isset($fs['/etc/hosts']),
            "Worker wrote to /etc/hosts");
        $this->assertNotEmpty($fs['/etc/hosts']->contents);
    }

    public function testMySQLConfig()
    {
        $phpAPI = new zymurgy\PHPAPI\Repository(true);
        $worker = new zymurgy\SiteInit\Worker($phpAPI);
        $worker->setupDatabase(new zymurgy\SiteInit\Configurator());

        $log = $phpAPI->getMySQL()->mockGetLog();
        $this->assertTrue(isset($log['mysql_connect']),
            "Logged in with mysql_connect");

        $queries = array();
        foreach ($log['mysql_query'] as $query) {
            $queries[] = $query[0];
        }
        $this->assertContains(
            "create database user",
            $queries,
            "Created project database"
        );
        $this->assertContains(
            "grant all on user.* to 'user'@'localhost' identified by 'password'",
            $queries,
            "Granted permissions on project database"
        );
    }

    public function testVhostsSymlinkSanityCheck_Missing()
    {
        $link = getenv('HOME') . '/.siteinit/vhosts';
        @unlink($link);
        $phpAPI = new \zymurgy\PHPAPI\Repository(true);
        $phpAPI->getPOSIX()->mockSetReturn('posix_getuid', 0);
        $worker = new \zymurgy\SiteInit\Worker($phpAPI);
        $configurator = new \zymurgy\SiteInit\Configurator();
        $thrown = false;
        try {
            $worker->writeSite($configurator);
        } catch (Exception $e) {
            $thrown = true;
        }
        $this->assertTrue(
            $thrown,
            "writeSite threw an error for the missing vhost link"
        );
    }

    public function testVhostsSymlinkSanityCheck_Wrong()
    {
        $link = getenv('HOME') . '/.siteinit/vhosts';
        @unlink($link);
        file_put_contents($link, "Not a symlink!");
        $phpAPI = new \zymurgy\PHPAPI\Repository(true);
        $phpAPI->getPOSIX()->mockSetReturn('posix_getuid', 0);
        $worker = new \zymurgy\SiteInit\Worker($phpAPI);
        $configurator = new \zymurgy\SiteInit\Configurator();
        $thrown = false;
        try {
            $worker->writeSite($configurator);
        } catch (Exception $e) {
            $thrown = true;
        }
        $this->assertTrue(
            $thrown,
            "writeSite threw an error for the wrong vhost file type"
        );
    }
}
