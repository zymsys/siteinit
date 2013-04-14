<?php
require_once('../bootstrap.php');

class WorkerTest extends PHPUnit_Framework_TestCase {
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
}
