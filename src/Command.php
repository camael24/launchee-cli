<?php
namespace Launchee;

class Command
{
    public function start($bucket)
    {
    }

    public function stop($bucket)
    {
    }

    public function output($bucket)
    {
        $data = $bucket->getData();
        echo $data['line']."\n";
    }

    public function run($process, $argument, $cwd = null)
    {
        return $this->_run($process, $argument, $cwd);
    }

    protected function _run($process, $argument, $cwd = null)
    {
        $path = \Hoa\Console\Processus::locate($process);

        if ($path === null) {

            if(is_file(realpath(INSTALL_DIR.'bin/'.$process)))
                $path = realpath(INSTALL_DIR.'bin/'.$process);
            else 
                throw new Exception("%s are not find in PATH and in %s", 0, [$process, INSTALL_DIR.'bin/']);
        }

        echo $path.' '.implode(' ', $argument)."\n";

        $processus = new \Hoa\Console\Processus($path, $argument, null, $cwd);

        $processus->on('start', [$this, 'start']);
        $processus->on('output', [$this, 'output']);
        $processus->on('stop', [$this, 'stop']);

        $processus->run();

        return $processus->getExitCode();
    }
}
