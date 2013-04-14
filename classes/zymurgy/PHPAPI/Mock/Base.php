<?php
namespace zymurgy\PHPAPI\Mock;

class Base
{
    private $_returns = array();
    private $_log = array();

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
        return $this->_returns[$method];
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
}