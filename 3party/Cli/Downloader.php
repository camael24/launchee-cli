<?php
namespace Camael24\Cli;

class Downloader extends \Hoa\File\Read
{
    private $_progress = null;
    public function watchProgress($progress)
    {
        $this->_progress = $progress;
        $this->_on->attach('progress',  function (Hoa\Core\Event\Bucket $bucket) use ($progress) {

            if ($progress !== null) {
                $progress->seek(5);
            }

            return;
        });
    }

    public function getWatcher() {
        return $this->_progress;
    }

    public function saveAs($destination)
    {
        //$dest = new \Hoa\File\Write($destination);

        if ($this->_progress !== null) {
            $this->_progress->start();
        }

        for($i = 0; $i <= 100; $i++)
            $this->_progress->seek($i);

        //$dest->writeAll($this->readAll());

        if ($this->_progress !== null) {
            $this->_progress->end();
        }
    }
}
