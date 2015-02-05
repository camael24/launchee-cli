<?php
namespace Launchee\Command;

use Hoa\Console\Chrome\Text;

    class Validate extends \Hoa\Console\Dispatcher\Kit
    {
        protected $options = array(
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, 'h'),
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, '?'),
        );

        /**
         * The entry method.
         *
         * @access  public
         * @return  int
         */
        public function main()
        {
            while (false !== $c = $this->getOption($v)) {
                switch ($c) {

                    case 'h':
                    case '?':
                        return $this->usage();
                        break;
                }
            }

            $fichier = null;
            $this->parser->listInputs($fichier);

            if ($fichier === null) {
                $fichier = '/home/camael/dev/project/launchee/launchee.json';
            }

            $options = new \Launchee\Options(new \Hoa\File\Read($fichier));

            if ($options->isValid() === false) {
                \Hoa\Console\Cursor::colorize('foreground(white) background(red)');
                foreach ($options->getErrors() as $value) {
                    echo $value, "\n";
                }

                \Hoa\Console\Cursor::colorize('normal');
            } else {
                \Hoa\Console\Cursor::colorize('foreground(white) background(green)');
                echo 'IS OK', "\n";
                \Hoa\Console\Cursor::colorize('normal');
            }
        }

        /**
         * The command usage.
         *
         * @access  public
         * @return  int
         */
        public function usage()
        {
            echo \Hoa\Console\Chrome\Text::colorize('Usage:', 'fg(yellow)')."\n";
            echo '   Welcome '."\n\n";

            echo \Hoa\Console\Chrome\Text::colorize('Options:', 'fg(yellow)'), "\n";
            echo $this->makeUsageOptionsList(array(
                'help' => 'This help.',
            ));

            return;
        }
    }

__halt_compiler();
This Page
