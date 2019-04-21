<?php

require_once "commit.php";
require_once "directories.php";
require_once "files.php";
require_once "MyGitLight_utils.php";
require_once "tarball.php";

// Si argc est inférieur à 2, le script a été lancé sans commandes
if ($argc < 2)
{
	die("Missing command" . PHP_EOL);
}

// Les fichiers sources du programme
$sources = Array(
	"MyGitLight.php",
	"MyGitLight_utils.php",
	"commit.php",
	"directories.php",
	"files.php",
	"tarball.php",
);

// Fichiers de travail de MyGitLight
$ignore_files = Array(
	".COMMIT_MESSAGE",
);

// La commande a éxécuter
$command = $argv[1];
$args = array_slice($argv, 2);
$args = array_diff($args, $sources);
$args = array_diff($args, $ignore_files);

// L'emplacement du dossier .MyGitLight
$working_directory = directory_locate_upwards(".MyGitLight");

if ($command === "init")
{
	init($args, $sources);
} 

else if ($command === "add")
{
	add($working_directory, $args);
}

else if ($command === "commit")
{
	commit($working_directory, $args);
}

else if ($command === "log")
{
	commit_log($working_directory . "/.COMMIT_MESSAGE");
}

else if ($command === "rm")
{
	rm($args);
}

else
{
	die("Commande inconnue" . PHP_EOL);
}

?>
