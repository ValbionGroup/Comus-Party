<?php

require_once  '../../include.php';

$template = $twig->load('signUp.twig');

echo $template->render(array());
