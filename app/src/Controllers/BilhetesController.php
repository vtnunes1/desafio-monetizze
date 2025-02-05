<?php

namespace App\Controllers;

use App\Models\Bilhete;
use App\Models\BilheteDezena;
use App\Models\Tripulante;

class BilhetesController
{
    /**
     * Modelo responsável pela manipulação de bilhetes.
     *
     * @var Bilhete
     */
    protected $bilheteModel;

    /**
     * Modelo responsável pela manipulação das dezenas de bilhetes.
     *
     * @var BilheteDezena
     */
    protected $bilheteDezenaModel;

    /**
     * Modelo responsável pela manipulação de tripulantes.
     *
     * @var Tripulante
     */
    protected $tripulanteModel;

    /**
     * Construtor da classe, responsável por inicializar os modelos de Bilhete, BilheteDezena e Tripulante.
     * Caso os modelos não sejam fornecidos, são instanciados objetos padrão para cada um.
     *
     * @param Bilhete|null $bilheteModel O modelo de Bilhete a ser utilizado (opcional).
     * @param BilheteDezena|null $bilheteDezenaModel O modelo de BilheteDezena a ser utilizado (opcional).
     * @param Tripulante|null $tripulanteModel O modelo de Tripulante a ser utilizado (opcional).
     */
    public function __construct(
        Bilhete $bilheteModel = null,
        BilheteDezena $bilheteDezenaModel = null,
        Tripulante $tripulanteModel = null
    ) {
        $this->bilheteModel = $bilheteModel ?? new Bilhete();
        $this->bilheteDezenaModel = $bilheteDezenaModel ?? new BilheteDezena();
        $this->tripulanteModel = $tripulanteModel ?? new Tripulante();
    }

    /**
     * Recupera dados e exibe no formato JSON.
     *
     * @return void
     */
    public function index()
    {
        $resultset = $this->bilheteModel->index();

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
        $resultset = $this->bilheteModel->show($id_bilhete);

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
        $resultset = $this->bilheteModel->create($data);

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }

    /**
     * Atualiza um registro específico e exibe a resposta em formato JSON.
     *
     * @param array $data Dados atualizados.
     * @param int $id_bilhete O ID do registro a ser atualizado.
     * @return void
     */
    public function update($data, $id_bilhete)
    {
        $resultset = $this->bilheteModel->update($data, $id_bilhete);

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }

    /**
     * Deleta um registro específico pelo ID e retorna a resposta em formato JSON.
     *
     * @param int $id_bilhete O ID do registro a ser deletado.
     * @return void
     */
    public function delete($id_bilhete)
    {
        $resultset = $this->bilheteModel->delete($id_bilhete);

        header('Content-Type: application/json');
        echo json_encode($resultset);;
    }

    /**
     * Gera um conjunto de números aleatórios para um bilhete.
     *
     * @param int $dezenas O número de dezenas a serem selecionadas para o bilhete.
     * @return array Um array contendo os números sorteados, ordenados em ordem crescente.
     */
    public function gerarNumerosBilhete($dezenas)
    {
        //** GERA ARRAY COM NUMEROS **//
        $numeros = range(1, 60);

        //** EMBARALHA ARRAY **//
        shuffle($numeros);

        //** PEGA PRIMEIRAS POSICOES DO ARRAY **//
        $bilhete = array_slice($numeros, 0, $dezenas);

        //** ORDENA NUMEROS **//
        sort($bilhete);

        //** RETURN **//
        return $bilhete;
    }

    /**
     * Realiza a validação dos parâmetros para a geração de bilhetes.
     *
     * @param int $quantidade A quantidade de bilhetes a serem gerados.
     * @param int $dezenas A quantidade de dezenas por bilhete (entre 6 e 10).
     * @param int $id_tripulante O ID do tripulante que está gerando os bilhetes.
     * 
     * @return array Retorna um array com o status da operação, contendo um campo `error` com os erros, ou `success` com valor `true` caso não haja erros.
     */
    public function validacao($quantidade, $dezenas, $id_tripulante)
    {
        $erros = [];

        //** VALIDA QUANTIDADES **//
        if ($quantidade < 1 || $quantidade > 50) {
            $erros[] = "Quantidade de bilhetes deve estar entre 1 e 50;";
        }

        //** VALIDA DEZENAS **//
        if ($dezenas < 6 || $dezenas > 10) {
            $erros[] = "Quantidade de dezenas deve estar entre 6 e 10;";
        }

        //** VALIDA TRIPULANTE **//
        $resultset = $this->tripulanteModel->show($id_tripulante);

        if ($resultset['status'] != 200) {
            $erros[] = "Tripulante não encontrado;";
        } else {

            //** VALIDA QTD BILHETES TRIPULANTE **//
            $resultset = $this->bilheteModel->getQtdBilhetesByTripulante($id_tripulante);

            if ((50 - ($quantidade + $resultset['qtd'])) < 0) {
                if ((50 - $resultset['qtd']) == 0) {
                    $erros[] = "Tripulante não pode gerar mais bilhetes";
                } else {
                    $erros[] = "Tripulante só pode gerar mais " . (50 - $resultset['qtd']) . " bilhetes;";
                }
            }
        }
        //** RETURN **//
        if (!empty($erros)) {
            return ["error" => $erros];
        }
        return ["success" => true];
    }

    /**
     * Gera bilhetes de sorteio e associa números aleatórios a cada bilhete.
     *
     * @param int $quantidade A quantidade de bilhetes a serem gerados.
     * @param int $dezenas A quantidade de dezenas por bilhete (entre 6 e 10).
     * @param int $id_tripulante O ID do tripulante que está gerando os bilhetes.
     * 
     * @return array Retorna um array com o status da operação, contendo um campo `error` com os erros, ou `success` com uma mensagem de sucesso caso a operação seja concluída corretamente.
     */

    public function gerarBilhetes($quantidade, $dezenas, $id_tripulante)
    {
        //** VALIDA DADOS **//
        $resultado_validacao = $this->validacao($quantidade, $dezenas, $id_tripulante);
        if (isset($resultado_validacao['error']) && !empty($resultado_validacao['error'])) {

            return [
                "error" => implode("<br>", $resultado_validacao['error'])
            ];
        } else {

            try {

                for ($i = 0; $i < $quantidade; $i++) {

                    //** CRIAR BILHETE **//
                    $bilhete = $this->bilheteModel->create(['id_tripulante' => $id_tripulante]);

                    //** GERA NUMEROS BILHETE **//
                    $bilheteNumeros = $this->gerarNumerosBilhete($dezenas);

                    //** ASSOCIA NUMEROS AO BILHETE **//
                    foreach ($bilheteNumeros as $numero) {
                        $arrayBilheteDezena = array(
                            "id_bilhete" => $bilhete['data']['id_bilhete'],
                            "dezena" => $numero
                        );

                        $bilheteDezena = $this->bilheteDezenaModel->create($arrayBilheteDezena);
                    }
                }
                //** RETURN **//
                return [
                    "success" => "Bilhetes cadastrados com suceso"
                ];
            } catch (\PDOException $e) {
                //** EXCEPTION **//
                return [
                    "error" => "Falha ao gerar bilhetes"
                ];
            }
        }
    }

    /**
     * Remove todos os bilhetes cadastrados.
     *
     * @return array Retorna um array com o status da operação, contendo um campo `error` com os erros, ou `success` com uma mensagem de sucesso caso a operação seja concluída corretamente.
     */

    public function removerBilhetes()
    {
        try {
            //** REMOVE DADOS **//
            $resultset = $this->bilheteModel->truncate();

            //** RETURN **//
            if ($resultset['status'] === 200) {
                return ["success" => "Bilhetes removidos com sucesso"];
            } else if ($resultset['status'] === 400) {
                return ["error" => "Nenhum bilhete encontrado para remoção"];
            } else {
                return ["error" => "Falha ao remover bilhetes"];
            }
        } catch (\PDOException $e) {
            //** EXCEPTION **//
            return [
                "error" => "Falha ao remover bilhetes"
            ];
        }
    }
}
