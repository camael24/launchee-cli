<?php
namespace Launchee\Installer;

class Main extends Generic
{
    public function install()
    {
        $vendor = $this->get('vendor');
        $name   = $this->get('name');

        // new \Launchee\File('Application.ini', 'application.tpl.php', ['vendor' => $vendor, 'name' => $name]);

//        $item = new \Launchee\Download();
//        $item->get('http://windows.php.net/downloads/releases/php-5.6.5-nts-Win32-VC11-x86.zip', INSTALL_DIR.'vendor-src');
//        $item->get('http://ark.im/', INSTALL_DIR.'vendor-src');
//        $item->get('http://edm.ark.im/', INSTALL_DIR.'vendor-src');
//        $item->run();

        $file = new \Launchee\Extract(INSTALL_DIR);

        $file->file(INSTALL_DIR.'vendor-src/php-5.6.5-nts-Win32-VC11-x86.zip', 'php');
        $file->file(INSTALL_DIR.'vendor-src/php-5.6.5-nts-Win32-VC11-x86 (copie).zip', 'php');
        $file->unzip();
    }
}
