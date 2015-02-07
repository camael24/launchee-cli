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
        $processus = new \Hoa\Console\Processus($process, $argument, null, $cwd);

        $processus->on('start', [$this, 'start']);
        $processus->on('output', [$this, 'output']);
        $processus->on('stop', [$this, 'stop']);

        $processus->run();

        return $processus->getExitCode();
    }
}
