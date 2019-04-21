<?php

require_once "directories.php";

/*
** Write the header of the tarball
** The format is as follows:
**    - first line is the number of files included in the archive (4 bytes)
**    - blocks of 2 lines for every file, 1 for filename, 1 for its size
**
** @param $output : the name of the file where to write the header
** @param $files : the list of input files
*/
function tar_create_header(string $output, Array $files)
{
	$tarball = fopen($output, "w");
	fwrite($tarball, count($files) . PHP_EOL);
	
	foreach ($files as $filename)
	{
		fwrite($tarball, $filename . PHP_EOL);
		fwrite($tarball, filesize($filename) . PHP_EOL);
	}
	
	fclose($tarball);
}

/*
** Creates a tarball named $args["output"] and containing files in $args["input"]
**
** @param $args : the array of args sent to the script
*/
function tar_create(Array $args)
{
	$recursive = in_array("recursive", $args["options"]);
	
	foreach ($args["input"] as $entry)
	{
		if (is_dir($entry))
		{
			$entry_content = directory_browse_files($entry, $recursive);
			$args["input"] = array_merge($args["input"], $entry_content);
			$args["input"] = array_diff($args["input"], Array($entry));
		}
	}

	tar_create_header($args["output"], $args["input"]);
	
	foreach ($args["input"] as $entry)
	{
		file_put_contents($args["output"], file_get_contents($entry), FILE_APPEND);
	}	
}

/*
** Reads the header of the tarball to retrieve informations about its content
**
** @param $tarball : the tarball opened with fopen()
**
** @return : an array where keys are filenames and values are their size
*/
function tar_parse_header(&$tarball)
{
	$header_content = Array();
	$entries_count = intval(fgets($tarball));

	for ($parsed_entries = 0; $parsed_entries < $entries_count; $parsed_entries++)
	{
		$entry_name = fgets($tarball);
		$entry_name = preg_replace("/\n/", "", $entry_name);
		$entry_size = intval(fgets($tarball));
		$header_content[$entry_name] = $entry_size;
	}

	return ($header_content);
}

/*
** Extracts files inside the tarball and place them in the right folder
**
** @param $tarball_name : the tarball to extract
** @param $script_args : args sent to the script
*/
function tar_extract(string $tarball_name, Array &$script_args)
{
	$tarball = fopen($tarball_name, "r");
	$tarball_content = tar_parse_header($tarball);

	$files = array_keys($tarball_content);
	$tree = directory_generate_tree($files);
	directory_build_tree($tree, $script_args);

	$restore_file = function (string $file_name, string $file_content)
	{
		$file = fopen($file_name, "w");
		fwrite($file, $file_content);
		fclose($file);
	};

	foreach ($tarball_content as $file_name => $file_size)
	{
		$file_content = fread($tarball, intval($file_size));
		if (file_exists($file_name))
		{
			if (in_array("force", $script_args["options"]))
			{
				$restore_file($file_name, $file_content);
			}

			else
			{
				$confirm = ask_overwrite_confirmation($file_name, $script_args);
				if ($confirm)
				{
					$restore_file($file_name, $file_content);
				}
				else
				{
					continue;
				}
			}
		}
		else
		{
			$restore_file($file_name, $file_content);
		}
	}

	fclose($tarball);
}

?>
