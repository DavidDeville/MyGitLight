<?php

require_once "directories.php";

function commit_exists(string $working_directory, int $version)
{
	$files = directory_browse_files($working_directory);
	foreach ($files as $entry)
	{
		var_dump($entry);
		if ($entry === $working_directory . "/" . $version . ".mytar")
		{
			return (true);
		}
	}
	return (false);
}

function commit_find_next_id(string $working_directory)
{
	$files = directory_browse_files($working_directory);
	$version = 1;
	while (commit_exists($working_directory, $version))
	{
		$version++;
	}
	return ($version);
}

function commit_save_message(string $commit_file, int $commit_id, string $message)
{
	$file = fopen($commit_file, "a");
	fwrite($file, $commit_id . ": " . $message . PHP_EOL);
	fclose($file);
}

function commit_log(string $commit_filename)
{
	$file = fopen($commit_filename, "r");
	$lines = [];

	while (($line = fgets($file)) !== false)
	{
		array_push($lines, $line);
	}

	$lines = array_reverse($lines);
	foreach ($lines as $line)
	{
		echo $line;
	}
}

function commit(string $working_directory, Array $args)
{
	if ($args == [])
	{
		die ("a commit message is needed" . PHP_EOL);
	}
	$message = implode(" ", $args);
	$files = directory_browse_files($working_directory, true);
	$commit_id = commit_find_next_id($working_directory);
	$commit_file = $working_directory . "/.COMMIT_MESSAGE";
	tar_create($files, $working_directory . "/" . $commit_id . ".mytar");
	commit_save_message($commit_file, $commit_id, $message);
}


?>
