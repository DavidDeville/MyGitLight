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

function commit_log (string $commit_filename)
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

?>
