<?php

$request = $_SERVER['REQUEST_URI'];
$pagesLocation = '/src/pages/';

$baseUrl = '/comusparty';
$request = str_replace($baseUrl, '', $request);

switch ($request) {
    case '':
    case '/':
        require __DIR__ . $pagesLocation . 'index.php';
        break;

    default:
        http_response_code(404);
        require __DIR__ . $pagesLocation . '404.php';
}