<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig as Twig;
use Slim\Views\TwigMiddleware as TwigMiddleware;

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require __DIR__ . '/vendor/autoload.php';

session_start(); 

$app = AppFactory::create();

$twing = Twig::create(__DIR__ . '/templates', ['cache' => false]);

$app->add(TwigMiddleware::create($app, $twing));

$app->add(function (Request $request, RequestHandler $handler){
    $response = $handler->handle($request);
    return $response->withHeader('Access-control-allow-origin', '*');
});

$app->get('/', \sae401\Controllers\Formulaire::class . ':doIt')
        ->setName('formulaire');


$app->get('/formulaire', \sae401\Controllers\Formulaire::class . ':doIt')
        ->setName('formulaire');

$app->get('/create', \sae401\Controllers\Create::class . ':doIt')
        ->setName('create');

$app->get('/formfichier', \sae401\Controllers\FormFichier::class . ':doIt')
        ->setName('formfichier');   
    
$app->get('/addMessageReussi', \sae401\Controllers\FichierReussi::class . ':doIt')
        ->setName('addMessageReussi');     

$app->get('/gallery', \sae401\Controllers\Gallery::class. ':doIt')
    ->setName('gallery');

$app->get('/style', \sae401\Controllers\Stylesheet::class. ':doIt')
    ->setName('style');
    
$app->get('/assets/{path:.*}', function ($request, $response, $args) {
    $file = __DIR__ . '/assets/' . $args['path'];
    if (file_exists($file)) {
        $contentType = mime_content_type($file);
        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
        if ($fileExtension === 'css') {
            $contentType = 'text/css';
        } elseif ($fileExtension === 'js') {
            $contentType = 'application/javascript';
        }
        return $response->withHeader('Content-Type', $contentType)
                        ->withBody(new \Slim\Psr7\Stream(fopen($file, 'r')));
    } else {
        throw new \Slim\Exception\HttpNotFoundException($request);
    }
});




$app->post('/connexion', \sae401\Controllers\Connexion::class. ':doIt')
    ->setName('connexion');

$app->post('/CreateCompte', \sae401\Controllers\CreateCompte::class. ':doIt')
    ->setName('CreateCompte');

$app->post('/drop', \sae401\Controllers\Drop::class.':doIt')
    ->setName('drop');

$app->run();
