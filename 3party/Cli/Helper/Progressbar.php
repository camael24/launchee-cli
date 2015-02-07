<?php
namespace Camael24\Cli\Helper;

class Progressbar implements IProgressbar
{
    private $_stream  = null;
    private $_info    = array();
    private $_isAnimable = true;
    private $_label   = '';
    private $_options = [
        'bracket_'      => '[',
        '_bracket'      => ']',
        'fill'          => '=',
        'fill_finished' => '-',
        'cursor'        => '>',
        'cursor_color'  => 'fg(yellow) bg(black)',
        'fill_color'    => 'fg(white) bg(blacl)',
        'width'         => 10,
        'span'          => 5,
    ];

    public function __construct($options = array())
    {
        $this->setOptions($options);

        if(OS_WIN === true)
            $this->_isAnimable = false;
    }

    public function setStream($stream)
    {
        $this->_stream = $stream;
    }

    public function setLabel($label)
    {
        $this->_name   = $label;
    }
    public function setOptions($options = [])
    {
        $this->_options = array_merge($this->_options, $options);
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function getOption($key)
    {
        if (isset($this->_options[$key])) {
            return $this->_options[$key];
        }

        return;
    }

    public function seek($percent)
    {
        if($this->_isAnimable === true)
            $this->_animated($percent);
        else
            $this->_static($percent);
    }

    protected function goToBeginLine(){
        echo "\x0D";
    }

    protected function finish() {
        $this->goToBeginLine();
        
        $span        = $this->getOption('span');
        $baseline    = str_repeat(' ', $span);
        $baseline   .= $this->_name.' ';
        $baseline   .= $this->getOption('bracket_');
        $baseline   .= str_repeat($this->getOption('fill_finished'), $this->getOption('width'));
        $baseline   .= $this->getOption('_bracket').'   ';
        $baseline   .= '100 %';

        echo $baseline;
    }

    protected function _static($percent) {
        
        $this->goToBeginLine();
        $p = '';
        if ($percent < 10) {
            $p .= '0';
        }
        if ($percent < 100) {
            $p .= '0';
        }

        $current     = round(($percent * $this->getOption('width')) / 100);
        $rest        = $this->getOption('width') - $current - 1;
        $p          .= $percent;
        $span        = $this->getOption('span');
        $baseline    = str_repeat(' ', $span);
        $baseline   .= $this->_name.' ';
        $baseline   .= $this->getOption('bracket_');
        $baseline   .= str_repeat($this->getOption('fill'), $current);
        $baseline   .= '>';
        
        if($rest > 0)
            $baseline   .= str_repeat($this->getOption('fill'), $rest);

        $baseline   .= $this->getOption('_bracket').'   ';
        $baseline   .= $p;

        echo $baseline;

    }

    protected function _animated($percent) {
        $current = round(($percent * $this->getOption('width')) / 100);
        $current = $this->_info['progress']['start'] + $current;

        if ($current > $this->_info['progress']['end']) {
            $current = $this->_info['progress']['end'];
        }

        \Hoa\Console\Cursor::moveTo($current);

        echo $this->getOption('cursor');

        \Hoa\Console\Cursor::moveTo($this->_info['label']['start']);

        if ($percent < 10) {
            echo '0';
        }
        if ($percent < 100) {
            echo '0';
        }

        echo $percent;
    }

    public function start()
    {
        $span                             = $this->getOption('span');
        $baseline                         = str_repeat(' ', $span);
        $baseline                        .= $this->_name.' ';
        $baseline                        .= $this->getOption('bracket_');
        $this->_info['progress']['start'] = strlen($baseline) +1;
        $baseline                        .= str_repeat($this->getOption('fill'), $this->getOption('width'));
        $this->_info['progress']['end']   = strlen($baseline);
        $baseline                        .= $this->getOption('_bracket').'   ';
        $this->_info['label']['start']    = strlen($baseline)+1;
        $baseline                        .= '100';
        $this->_info['label']['end']      = strlen($baseline);
        $baseline                        .= ' %';

        echo $baseline;

        if($this->_isAnimable === true)
            \Hoa\Console\Cursor::save();
    }

    public function stop()
    {


        if($this->_isAnimable === true)
        {
            \Hoa\Console\Cursor::moveTo($this->_info['progress']['start']);
            echo str_repeat($this->getOption('fill_finished'), $this->getOption('width'));

            \Hoa\Console\Cursor::restore();
        }
        else {
            $this->finish();
        }
        echo "\n";
    }
}
