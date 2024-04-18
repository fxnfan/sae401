<?php
namespace sae401\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

class Stylesheet {
    public function doIt (Request $request, Response $response) : Response {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'style.css', []);
    }
}