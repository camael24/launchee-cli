<?php
namespace Launchee\Command;

use Hoa\Console\Chrome\Text;
use Hoa\Core\Exception\Exception;

    class Install extends \Hoa\Console\Dispatcher\Kit
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

            define('INSTALL_DIR', ROOT_DIR.'tmp'.DIRECTORY_SEPARATOR);

            $fichier = null;
            $this->parser->listInputs($fichier);

            if ($fichier === null) {
                $fichier = '/home/camael/dev/project/launchee/launchee.json';
                if (!file_exists($fichier)) {
                    $fichier = 'C:\\www\\launchee-cli\\launchee.json';
                }
            }

            $options = new \Launchee\Options(new \Hoa\File\Read($fichier));

            if ($options->isValid() === false) {
                throw new Exception("File %s are not valid fix [%s]", 0, [$fichier, implode(',', $options->getErrors())]);
            }

            $main = (new \Launchee\Installer\Main($options->all()))->install();

            foreach ($options->get('http') as $http_server => $opt) {
                switch ($http_server) {
                    case 'nginx':
                    case 'php':
                    case 'apache':
                    default:
                        break;
                }
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
