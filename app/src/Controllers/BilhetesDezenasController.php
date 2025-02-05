<?php

namespace App\Controllers;

use App\Models\BilheteDezena;

class BilhetesDezenasController
{
    /**
     * Modelo responsável pela manipulação das dezenas de bilhetes.
     *
     * @var BilheteDezena
     */
    protected $bilheteDezenaModel;

    /**
     * Construtor da classe, responsável por inicializar os modelos de BilheteDezena.
     * Caso os modelo não sejam fornecidos, são instanciados objetos padrão para cada um.
     *
     * @param BilheteDezena|null $bilheteDezenaModel O modelo de BilheteDezena a ser utilizado (opcional).
     */
    public function __construct(
        BilheteDezena $bilheteDezenaModel = null,
    ) {
        $this->bilheteDezenaModel = $bilheteDezenaModel ?? new BilheteDezena();
    }

    /**
     * Recupera dados e exibe no formato JSON.
     *
     * @return void
     */
    public function index()
    {
        $resultset = $this->bilheteDezenaModel->index();

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }

    /**
     * Recupera dados específico pelo ID e exibe em formato JSON.
     *
     * @param int $id_bilhete O ID a ser recuperado.
     * @return void
     */
    public function show($id_bilhete)
    {
        $resultset = $this->bilheteDezenaModel->show($id_bilhete);

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }

    /**
     * Cria um novo registro com os dados fornecidos e retorna a exibe em formato JSON.
     *
     * @param array $data Dados a serem criado.
     * @return void
     */
    public function store($data)
    {
        $resultset = $this->bilheteDezenaModel->create($data);

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }

    /**
     * 
     */
    public function update($data, $id_bilhete_dezena) {}

    /**
     * Deleta um registro específico pelo ID e retorna a resposta em formato JSON.
     *
     * @param int $id_bilhete_dezena O ID do registro a ser deletado.
     * @return void
     */
    public function delete($id_bilhete_dezena, $dezena)
    {
        $resultset = $this->bilheteDezenaModel->delete($id_bilhete_dezena, $dezena);

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }
}
