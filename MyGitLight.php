<?php


require_once "directories.php";
require_once "files.php";

if ($argc < 2)
{
	die("Missing command" . PHP_EOL);
}

$command = $argv[1];
$sources = Array(
	"MyGitLight.php",
	"directories.php",
);

if ($command === "init")
{
	$args = array_slice($argv, 2);
	if ($args === [])
	{
		die("Missing path" . PHP_EOL);
	}

	if (file_exists($args[0] . "/.MyGitLight"))
	{
		die("this folder already has a MyGitLight");
	}

	$tree = directory_generate_path_to($args[0]);
	array_push($tree, $args[0], $args[0] . "/.MyGitLight");
	directory_build_tree($tree);

	foreach ($sources as $source)
	{
		file_copy($args[0] . "/.MyGitLight/" . $source, $source);
	}
}

else
{
	die("Commande inconnue" . PHP_EOL);
}

?>
