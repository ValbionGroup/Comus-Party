<?php

require_once  '../../include.php';

$template = $twig->load('sign_up.twig');

echo $template->render(array());
