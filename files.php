<?php

function file_copy(string $new_filename, string $original_filename)
{
	$file_content = file_get_contents($original_filename);
	file_put_contents($new_filename, $file_content);
}

function file_remove_path(string $filename)
{
	$last_slash_pos = strrpos($filename, "/");
	if ($last_slash_pos !== false)
	{
		$filename = substr($filename, $last_slash_pos + 1);
	}

	return ($filename);
}

function file_was_modified(string $check_filename, string $filename)
{
	return (md5($check_filename) !== md5($filename));
}

?>
