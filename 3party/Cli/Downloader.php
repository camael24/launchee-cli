<?php
namespace Camael24\Cli;

use Hoa\File;

class Downloader
{
    private $_progress = null;
    private $_source   = null;

    public function __construct($url)
    {
        $this->_source = new File\Read($url, File::MODE_READ, null, true);
    }

    public function setWatcher(Helper\IProgressbar $progress)
    {
        $this->_progress = $progress;
    }

    public function getWatcher()
    {
        return $this->_progress;
    }

    public function saveAs($destination)
    {
        //$dest = new \Hoa\File\Write($destination);

        if ($this->_progress !== null) {
            $this->_progress->start();
        }

        $this->_source->open();

        // $dest->writeAll($this->_source->readAll());

        for ($i = 0; $i <= 100; $i++) {
            $this->_progress->seek($i);
        }

        if ($this->_progress !== null) {
            $this->_progress->stop();
        }
    }
}
