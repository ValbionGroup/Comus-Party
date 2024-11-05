<?php
require_once  '../../include.php';

$template = $twig->load('default.twig');

echo $template->render();