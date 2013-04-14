<?php
namespace zymurgy\SiteInit;

class Worker
{
    protected $factory;

    public function __construct(\zymurgy\PHPAPI\Repository $factory = null)
    {
        if (!$factory) {
            $factory = new \zymurgy\PHPAPI\Repository();
        }
        $this->factory = $factory;
    }

    public function writeSite()
    {
        $this->checkIsRoot();
        $this->factory->getFilesystem()->fopen('/etc/hosts','a');
    }

    private function checkIsRoot()
    {
        if (0 !== $this->factory->getPOSIX()->posix_getuid()) {
            throw new \zymurgy\PHPAPI\Exceptions\InvalidUser(
                "siteinit must be run as root."
            );
        }
    }
}