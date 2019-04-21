<?php

require_once "directories.php";

function commit_exists(string $working_directory, int $version)
{
	foreach ($files as $entry)
	{
		if ($entry === $version . ".mytar")
		{
			return (true);
		}
	}

	return (false);
}

function commit_find_next_name(string $working_directory)
{
	$files = directory_browse_files($working_directory);
	$version = 1;

	while (commit_exists($working_directory, $version))
	{
		$version++;
	}

	return $version . ".mytar";
}

function commit

?>
