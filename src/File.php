<?php
namespace Launchee;

class File
{
    public function __construct($filename, $tplfile, $data)
    {
        $greut  = new \Launchee\Greut();

        $greut->setData($data);

        $installFilename = INSTALL_DIR.$filename;
        $file             = $greut->renderFile($tplfile);

        echo $installFilename."\n";
        echo $file."\n";
    }
}
