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
        $dest = new \Hoa\File\Write($destination);

        $progress = $this->_progress;
        $length = null;

        if ($progress !== null) {

            $progress->start();

            $this->_source->on('size', function (\Hoa\Core\Event\Bucket $bucket) use (&$length) {
                $length = $bucket->getData()['max'];
            });

            $this->_source->on('progress', function (\Hoa\Core\Event\Bucket $bucket) use (&$progress, &$length) {

                $data      = $bucket->getData(); /* check all the data */
                $stream    = $bucket->getSource()->getStreamMetaData();

                if ($stream['uri'] !== null) {
                    if ($length > 0) {
                        $percent = round((intval($data['transferred']) * 100) / $length);
                        $progress->seek($percent);
                    } else {

                        $bytes = function ($size, $precision = 2) { 

                            $base = log($size, 1024);
                            $suffixes = array('', 'k', 'M', 'G', 'T');   

                            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
                        };

                        $progress->step($bytes($data['transferred']));
                        $progress->infinite();
                    }
                }

            });
        }
        $this->_source->open();
        
        $dest->writeAll($this->_source->readAll());

        if ($progress !== null) {
            $progress->stop();
        }
    }
}
