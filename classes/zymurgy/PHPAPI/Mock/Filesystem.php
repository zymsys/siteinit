<?php
namespace zymurgy\PHPAPI\Mock;

class Filesystem extends Base implements \zymurgy\PHPAPI\IFilesystem
{
    public function fopen($filename, $mode, $use_include_path = null,
                          $context = null)
    {
        $this->mockLog(__FUNCTION__, func_get_args());
        $handle = $this->mockGetFile($filename, $mode);
        if ($this->hasMockReturn(__FUNCTION__)) {
            return $this->getMockReturn(__FUNCTION__, func_get_args());
        }
        return $handle;
    }

    function fgets($handle, $length = null)
    {
        $this->mockLog(__FUNCTION__, func_get_args());
        if ($this->hasMockReturn(__FUNCTION__)) {
            return $this->getMockReturn(__FUNCTION__, func_get_args());
        }
        return false;
    }

    function file_put_contents($filename, $data, $flags = null, $context = null)
    {
        $this->mockLog(__FUNCTION__, func_get_args());
        $fd = $this->fopen($filename, 'w');
        $this->fwrite($fd, $data);
        $this->fclose($fd);
        if ($this->hasMockReturn(__FUNCTION__)) {
            return $this->getMockReturn(__FUNCTION__, func_get_args());
        }
        return strlen($data);
    }

    /**
     * @param $handle \zymurgy\PHPAPI\MockFileHandle
     * @param $string
     * @param null $length
     * @return int|mixed
     */
    public function fwrite($handle, $string, $length = null)
    {
        $this->mockLog(__FUNCTION__, func_get_args());
        $handle->write($string);
        if ($this->hasMockReturn(__FUNCTION__)) {
            return $this->getMockReturn(__FUNCTION__, func_get_args());
        }
        return strlen($string);
    }

    /**
     * @param $handle \zymurgy\PHPAPI\MockFileHandle
     * @return bool|mixed
     */
    public function fclose($handle)
    {
        $this->mockLog(__FUNCTION__, func_get_args());
        $handle->mode = null;
        if ($this->hasMockReturn(__FUNCTION__)) {
            return $this->getMockReturn(__FUNCTION__, func_get_args());
        }
        return true;
    }
}