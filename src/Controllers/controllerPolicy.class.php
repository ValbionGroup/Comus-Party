<?php

namespace ComusParty\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ControllerPolicy extends Controller
{
    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
        parent::__construct($loader, $twig);
    }

    public function showCgu()
    {
        $template = $this->getTwig()->load('cgu.twig');
        echo $template->render();
    }
}