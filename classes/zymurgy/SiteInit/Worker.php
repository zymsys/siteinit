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
        $this->writeEtcHosts($configurator);
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
        $this->factory->getString()->echoWrapper($prompt);
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

    public function setupDatabase(Configurator $configurator)
    {
        $config = \zymurgy\SiteInit\Config::getConfig();
        $connection = $this->factory->getMySQL()->mysql_connect(
            $config->mysql->userName,
            $config->mysql->password
        );
        $script = $configurator->buildSetupSQL();
        foreach ($script as $sql) {
            $this->factory->getMySQL()->mysql_query($sql, $connection);
        }
        $this->factory->getMySQL()->mysql_close($connection);
    }

    private function writeEtcHosts(Configurator $configurator)
    {
        $fs = $this->factory->getFilesystem();
        $fd = $fs->fopen('/etc/hosts','a');
        $fs->fwrite($fd, $configurator->buildHosts());
        $fs->fclose($fd);
    }
}