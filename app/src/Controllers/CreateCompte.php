<?php
namespace sae401\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateCompte {
    public function doIt(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        if (!isset($data['pseudo'], $data['password'])) {
            $response->getBody()->write('Missing pseudo or password');
            return $response->withStatus(400);
        }
        $pseudo = $data['pseudo'];
        $password = $data['password'];

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $bdd = new \sae401\Services\ViaPdo\Bdd();
        $pdo = $bdd->connect;

        $stmt = $pdo->prepare('INSERT INTO user (pseudo, password) VALUES (?, ?)');
        
        $stmt->execute([$pseudo, $hashed_password]);

        $response = $response->withHeader('Location', '/formulaire');
        return $response->withStatus(302);
    }
}