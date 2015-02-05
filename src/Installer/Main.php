<?php
namespace Launchee\Installer;

class Main extends Generic
{
    public function install()
    {
        $vendor = $this->get('vendor');
        $name   = $this->get('name');

        // new \Launchee\File('Application.ini', 'application.tpl.php', ['vendor' => $vendor, 'name' => $name]);

        $item = new \Launchee\Download();
        $item->get('https://github.com/twbs/bootstrap/releases/download/v3.3.2/bootstrap-3.3.2-dist.zip', INSTALL_DIR.'vendor-src');
    }
}
