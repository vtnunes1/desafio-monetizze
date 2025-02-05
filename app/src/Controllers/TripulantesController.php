<?php

namespace App\Controllers;

use App\Models\Tripulante;

class TripulantesController
{
    /**
     * Modelo responsável pela manipulação de tripulantes.
     *
     * @var Tripulante
     */
    protected $tripulanteModel;

    /**
     * Construtor da classe, responsável por inicializar os modelos de Tripulante.
     * Caso os modelos não sejam fornecidos, são instanciados objetos padrão para cada um.
     *
     * @param Tripulante|null $tripulanteModel O modelo de Tripulante a ser utilizado (opcional).
     */
    public function __construct(
        Tripulante $tripulanteModel = null
    ) {
        $this->tripulanteModel = $tripulanteModel ?? new Tripulante();
    }

    /**
     * Recupera dados e exibe no formato JSON.
     *
     * @return void
     */
    public function index()
    {
        $resultset = $this->tripulanteModel->index();

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }

    /**
     * Recupera dados específico pelo ID e exibe em formato JSON.
     *
     * @param int $id_tripulante O ID a ser recuperado.
     * @return void
     */
    public function show($id_tripulante)
    {
        $resultset = $this->tripulanteModel->show($id_tripulante);

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
        $resultset = $this->tripulanteModel->create($data);

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }

    /**
     * Atualiza um registro específico e exibe a resposta em formato JSON.
     *
     * @param array $data Dados atualizados.
     * @param int $id_tripulante O ID do registro a ser atualizado.
     * @return void
     */
    public function update($data, $id_tripulante)
    {
        $resultset = $this->tripulanteModel->update($data, $id_tripulante);

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }

    /**
     * Deleta um registro específico pelo ID e retorna a resposta em formato JSON.
     *
     * @param int $id_tripulante O ID do registro a ser deletado.
     * @return void
     */
    public function delete($id_tripulante)
    {
        $resultset = $this->tripulanteModel->delete($id_tripulante);

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }
}
