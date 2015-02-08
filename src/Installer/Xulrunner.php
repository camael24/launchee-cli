<?php
namespace Launchee\Installer;

class Xulrunner extends Generic
{
    public function install()
    {
        if (OS_WIN === true) {
            $dl = new \Launchee\Download();
            $dl->get($this->get('source-win'), INSTALL_DIR);
            $dl->run();

            $path = pathinfo($this->get('source-win'));

            $file = new \Launchee\Extract(INSTALL_DIR);
            $file->bz2(INSTALL_DIR.$path['basename'], '');
            $file->run();

            rename(INSTALL_DIR.'xulrunner'.DIRECTORY_SEPARATOR.'xulrunner-stub.exe', INSTALL_DIR.'app.exe');
            chmod(INSTALL_DIR.'app.exe', 0777);
        } else {
            $dl = new \Launchee\Download();
            $dl->get($this->get('source-unix'), INSTALL_DIR);
            $dl->run();

            $path = pathinfo($this->get('source-unix'));

            $file = new \Launchee\Extract(INSTALL_DIR);
            $file->bz2(INSTALL_DIR.$path['basename'], '');
            $file->run();

            rename(INSTALL_DIR.'xulrunner'.DIRECTORY_SEPARATOR.'xulrunner-stub', INSTALL_DIR.'app');
            chmod(INSTALL_DIR.'app', 0777);
        }
    }
}
