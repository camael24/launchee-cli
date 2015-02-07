<?php
namespace Launchee;

class Download
{
    private $_useCache = true;
    private $_item     = [];

    public function useCache($bool)
    {
        $this->_useCache = $bool;
    }

    public function get($url, $destination, $label = '')
    {
        $this->_item[] = [ $url, $destination, $label];
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

            if($value[2] !== '')
            {
                $file   = $value[2];
                $length =  $maxLabel - strlen($file);
            }
            else {
                $file = $url_info['basename'];
                $length =  $maxLabel - strlen($file);
            }


            if (file_exists($destination.'/'.$url_info['basename']) === false or $this->_useCache === false) {
                if (file_exists($destination.'/'.$url_info['basename']) === true) {
                    unlink($destination.'/'.$url_info['basename']);
                }

                if ($length < 0) {
                    $length = 0;
                }

                $progress->setLabel($file.str_repeat(' ', $length + 5));

                $file = new \Camael24\Cli\Downloader($url);
                $file->setWatcher($progress);
                $file->saveAs($destination.'/'.$url_info['basename']);
            } else {
                echo str_repeat(' ', $progress->getOption('span')).$file.' has in cache, use it instead of download'."\n";
            }
        }
    }
}
