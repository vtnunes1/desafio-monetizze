<?php

namespace App\Controllers;

use App\Models\BilhetePremiadoDezena;

class BilhetesPremiadosDezenasController
{
    /**
     * Modelo responsável pela manipulação das dezenas de bilhetes premiados.
     *
     * @var BilhetePremiadoDezena
     */
    protected $bilhetePremiadoDezenaModel;

    /**
     * Construtor da classe, responsável por inicializar os modelos de BilhetePremiadoDezena.
     * Caso os modelo não sejam fornecidos, são instanciados objetos padrão para cada um.
     *
     * @param BilhetePremiadoDezena|null $bilhetePremiadoDezenaModel O modelo de BilhetePremiadoDezena a ser utilizado (opcional).
     */
    public function __construct(
        BilhetePremiadoDezena $bilhetePremiadoDezenaModel = null,
    ) {
        $this->bilhetePremiadoDezenaModel = $bilhetePremiadoDezenaModel ?? new BilhetePremiadoDezena();
    }

    /**
     * Recupera dados e exibe no formato JSON.
     *
     * @return void
     */
    public function index()
    {
        $resultset = $this->bilhetePremiadoDezenaModel->index();

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }

    /**
     * Recupera dados específico pelo ID e exibe em formato JSON.
     *
     * @param int $id_bilhete_premiado O ID a ser recuperado.
     * @return void
     */
    public function show($id_bilhete_premiado) {}

    /**
     * Cria um novo registro com os dados fornecidos e retorna a exibe em formato JSON.
     *
     * @param array $data Dados a serem criado.
     * @return void
     */
    public function store($data)
    {
        $resultset = $this->bilhetePremiadoDezenaModel->create($data);

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }

    /**
     * 
     */
    public function update($data, $id_bilhete_premiado) {}

    /**
     * Deleta um registro específico pelo ID e retorna a resposta em formato JSON.
     *
     * @param int $id_bilhete_premiado O ID do registro a ser deletado.
     * @return void
     */
    public function delete($id_bilhete_premiado, $dezena)
    {
        $resultset = $this->bilhetePremiadoDezenaModel->delete($id_bilhete_premiado, $dezena);

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }
}
