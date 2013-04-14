<?php
namespace zymurgy\PHPAPI;

class Repository
{
    private $_mock;
    private $_POSIX;
    private $_Filesystem;

    function __construct($mock = false)
    {
        $this->_mock = $mock;
    }

    public function getPOSIX()
    {
        if (!$this->_POSIX) {
            $this->_POSIX = $this->_mock ?
                new Mock\POSIX() : new Production\POSIX();
        }
        return $this->_POSIX;
    }

    public function getFilesystem()
    {
        if (!$this->_Filesystem) {
            $this->_Filesystem = $this->_mock ?
                new Mock\Filesystem() : new Production\Filesystem();
        }
        return $this->_Filesystem;
    }
}