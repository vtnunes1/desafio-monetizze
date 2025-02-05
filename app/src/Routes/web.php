<?php

use App\Controllers\BilhetesController;
use App\Controllers\BilhetesPremiadosController;

$bilhetesController = new BilhetesController();
$bilhetesPremiadosController = new BilhetesPremiadosController();

$router->addRoute('GET', '/', function () use ($bilhetesController, $bilhetesPremiadosController) {

    // Iniciar a sessão para acessar as mensagens armazenadas
    session_start();

    // Verificar se há mensagens de erro ou sucesso na sessão
    $requestError = isset($_SESSION['requestError']) ? $_SESSION['requestError'] : null;
    $requestSuccess = isset($_SESSION['requestSuccess']) ? $_SESSION['requestSuccess'] : null;

    // Limpar as mensagens da sessão após pegá-las (para não exibir novamente na próxima requisição)
    unset($_SESSION['requestError']);
    unset($_SESSION['requestSuccess']);

    // Incluir o template e passar as mensagens para ele
    include '../src/Views/index.php';

    exit();
});

$router->addRoute('GET', '/gerar-bilhete-premiado', function () use ($bilhetesPremiadosController) {

    //** GERA BILHETE PREMIADO **//
    $request = $bilhetesPremiadosController->gerarBilhetePremiado();

    // ** START SESSION **//
    session_start();

    if (isset($request['error'])) {
        $_SESSION['requestError'] = $request['error'];  // Armazena o erro na sessão
    } else {
        $_SESSION['requestSuccess'] = $request['success'];  // Armazena o sucesso na sessão
    }

    // ** GET URL DE ORIGEM DA REQUISICAO **//
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';

    // ** REDIRECT **//
    header('Location: ' . $referer);
    exit();
});

$router->addRoute('POST', '/gerar-bilhete', function () use ($bilhetesController) {

    // ** VERIFICA TIPO REQUISICAO **//
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // ** START SESSION **//
        session_start();

        // ** GET DADOS FORMULÁRIO **//
        $numeros = isset($_POST['quantidadeBilhetes']) ? htmlspecialchars($_POST['quantidadeBilhetes']) : null;
        $dezenas = isset($_POST['quantidadeDezenas']) ? htmlspecialchars($_POST['quantidadeDezenas']) : null;
        $idTripulante = isset($_POST['idTripulante']) ? htmlspecialchars($_POST['idTripulante']) : null;

        // ** GERA BILHETES **//
        $request = $bilhetesController->gerarBilhetes($numeros, $dezenas, $idTripulante);
        if (isset($request['error'])) {
            $_SESSION['requestError'] = $request['error'];  // Armazena o erro na sessão
        } else {
            $_SESSION['requestSuccess'] = $request['success'];  // Armazena o sucesso na sessão
        }

        // ** GET URL DE ORIGEM DA REQUISICAO **//
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';

        // ** REDIRECT **//
        header('Location: ' . $referer);
        exit();
    }

    echo "Erro ao processar requisição";
    exit();
});

$router->addRoute('GET', '/remover-bilhetes', function () use ($bilhetesController) {

    //** REMOVE BILHETES **//
    $request = $bilhetesController->removerBilhetes();

    // ** START SESSION **//
    session_start();

    if (isset($request['error'])) {
        $_SESSION['requestError'] = $request['error'];  // Armazena o erro na sessão
    } else {
        $_SESSION['requestSuccess'] = $request['success'];  // Armazena o sucesso na sessão
    }

    // ** GET URL DE ORIGEM DA REQUISICAO **//
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';

    // ** REDIRECT **//
    header('Location: ' . $referer);
    exit();
});
