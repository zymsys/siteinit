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
        $sql = $this->fillTemplate(
            file_get_contents(getenv('HOME') . '/.siteinit/mysql.sql')
        );
        $lines = explode(";", $sql);
        $stripped = array();
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $stripped[] = $line;
            }
        }
        return $stripped;
    }
}