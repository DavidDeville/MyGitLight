<?php

require_once "MyGitLight.class.php";
require_once "Request.class.php";

$request = new Request($argc, $argv);
MyGitLight::execute($request);



?>
