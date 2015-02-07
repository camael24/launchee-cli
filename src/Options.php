<?php
namespace Launchee;

use Hoa\Stream\IStream\In;

class Options
{
    protected $_json = [];
    protected $_required = ['vendor', 'name', 'xulrunner'];
    protected $_errors = [];

    public function __construct(In $json)
    {
        if (defined('INSTALL_DIR') === false) {
            define('INSTALL_DIR', $json->getDirname().DIRECTORY_SEPARATOR);
        }

        $json        = $json->readAll();
        $this->_json = json_decode($json, true);
    }

    public function get($key)
    {
        if (isset($this->_json[$key]) === true) {
            return $this->_json[$key];
        }

        return;
    }

    public function isValid()
    {
        $keys  = array_keys($this->_json);
        $valid = true;

        foreach ($this->_required as $key) {
            if (in_array($key, $keys) === false) {
                $valid           = false;
                $this->_errors[] = 'Keys '.$key.' not exists';
            }
        }

        return $valid;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function all()
    {
        return $this->_json;
    }
}
