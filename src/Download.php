<?php
namespace Launchee;

class Download
{
    public static function get($url, $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination);
        }

        $url_info = pathinfo($url);

        if (!file_exists($destination.'/'.$url_info['basename'])) {
            $file = new \Camael24\Cli\Downloader($url);

            $bar = new \Camael24\Cli\Progressbar($file, ['width' => 25]);

            $bar->run();
        }
    }
}
