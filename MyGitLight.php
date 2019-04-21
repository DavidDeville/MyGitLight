<?php

require_once "commit.php";
require_once "directories.php";
require_once "files.php";
require_once "tarball.php";

// Si argc est inférieur à 2, le script a été lancé sans commandes
if ($argc < 2)
{
	die("Missing command" . PHP_EOL);
}

// La commande a éxécuter
$command = $argv[1];
$args = array_slice($argv, 2);
$working_directory = directory_locate_upwards(".MyGitLight");

// Les fichiers sources du programme
$sources = Array(
	"MyGitLight.php",
	"directories.php",
	"files.php",
	"tarball.php",
);

if ($command === "init")
{
	// If $args is empty, no path was set for init
	if ($args === [])
	{
		die("Missing path" . PHP_EOL);
	}

	if (file_exists($args[0] . "/.MyGitLight"))
	{
		die("this folder already has a MyGitLight");
	}

	// Reconstruction de l'arborescence de base dans le dossier .MyGitLight
	$tree = directory_generate_path_to($args[0]);
	array_push($tree, $args[0], $args[0] . "/.MyGitLight");
	directory_build_tree($tree);

	// Copie des fichiers 
	foreach ($sources as $source)
	{
		file_copy($args[0] . "/.MyGitLight/" . $source, $source);
	}
} // Fin de la commande init

else if ($command === "add")
{
	if ($working_directory === null)
	{
		die ("No .MyGitLight directory found" . PHP_EOL);
	}

	// If add was called without specifying files, add the whole directory
	if ($args == [])
	{
		$args = directory_browse_files(".", true);
	}

	// Don't add .git folder and .MyGitLight
	foreach ($args as $entry)
	{
		if (
			(strpos($entry, ".git/") === 0) || 
			(strpos($entry, ".MyGitLight/") === 0))
		{
			$args = array_diff($args, Array($entry));
		}
	}

	// If folders were added, breplace them by their content in the list
	foreach ($args as $entry)
	{
		if (is_dir($entry))
		{
			$args = array_diff($args, Array($entry));
			$args = array_merge($args, directory_browse_files($entry, true));
		}
	}
	
	// On récupère l'arborescence de base
	$tree = directory_generate_tree($args);
	foreach ($tree as &$directory)
	{
		$directory = $working_directory . "/" . $directory;
	}
	directory_build_tree($tree);
	foreach ($args as $file)
	{
		file_copy($working_directory . "/" . $file, $file);
	}
} // Fin de la commande add

else if ($command === "commit")
{
	if ($args == [])
	{
		die ("a commit message is needed" . PHP_EOL);
	}

	// Reconstruction du message de commit et création de l'archive
	$message = implode(" ", $args);
	$files = directory_browse_files($working_directory, true);
	$files = array_diff($files, $sources);
	tar_create($files, commit_find_next_name($working_directory));

} // Fin de la commande commit

else
{
	die("Commande inconnue" . PHP_EOL);
}

?>
