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
        $this->populateMissingEnvironment();
        $configurator = new Configurator();
        $this->writeApacheConfig($configurator);
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

    private function populateMissingEnvironment()
    {
        if (!getenv('TITLE')) {
            $this->populateEnvironmentFromInput('TITLE', "Title: ");
        }
        if (!getenv('HOSTNAME')) {
            $this->populateEnvironmentFromInput('HOSTNAME', "Host: ");
        }
        if (!getenv('USERNAME')) {
            $this->populateEnvironmentFromInput('USERNAME', "User: ");
        }
        if (!getenv('PASSWORD')) {
            $this->populateEnvironmentFromInput('PASSWORD', "Password: ");
        }
    }

    private function populateEnvironmentFromInput($variableName, $prompt)
    {
        echo $prompt;
        $value = $this->factory->getFilesystem()->fgets(STDIN);
        putenv($variableName . '=' . $value);
    }

    private function writeApacheConfig(Configurator $configurator)
    {
        $this->factory->getFilesystem()->file_put_contents(
            getenv('HOME') . '/.siteinit/vhosts/' .
                getenv('HOSTNAME') . '.conf',
            $configurator->buildApacheConfig()
        );
    }
}