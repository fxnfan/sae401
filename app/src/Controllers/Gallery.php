<?php
namespace sae401\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

class Gallery {
    public function doIt(Request $request, Response $response, array $args) {

        $userId = $_SESSION['id'];

        $bdd = new \sae401\Services\ViaPdo\Bdd();
        $pdo = $bdd->connect;

        $stmt = $pdo->prepare('SELECT * FROM fichier WHERE id_user = :userId');
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $fichiers = $stmt->fetchAll();

        foreach ($fichiers as &$fichier) {
            $fichier['file'] = base64_encode($fichier['file']);
        }

        $stmt = $pdo->prepare('SELECT pseudo FROM user WHERE id = :userId');
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $user = $stmt->fetch();
        $pseudo = $user['pseudo'];

        $view = Twig::fromRequest($request);
        return $view->render($response, 'gallery.html', ['fichiers' => $fichiers, 'pseudo' => $pseudo]);
    }
}