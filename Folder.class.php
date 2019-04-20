<?php

class Folder
{
	private $content;
	private $name;

	public function __construct(string $name)
	{
		$this->name = $name;
		Folder::create_tree(Folder::generate_tree($name));
		$this->content = opendir($name);
	}

	// Depuis un path, génère la liste de ses sous-dossiers
	// Renvoie un tableau contenant chaque nom de dossier comme étant des strings
	static private function generate_tree(string $path)
	{
		$tree = [];

		$slash_pos = -1;
		while (($slash_pos = strpos($path, "/", $slash_pos + 1)) !== false)
		{
			$sub_folder = substr($path, 0, $slash_pos);
			array_push($tree, $sub_folder);
		}

		return ($tree);
	}

	// Depuis une liste de noms de dossiers, les crée
	static private function create_tree(Array $tree)
	{
		foreach ($tree as $folder)
		{
			if (file_exists($folder))
			{
				continue;
			}
			else
			{
				mkdir($folder);
			}
		}
	}

	public function getName()
	{
		return $this->name;
	}
}

?>
