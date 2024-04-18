<?php
namespace sae401\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Connexion {
    public function doIt(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        if (!isset($data['pseudo'], $data['password'])) {
        $response->getBody()->write('Missing pseudo or password');
        return $response->withStatus(400);
        }
        $pseudo = $data['pseudo'];
        $password = $data['password'];
        $bdd = new \sae401\Services\ViaPdo\Bdd();
        $pdo = $bdd->connect;
        $stmt = $pdo->prepare('SELECT * FROM user WHERE pseudo = ?');
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $response = $response->withHeader('Location', '/formfichier');
            return $response->withStatus(302);
        } else {
            $response->getBody()->write('Pseudo ou mot de passe incorrect');
        }

        return $response;
    }
}