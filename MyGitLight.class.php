<?php

require_once "File.class.php";
require_once "Folder.class.php";

// Classe de stockage, contient toutes les méthodes/commandes valides
abstract class MyGitLight
{
	// Teste la validité de la requête et l'éxécute
	// Si la requête est invalide, affiche un message d'erreur
	static public function execute(Request $request)
	{
		if (MyGitLight::isValid($request))
		{
			$command = $request->getAction();
			MygitLight::$command($request->getArgs());
		}

		else 
		{
			MyGitLight::error("Commande invalide");
		}
	}

	// Renvoie vrai si la reqête est valide (si elle correspond à une commande)
	// Renvoie faux sinon
	static private function isValid(Request $request)
	{
		$class = new \ReflectionClass("MyGitLight");
		$features = $class->getMethods(ReflectionMethod::IS_PROTECTED);

		$command = $request->getAction();
		
		foreach ($features as $feature)
		{
			if ($feature->getName() === $command)
			{
				return (true);
			}
		}

		return (false);
	}

	static protected function init(Array $args)
	{
		if (count($args) === 0)
		{
			MyGitLight::error("Missing path");
		}

		$sources = Array(
			"main.php",
			"File.class.php",
			"Folder.class.php",
			"MyGitLight.class.php",
			"Request.class.php",
		);

		$init_folder = new Folder($args[0] . "/" . ".MyGitLight/");

		foreach ($sources as $filename)
		{
			$file_copy = $init_folder->getName() . $filename;
			new File($file_copy, file_get_contents($filename));
		}
	}

	static private function error(string $message)
	{
		echo $message . PHP_EOL;
		exit(84);
	}
}

?>
