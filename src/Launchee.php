<?php
namespace Launchee;

$path =  __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
    require $path;

    if (defined('ROOT_DIR') === false) {
        define('ROOT_DIR', realpath(__DIR__.DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR);
    }


    if (defined('TPL_DIR') === false) {
        define('TPL_DIR', realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'tpl').DIRECTORY_SEPARATOR);
    }

    unset($_SERVER['TERM']);

    /**
     * Here we go…
     */
    $message = null;
    try {
        $router = new \Hoa\Router\Cli();
        $router->get(
            'g',
            '((?<command>\w+))?(?<_tail>.*?)',
            'command',
            'welcome',
            array(
                'library' => 'command',
                'command' => 'welcome',
            )
        );

        $dispatcher = new \Hoa\Dispatcher\Basic(
            array(
                'synchronous.call' => '\Launchee\Command\(:%variables.command:lU:)',
                'synchronous.able'     => 'main',
            )
        );

        exit($dispatcher->dispatch($router));
    } catch (\Hoa\Core\Exception $e) {
        $message = $e->raise(true);
    } catch (\Exception $e) {
        $message = $e->getMessage();
    }

    \Hoa\Console\Cursor::colorize('foreground(white) background(red)');
    echo $message, "\n";
    \Hoa\Console\Cursor::colorize('normal');
