<?php
namespace Camael24\Cli;

class Downloader extends \Hoa\File\Read
{
    public function saveAs($destination)
    {
        $dest = new \Hoa\File\Write($destination);

        $dest->writeAll($this->readAll());
    }

    public function on($listenerId, $callable)
    {
        $this->_on->attach($listenerId, $callable);

        return $this;
    }
}
