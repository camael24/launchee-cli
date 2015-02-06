<?php
namespace Launchee;

class Download
{
    private $_useCache = false;

    public function useCache($bool)
    {
        $this->_useCache = $bool;
    }

    public function get($url, $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination);
        }


        $url_info = pathinfo($url);
        $progress = new \Camael24\Cli\Progressbar(['span' => 15]);

        if (file_exists($destination.'/'.$url_info['basename']) === false or $this->_useCache === false) {
            if (file_exists($destination.'/'.$url_info['basename']) === true) {
                unlink($destination.'/'.$url_info['basename']);
            }

            $progress->setLabel($url_info['basename']);

            $file = new \Camael24\Cli\Downloader($url);
            $file->watchProgress($progress);
            $file->saveAs($destination.'/'.$url_info['basename']);
        }
        else {

            echo str_repeat(' ', $progress->getOption('span')).$url_info['basename'].' has in cache, use it'."\n";

        }


    }
}
