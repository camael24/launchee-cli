<?php 
namespace Camael24\Cli\Helper;

interface IProgressbar  {
	public function start();

	public function stop();

	public function seek($percent);
}