<?php
namespace Launchee;

class Extract extends Command
{
    private $_item = [];
    private $_directory = '';
    private $_progress = '';
    private $_args = [];

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

    public function unzip($source, $directory, $label = '')
    {
        $this->_args[] = '-o';
        $this->_file('unzip', $source, $directory, $label);
    }

    public function bz2($source, $directory, $label = '')
    {
        $this->_args[] = 'jxf';
        $this->_file('tar', $source, $directory, $label);
    }

    protected function _file($exe, $source, $directory, $label = '')
    {
        $this->_item[$exe][] = [$source, $this->_directory.'/'.$directory, $label];
    }

    public function run()
    {
        $maxLabel = 0;

        foreach ($this->_item as $a) {
            foreach ($a as $value) {
                $source = '';
                if ($value[2] !== '') {
                    $source = $value[2];
                } else {
                    $source     = $value[0];
                }

                $length     = strlen(basename($source));

                if ($length > $maxLabel) {
                    $maxLabel = $length;
                }
            }
        }

        foreach ($this->_item as $type => $a) {
            foreach ($a as $value) {
                $source = $value[0];
                $cwd    = $value[1];

                if ($value[2] !== '') {
                    $file = $value[2];
                    $length =  $maxLabel - strlen($file);
                } else {
                    $file = basename($source);
                    $length =  $maxLabel - strlen($file);
                }

                if ($length < 0) {
                    $length = 0;
                }

                $this->_progress->setLabel($file.str_repeat(' ', $length));

                if (is_dir($cwd) === false) {
                    mkdir($cwd, 0777, true);
                }

                if (($cwd = realpath($cwd)) !== false) {
                    $this->_run($type, array_merge($this->_args, [$source]), $cwd);
                }
            }
        }
    }
}
