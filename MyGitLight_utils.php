<?php


function init(Array $args, Array $sources)
{
	// If $args is empty, no path was set for init
	if ($args === [])
	{
		die("Missing path" . PHP_EOL);
	}

	if (file_exists($args[0] . "/.MyGitLight"))
	{
		die("this folder already has a MyGitLight" . PHP_EOL);
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

}

function rm(Array $args)
{
	foreach ($args as $file)
	{
		if (file_exists($file))
		{
			unlink($file);
		}
		if (file_exists($working_directory . "/" . $file))
		{
			unlink($working_directory . "/" . $file);
		}
	}
}

function add(string $working_directory, Array $args)
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
}

?>
