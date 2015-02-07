<?php
namespace Launchee;

class Extract extends Command
{
    private $_item = [];
    private $_directory = '';
    private $_progress = '';

    public function __construct($directory)
    {
        $this->_directory = $directory;
        $this->_progress = new \Camael24\Cli\Helper\Progressbar();
    }

    public function start($bucket)
    {
        $this->_progress->start();
    }

    public function stop($bucket)
    {
        $this->_progress->stop();
    }

    public function output($bucket)
    {
        $data = $bucket->getData();

        $this->_progress->step($data['line']);
        $this->_progress->infinite();
    }

    public function file($source, $directory)
    {
        $this->_item[] = [$source, $this->_directory.'/'.$directory];
    }

    public function unzip()
    {
        $maxLabel = 0;

        foreach ($this->_item as $value) {
            $source     = $value[0];
            $length     = strlen(basename($source));

            if ($length > $maxLabel) {
                $maxLabel = $length;
            }
        }

        foreach ($this->_item as $value) {
            $source = $value[0];
            $cwd    = $value[1];

            $file = basename($source);

            $length =  $maxLabel - strlen($file);

            if ($length < 0) {
                $length = 0;
            }

            $this->_progress->setLabel($file.str_repeat(' ', $length));

            if (is_dir($cwd) === false) {
                mkdir($cwd, 0777, true);
            }

            if (($cwd = realpath($cwd)) !== false) {
                $this->run('unzip', ['-o', $source], $cwd);
            }
        }
    }
}
