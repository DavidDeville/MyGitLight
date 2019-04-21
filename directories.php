<?php

/*
** Given a directory, returns the list of files inside of it
** Directories won't be included, unless $recursive is true
**
** @param $directory_name : the folder to navigate through
** @param $recursive : navigates through sub-folders or not
** 
** @return : an array of "path"/"filename"
*/
function directory_browse_files(string $directory_name, bool $recursive = false)
{
	$files = Array();
	$directory_content = scandir($directory_name);
	$directory_content = array_diff($directory_content, Array(".", ".."));
	
	foreach ($directory_content as $entry)
	{
		if (is_dir($directory_name . "/" . $entry))
		{
			if ($recursive)
			{
				$files = array_merge($files, tar_list_directory($directory_name . $entry . "/", $recursive));
			}
			else
			{
				continue;
			}
		}
		else
		{
			array_push($files, $directory_name . $entry);
		}
	}

	return ($files);
}

/*
** Given a relative path to a file, generates the route of successive folders
** to reach it
**
** @param $path : the file to reach
**
** @return : an array of folders to go through to reach $path
*/
function directory_generate_path_to(string $path) 
{
	$tree = Array();

	$slash_pos = -1;
	
	do 
	{
		$slash_pos = strpos($path, "/", $slash_pos + 1);
		
		if ($slash_pos !== false) 
		{
			array_push($tree, substr($path, 0, $slash_pos) . "/");
		}
	} while ($slash_pos !== false);

	return ($tree);
}

/*
** Given a list of relative paths, generates the list of folders required to reach them
**
** @param $path_list : an array of path to generate tree from
**
** @return : every unique folder required to access everything in $ath_list
*/
function directory_generate_tree(Array $path_list)
{
	$tree = Array();
	
	foreach ($path_list as $path)
	{
		$tree = array_merge($tree, directory_generate_path_to($path));
		$tree = array_unique($tree);
	}

	return ($tree);
}

/*
** Given a list of folder, creates them
**
** @param $tree : the list of directories to create
** @param $scripts_args : args sent to the script
*/
function directory_build_tree(Array $tree)
{
	foreach ($tree as $directory)
	{
		if (file_exists($directory))
		{
			continue;
		}
		else 
		{
			mkdir($directory);
		}
	}
}

?>
