<?php
namespace zymurgy\PHPAPI\Mock;

class Base
{
    private $_returns = array();
    private $_log = array();
    private $_files = array();

    public function mockSetReturn($functionName, $value)
    {
        $this->_returns[$functionName] = $value;
    }

    protected function hasMockReturn($method)
    {
        return isset($this->_returns[$method]);
    }

    protected function getMockReturn($method, $arguments)
    {
        $value = $this->_returns[$method];
        if ($value instanceof \zymurgy\PHPAPI\MockReturnSet) {
            return $value->get($arguments);
        }
        return $value;
    }

    public function mockGetLog()
    {
        return $this->_log;
    }

    protected function mockLog($method, $args)
    {
        if (!isset($this->_log[$method])) {
            $this->_log[$method] = array();
        }
        $this->_log[$method][] = $args;
    }

    public function mockGetFiles()
    {
        return $this->_files;
    }

    protected function mockGetFile($filename, $mode)
    {
        if (!isset($this->_files[$filename])) {
            $this->_files[$filename] = new \zymurgy\PHPAPI\MockFileHandle($mode);
        }
        return $this->_files[$filename];
    }
}