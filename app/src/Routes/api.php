<?php

use App\Controllers\BilhetesController;
use App\Controllers\BilhetesDezenasController;
use App\Controllers\TripulantesController;
use App\Controllers\BilhetesPremiadosController;
use App\Controllers\BilhetesPremiadosDezenasController;


$bilhetesController = new BilhetesController();
$bilhetesDezenasController = new BilhetesDezenasController();
$tripulantesController = new TripulantesController();
$bilhetesPremiadosController = new BilhetesPremiadosController();
$bilhetesPremiadosDezenasController = new BilhetesPremiadosDezenasController();

/**
 * TRIPULANTES
 */
$router->addRoute('GET', '/api/v1/tripulantes', function () use ($tripulantesController) {
    return $tripulantesController->index();
});

$router->addRoute('GET', '/api/v1/tripulantes/{id_tripulante}', function ($id_tripulante) use ($tripulantesController) {
    return $tripulantesController->show($id_tripulante);
});

$router->addRoute('POST', '/api/v1/tripulantes', function () use ($tripulantesController) {
    $data = json_decode(file_get_contents('php://input'), true);  // Converte o JSON para array associativo
    return $tripulantesController->store($data);
});

$router->addRoute('PUT', '/api/v1/tripulantes/{id_tripulante}', function ($id_tripulante) use ($tripulantesController) {
    $data = json_decode(file_get_contents('php://input'), true);  // Converte o JSON para array associativo
    return $tripulantesController->update($data, $id_tripulante);
});

$router->addRoute('DELETE', '/api/v1/tripulantes/{id_tripulante}', function ($id_tripulante) use ($tripulantesController) {
    return $tripulantesController->delete($id_tripulante);
});

/**
 * BILHETES
 */
$router->addRoute('GET', '/api/v1/bilhetes', function () use ($bilhetesController) {
    return $bilhetesController->index();
});

$router->addRoute('GET', '/api/v1/bilhetes/{id_bilhete}', function ($id_bilhete) use ($bilhetesController) {
    return $bilhetesController->show($id_bilhete);
});

$router->addRoute('POST', '/api/v1/bilhetes', function () use ($bilhetesController) {
    $data = json_decode(file_get_contents('php://input'), true);  // Converte o JSON para array associativo
    return $bilhetesController->store($data);
});

$router->addRoute('PUT', '/api/v1/bilhetes/{id_bilhete}', function ($id_bilhete) use ($bilhetesController) {
    $data = json_decode(file_get_contents('php://input'), true);  // Converte o JSON para array associativo
    return $bilhetesController->update($data, $id_bilhete);
});

$router->addRoute('DELETE', '/api/v1/bilhetes/{id_bilhete}', function ($id_bilhete) use ($bilhetesController) {
    return $bilhetesController->delete($id_bilhete);
});

/**
 * BILHETES DEZENAS
 */
$router->addRoute('GET', '/api/v1/bilhetes-dezenas', function () use ($bilhetesDezenasController) {
    return $bilhetesDezenasController->index();
});

$router->addRoute('GET', '/api/v1/bilhetes-dezenas/{id_bilhete}', function ($id_bilhete) use ($bilhetesDezenasController) {
    return $bilhetesDezenasController->show($id_bilhete);
});

$router->addRoute('POST', '/api/v1/bilhetes-dezenas', function () use ($bilhetesDezenasController) {
    $data = json_decode(file_get_contents('php://input'), true);  // Converte o JSON para array associativo
    return $bilhetesDezenasController->store($data);
});

$router->addRoute('DELETE', '/api/v1/bilhetes-dezenas/{id_bilhete_dezena}/{dezena}', function ($id_bilhete, $dezena) use ($bilhetesDezenasController) {
    return $bilhetesDezenasController->delete($id_bilhete, $dezena);
});

/**
 * BILHETES PREMIADOS
 */
$router->addRoute('GET', '/api/v1/bilhetes-premiados', function () use ($bilhetesPremiadosController) {
    return $bilhetesPremiadosController->index();
});

$router->addRoute('POST', '/api/v1/bilhetes-premiados', function () use ($bilhetesPremiadosController) {
    $data = json_decode(file_get_contents('php://input'), true);  // Converte o JSON para array associativo
    return $bilhetesPremiadosController->store($data);
});

$router->addRoute('DELETE', '/api/v1/bilhetes-premiados/{id_bilhete_premiado}', function ($id_bilhete_premiado) use ($bilhetesPremiadosController) {
    return $bilhetesPremiadosController->delete($id_bilhete_premiado);
});

/**
 * BILHETES PREMIADOS DEZENAS
 */
$router->addRoute('GET', '/api/v1/bilhetes-premiados-dezenas', function () use ($bilhetesPremiadosDezenasController) {
    return $bilhetesPremiadosDezenasController->index();
});

$router->addRoute('POST', '/api/v1/bilhetes-premiados-dezenas', function () use ($bilhetesPremiadosDezenasController) {
    $data = json_decode(file_get_contents('php://input'), true);  // Converte o JSON para array associativo
    return $bilhetesPremiadosDezenasController->store($data);
});

$router->addRoute('DELETE', '/api/v1/bilhetes-premiados-dezenas/{id_bilhete_premiado}/{dezena}', function ($id_bilhete_premiado, $dezena) use ($bilhetesPremiadosDezenasController) {
    return $bilhetesPremiadosDezenasController->delete($id_bilhete_premiado, $dezena);
});
