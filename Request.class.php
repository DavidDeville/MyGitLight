<?php

// Représente l'action voulue par l'utilisateur
class Request
{
	private $action; // La commande à éxécuter
	private $args; // Les arguments de la commande à éxécuter

	public function __construct(int $argc, Array $argv)
	{
		$this->action = $argv[1];
		$this->args = array_slice($argv, 2);
	}

	public function getAction()
	{
		return $this->action;
	}

	public function getArgs()
	{
		return $this->args;
	}
}

?>
