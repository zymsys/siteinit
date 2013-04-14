<?php
namespace zymurgy\PHPAPI;

class MockReturnSet
{
    private $_results;

    function __construct($results)
    {
        if (!is_array($results)) {
            throw new \InvalidArgumentException(
                "MockReturnSet must be constructed with an array of " .
                "return values."
            );
        }
        $this->_results = $results;
    }

    public function get($arguments)
    {
        if (0 === count($this->_results)) {
            throw new \ErrorException("No more results available");
        }
        return array_shift($this->_results);
    }
}