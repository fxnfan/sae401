<?php
namespace sae401\Controllers;

use sae401\Services\ViaPdo\Bdd;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class FormFichier {
    public function doIt (Request $request, Response $response) : Response {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'formfichier.html', []);
    }
};