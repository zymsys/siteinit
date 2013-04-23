<?php
namespace zymurgy\PHPAPI;

class Repository
{
    private $_mock;
    private $_Filesystem;
    private $_MySQL;
    private $_POSIX;
    private $_String;

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

    public function getString()
    {
        if (!$this->_String) {
            $this->_String = $this->_mock ?
                new Mock\String() : new Production\String();
        }
        return $this->_String;
    }

    public function getMySQL()
    {
        if (!$this->_MySQL) {
            $this->_MySQL = $this->_mock ?
                new Mock\MySQL() : new Production\MySQL();
        }
        return $this->_MySQL;
    }
}