<?php
namespace Camael24\Cli;

class Progressbar
{
    private $_stream  = null;
    private $_options = [
        'bracket_'     => '[',
        '_bracket'     => ']',
        'fill'         => '=',
        'cursor'       => '>',
        'cursor_color' => 'fg(yellow) bg(black)',
        'fill_color'   => 'fg(white) bg(blacl)',
        'width'        => 4,
        'span'         => 5,
    ];

    public function __construct($stream, $options = array())
    {
        $this->_stream = $stream;
        $this->_name   = 'php5.6.0';

        $this->setOptions($options);
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

    protected function _line($percent, $start_p, $end_p, $start_l)
    {
        $current = round(($percent * $this->getOption('width')) / 100);
        $current = $start_p + $current;

        if ($current > $end_p) {
            $current = $end_p;
        }

        $current = $current - 1;

        if ($current < $start_p) {
            $current = $start_p;
        }

        \Hoa\Console\Cursor::moveTo($current);

        echo $this->getOption('fill').$this->getOption('cursor');

        \Hoa\Console\Cursor::moveTo($start_l);

        if ($percent < 10) {
            echo '0';
        }
        if ($percent < 100) {
            echo '0';
        }

        echo $percent;
    }

    public function run()
    {
        $this->_stream->on('progress', function (Hoa\Core\Event\Bucket $bucket) {

            echo 'Helo';

    return;
});

        $this->_stream->saveAs('/tmp/foo.zip');

        return;
        // <filename> [====>====] 52%
       $information = 'php5.6.0.tar.bz2';
        $span        = $this->getOption('span');
        $baseline    = str_repeat(' ', $span);
        $baseline   .= $this->_name.' ';
        $baseline   .= $this->getOption('bracket_');
        $start_p     = strlen($baseline) +1;
        $baseline   .= str_repeat($this->getOption('fill'), $this->getOption('width'));
        $end_p       = strlen($baseline);
        $baseline   .= $this->getOption('_bracket').'   ';
        $start_l     = strlen($baseline)+1;
        $baseline   .= '100';
        $end_l       = strlen($baseline);
        $baseline   .= ' %';
        $that        = $this;

        echo $baseline;

        \Hoa\Console\Cursor::save();

        for ($i = 0; $i <= 100; $i++) {
            $this->_line($i, $start_p, $end_p, $start_l);
        }

        \Hoa\Console\Cursor::restore();

        echo "\n";
    }
}
