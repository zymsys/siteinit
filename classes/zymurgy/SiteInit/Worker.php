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
        $this->setupDatabase($configurator);
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
        if (!getenv(SiteInit::ENV_TITLE)) {
            $this->populateEnvironmentFromInput(SiteInit::ENV_TITLE, "Title: ");
        }
        if (!getenv(SiteInit::ENV_HOSTNAME)) {
            $this->populateEnvironmentFromInput(SiteInit::ENV_HOSTNAME, "Host: ");
        }
        if (!getenv(SiteInit::ENV_USERNAME)) {
            $this->populateEnvironmentFromInput(SiteInit::ENV_USERNAME, "User: ");
        }
        if (!getenv(SiteInit::ENV_PASSWORD)) {
            $this->populateEnvironmentFromInput(SiteInit::ENV_PASSWORD, "Password: ");
        }
    }

    private function populateEnvironmentFromInput($variableName, $prompt)
    {
        $this->factory->getString()->echoWrapper($prompt);
        $value = $this->factory->getFilesystem()->fgets(STDIN);
        putenv($variableName . '=' . trim($value));
    }

    private function writeApacheConfig(Configurator $configurator)
    {
        if (!is_link(getenv('HOME') . '/.siteinit/vhosts')) {
            throw new \Exception(
                "No .siteinit/vhosts symlink to the vhosts folder"
            );
        }
        $this->factory->getFilesystem()->file_put_contents(
            getenv('HOME') . '/.siteinit/vhosts/' .
                getenv(SiteInit::ENV_HOSTNAME) . '.conf',
            $configurator->buildApacheConfig()
        );
    }

    public function setupDatabase(Configurator $configurator)
    {
        $config = \zymurgy\SiteInit\Config::getConfig();
        $connection = $this->factory->getMySQL()->mysql_connect(
            $config->mysql->host,
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

    private function deploySkeletonPath($path, Configurator $configurator)
    {
        $handle = opendir($path);
        $commonLength = strlen(getenv('HOME') . '/.siteinit/skeleton');
        while (($entry = readdir($handle)) !== false) {
            if (($entry === '.') || ($entry === '..')) {
                continue;
            }
            $filename = $path . '/' . $entry;
            if (is_dir($filename)) {
                $this->deploySkeletonPath($filename, $configurator);
            } else if (is_file($filename)) {
                $destination = getenv('HOME') . '/.siteinit/Sites/' .
                    getenv(SiteInit::ENV_HOSTNAME) .
                    substr($filename, $commonLength);
                $configurator->copyAndFillTemplateValues($filename, $destination);
            }
        }
    }

    public function deploySkeleton(Configurator $configurator)
    {
        $this->deploySkeletonPath(
            getenv('HOME') . '/.siteinit/skeleton',
            $configurator
        );
    }
}