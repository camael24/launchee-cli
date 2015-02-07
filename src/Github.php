<?php
namespace Launchee;

class Github
{
    public function tag($name, $tag)
    {
        $git = \Hoa\Console\Processus::locate('git');

        if ($git === null) {
            throw new Exception("Git are not installed", 0);
        }

        if (is_dir(INSTALL_DIR.'/console') === false) {
            $cmd = new Git(INSTALL_DIR);
            $cmd->repo('https://github.com/'.$name, 'console');
            $cmd->run();
        }

//		if($tag !== 'master')
//		{
//			$command = new Command();
//			$command->run('git' , ['remote show origin'], INSTALL_DIR.'/console');
//		}
    }
    public function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") {
                        $this->rrmdir($dir."/".$object);
                    } else {
                        unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function release($repository, $tag)
    {
        // Download & Extract

        $file = 'https://github.com/'.$repository.'/archive/'.$tag.'.zip';
        $name = strtolower(substr($repository, strpos($repository, '/') + 1, strlen($repository)));

        $download = new \Launchee\Download();
        $download->get($file, INSTALL_DIR.'vendor-src', $repository);
        $download->run();

        $extract = new \Launchee\Extract(INSTALL_DIR);
        $extract->unzip(INSTALL_DIR.'vendor-src/'.$tag.'.zip', '', $repository);
        $extract->run();

        if (is_dir(INSTALL_DIR.$name)) {
            $this->rrmdir(INSTALL_DIR.$name);
        }

        if (is_dir(INSTALL_DIR.ucfirst($name).'-'.$tag) === true) {
            rename(INSTALL_DIR.ucfirst($name).'-'.$tag, INSTALL_DIR.$name);
        } elseif (is_dir(INSTALL_DIR.$name.'-'.$tag) ===  true) {
            rename(INSTALL_DIR.$name.'-'.$tag, INSTALL_DIR.$name);
        } else {
            throw new Exception("Can't find the folder, report a bug (%s)", 1, [INSTALL_DIR.$name.'-'.$tag]);
        }
    }

    public function get($name, $tag)
    {
        $this->release($name, $tag);
    }
}
