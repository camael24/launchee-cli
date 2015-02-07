<?php
namespace Launchee;

class Git extends Command
{
    private $_item = [];
    private $_directory = '';
    private $_progress = '';
    private $_output = '';

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
        $this->_output .= $data['line']."\n";

        $this->_progress->step($data['line']);
        $this->_progress->infinite();
    }

    public function repo($source, $directory = '', $tag ='')
    {
        $this->_item[] = [$source, $directory, $tag];
    }

    protected function _clone($source, $dir)
    {
        if(is_dir($this->_directory.'/'.$dir) === false)
           return $this->_run('git', ['clone', $source, $dir], $this->_directory);

        return null;
    }

    protected function _checkout($dir, $tag)
    {
        if(is_dir($this->_directory . $dir.'/.git') === true){
            //$this->_run('git', ['--git-dir='.$this->_directory . $dir.'/.git', 'stash'], $dir);

            // $checkout = $this->_run('git', ['--git-dir='.$this->_directory . $dir.'/.git', 'checkout', $tag], $dir);

            if($checkout !== 0){
                $this->_output .= 'Complementary information tag list : '."\n";
                //$this->_run('git', ['--git-dir='.$this->_directory . $dir.'/.git', 'tag', '-l'], $dir);
            }

            return $checkout;
        }

        return;
    }

    public function run()
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
            $tag    = $value[2];
            $file   = basename($source);
            $length = $maxLabel - strlen($file);

            if ($length < 0) {
                $length = 0;
            }


            $this->_progress->setLabel($file.str_repeat(' ', $length));
            
            if($this->_clone($source, $cwd) !== 0)
                throw new Exception("Clone of %s in %s \n Error: \n %s", 0, [$source, $cwd, $this->_output]);
        }
    }
}
