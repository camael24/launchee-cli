<?php
namespace Camael24\Cli;

use Hoa\Core;
use Hoa\File;

class Downloader
{
    private $_progress = null;
    private $_source   = null;

    public function __construct($url)
    {
        $this->_source = new File\Read($url, File::MODE_READ, null, true);

        var_dump($this->getSourceSize());
    }

    public function setWatcher($progress)
    {
        $this->_progress = $progress;

        $this->_source->on('progress', function (\Hoa\Core\Event\Bucket $bucket) use ($progress) {

            $data = $bucket->getData(); /* check all the data */
            //var_dump($data['transferred']);

            return;
        });
    }

    public function getSourceSize()
    {
        $url = 'http://google.com/';
        $code = false;

        $options['http'] = array(
            'method' => "HEAD",
        );

        $context = stream_context_create($options);
        $body    = file_get_contents($url, null, $context);
        $request = new \Hoa\Http\Response\Response();

        $request->parse(implode("\r\n", $http_response_header));

        print_r($request['Content-Type']);
        

        
    }

    public function getWatcher()
    {
        return $this->_progress;
    }

    public function saveAs($destination)
    {
        $dest = new \Hoa\File\Write($destination);

        if ($this->_progress !== null) {
            $this->_progress->start();
        }

        $this->_source->open();

        $dest->writeAll($this->_source->readAll());

        if ($this->_progress !== null) {
            $this->_progress->end();
        }
    }
}
