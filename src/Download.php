<?php
namespace Launchee;

class Download
{
    private $_useCache = false;
    private $_item     = [];

    public function useCache($bool)
    {
        $this->_useCache = $bool;
    }

    public function get($url, $destination)
    {
        $this->_item[] = [ $url, $destination ];
    }

    public function run()
    {
        $maxLabel = 0;

        foreach ($this->_item as $value) {
            $url        = $value[0];
            $url_info   = pathinfo($url);
            $length     = strlen($url_info['basename']);

            if ($length > $maxLabel) {
                $maxLabel = $length;
            }
        }

        foreach ($this->_item as $value) {
            $url         = $value[0];
            $destination = $value[1];

            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }

            $url_info = pathinfo($url);
            $progress = new \Camael24\Cli\Helper\Progressbar([], false);

            if (file_exists($destination.'/'.$url_info['basename']) === false or $this->_useCache === false) {
                if (file_exists($destination.'/'.$url_info['basename']) === true) {
                    unlink($destination.'/'.$url_info['basename']);
                }
                $length =  $maxLabel - strlen($url_info['basename']);

                if ($length < 0) {
                    $length = 0;
                }

                $progress->setLabel($url_info['basename'].str_repeat(' ', $length + 5));

                $file = new \Camael24\Cli\Downloader($url);
                $file->setWatcher($progress);
                $file->saveAs($destination.'/'.$url_info['basename']);
            } else {
                echo str_repeat(' ', $progress->getOption('span')).$url_info['basename'].' has in cache, use it instead of download'."\n";
            }
        }
    }
}
