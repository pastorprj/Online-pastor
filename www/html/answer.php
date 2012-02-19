<?php

include("classes/Pastor.php");
$question = $_REQUEST['question'];

$pastor = new Pastor();
$response = $pastor->process($question);

echo $response;
