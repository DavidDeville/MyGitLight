<?php

class File
{
	private $content;

	public function __construct(string $name, string $content = null)
	{
		if ($content === null)
		{
			$this->content = fopen($name, "r");
		}

		else
		{
			file_put_contents($name, $content);
			$this->content = fopen($name, "a+");
		}
	}




}

?>
