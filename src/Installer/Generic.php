<?php
namespace Launchee\Installer;

class Generic implements Installable
{
    protected $_options = [];

    public function __construct(Array $options = [])
    {
        $this->_options = array_merge($this->_options, $options);
    }

    public function get($key)
    {
        if (isset($this->_options[$key]) === true) {
            return $this->_options[$key];
        }

        return;
    }
}
