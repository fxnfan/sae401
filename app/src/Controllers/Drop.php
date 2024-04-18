<?php
namespace sae401\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig as Twig;

class Drop {
    public function doIt(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        if (!isset($data['titre'], $data['description'])) {
            $response->getBody()->write('Missing titre, description or blob');
            return $response->withStatus(400);
        }
        $titre = $data['titre'];
        $description = $data['description'];

        $files = $request->getUploadedFiles();
        if (!isset($files['blob'])) {
            $response->getBody()->write('No file uploaded');
            return $response->withStatus(400);
        }

        $blob = $files['blob'];
        if ($blob->getError() !== UPLOAD_ERR_OK) {
            $response->getBody()->write('Failed to upload file');
            return $response->withStatus(500);
        }

        $tmpPath = sys_get_temp_dir() . '/' . $blob->getClientFilename();
        $blob->moveTo($tmpPath);

        $blobContent = file_get_contents($tmpPath);

        unlink($tmpPath);

        if (isset($_SESSION['id'])) {
            $id_user = $_SESSION['id'];
        } else {
            $response->getBody()->write('User is not logged in');
            return $response->withStatus(401);
        }

        $bdd = new \sae401\Services\ViaPdo\Bdd();
        $pdo = $bdd->connect;

        $stmt = $pdo->prepare('INSERT INTO fichier (titre, description, file, id_user) VALUES (?, ?, ?, ?)');

        $stmt->execute([$titre, $description, $blobContent, $id_user]);

        $response = $response->withHeader('Location', '/addMessageReussi');
        return $response->withStatus(302);
    }
}