<?php
namespace zymurgy\SiteInit;

class Configurator
{
    private $_substitutions;

    public function __construct()
    {
        $this->_substitutions = array(
            '{{host}}'     => getenv('HOSTNAME'),
            '{{title}}'    => getenv('TITLE'),
            '{{user}}'     => getenv('USERNAME'),
            '{{password}}' => getenv('PASSWORD'),
        );
    }

    /**
     * Takes the text of a template, and makes the substitutions listed
     * in the private $_substitutions variable.  Returns the filled in
     * template.
     *
     * @param $text
     * @return string
     */
    protected function fillTemplate($text)
    {
        return str_replace(
            array_keys($this->_substitutions),
            array_values($this->_substitutions),
            $text
        );
    }

    /**
     * Reads the template apache config; fills it in, and returns the
     * completed config text.
     *
     * @return string
     */
    public function buildApacheConfig()
    {
        return $this->fillTemplate(
            file_get_contents(getenv('HOME') . '/.siteinit/apache.conf')
        );
    }

    public function buildSetupSQL()
    {
        $config = Config::getConfig();
        $script = $config->mysql->initScript;
        $filled = array();
        foreach ($script as $sql) {
            $filled[] = $this->fillTemplate($sql);
        }
        return $filled;
    }

    private function deploySkeletonPath($path)
    {
        $handle = opendir($path);
        $commonLength = strlen(getenv('HOME') . '/.siteinit/skeleton');
        while (($entry = readdir($handle)) !== false) {
            if (($entry === '.') || ($entry === '..')) {
                continue;
            }
            $filename = $path . '/' . $entry;
            if (is_dir($filename)) {
                $this->deploySkeletonPath($filename);
            } else if (is_file($filename)) {
                $destination = getenv('HOME') . '/.siteinit/Sites/' .
                    getenv('HOSTNAME') . substr($filename, $commonLength);
                $this->copyAndFillTemplateValues($filename, $destination);
            }
        }
    }

    public function deploySkeleton()
    {
        $this->deploySkeletonPath(getenv('HOME') . '/.siteinit/skeleton');
    }

    private function copyAndFillTemplateValues($filename, $destination)
    {
        $destinationFolder = dirname($destination);
        if (!file_exists($destinationFolder)) {
            mkdir($destinationFolder, 0755, true);
        }
        $contents = file_get_contents($filename);
        $meta = stat($filename);
        file_put_contents($destination, $this->fillTemplate($contents));
        chmod($destination, $meta['mode']);
    }

    public function buildHosts()
    {
        $template = file_get_contents(getenv('HOME') . '/.siteinit/hosts');
        return $this->fillTemplate($template);
    }
}