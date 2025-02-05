<?php

namespace App\Controllers;

use App\Models\BilhetePremiado;
use App\Models\BilhetePremiadoDezena;

class BilhetesPremiadosController
{
    /**
     * Modelo responsável pela manipulação de bilhetes premiados.
     *
     * @var BilhetePremiado
     */
    protected $bilhetePremiadoModel;

    /**
     * Modelo responsável pela manipulação das dezenas de bilhetes premiados.
     *
     * @var BilhetePremiadoDezena
     */
    protected $bilhetePremiadoDezenaModel;

    /**
     * Construtor da classe, responsável por inicializar os modelos de BilhetePremiado, BilhetePremiadoDezena.
     * Caso os modelos não sejam fornecidos, são instanciados objetos padrão para cada um.
     *
     * @param BilhetePremiado|null $bilhetePremiadoModel O modelo de BilhetePremiado a ser utilizado (opcional).
     * @param BilhetePremiadoDezena|null $bilhetePremiadoDezenaModel O modelo de BilhetePremiadoDezena a ser utilizado (opcional).
     */
    public function __construct(
        BilhetePremiado $bilhetePremiadoModel = null,
        BilhetePremiadoDezena $bilhetePremiadoDezenaModel = null,
    ) {
        $this->bilhetePremiadoModel = $bilhetePremiadoModel ?? new BilhetePremiado();
        $this->bilhetePremiadoDezenaModel = $bilhetePremiadoDezenaModel ?? new BilhetePremiadoDezena();
    }

    /**
     * Recupera dados e exibe no formato JSON.
     *
     * @return void
     */
    public function index()
    {
        $resultset = $this->bilhetePremiadoModel->index();

        header('Content-Type: application/json');
        echo json_encode($resultset);
    }

    /**
     * 
     */
    public function show($id_bilhete_premiado) {}

    /**
     * Cria um novo registro com os dados fornecidos e retorna a exibe em formato JSON.
     *
     * @param array $data Dados a serem criado.
     * @return void
     */
    public function store()
    {
        $resultset = $this->bilhetePremiadoModel->create();

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
    public function delete($id_bilhete_premiado)
    {
        $resultset = $this->bilhetePremiadoModel->delete($id_bilhete_premiado);

        header('Content-Type: application/json');
        echo json_encode($resultset);;
    }

    /**
     * Gera um conjunto de 6 números aleatórios para um bilhete de sorteio.
     *
     * @return array Um array contendo 6 números sorteados, ordenados em ordem crescente.
     */
    public function gerarNumerosBilhete()
    {
        //** GERA ARRAY COM NUMEROS **//
        $numeros = range(1, 60);

        //** EMBARALHA ARRAY **//
        shuffle($numeros);

        //** PEGA 6 PRIMEIRAS POSICOES DO ARRAY **//
        $bilhete = array_slice($numeros, 0, 6);

        //** ORDENA NUMEROS **//
        sort($bilhete);

        //** RETURN **//
        return $bilhete;
    }

    /**
     * Gera um bilhete premiado com 6 números aleatórios.
     *
     * @return array Retorna um array com o status da operação, contendo um campo `error` com os erros, ou `success` com uma mensagem de sucesso caso a operação seja concluída corretamente.
     */
    public function gerarBilhetePremiado()
    {
        try {
            //** REMOVE BILHETE PREMIADO **//
            $delete = $this->bilhetePremiadoModel->delete();

            //** CRIAR BILHETE PREMIADO **//
            $bilhetePremiado = $this->bilhetePremiadoModel->create();

            //** GERA NUMEROS BILHETE **//
            $bilhetePremiadoNumeros = $this->gerarNumerosBilhete();

            //** ASSOCIA NUMEROS AO BILHETE PREMIADO **//
            foreach ($bilhetePremiadoNumeros as $numero) {
                $arrayBilhetePremiadoDezena = array(
                    "id_bilhete_premiado" => $bilhetePremiado['data']['id_bilhete_premiado'],
                    "dezena" => $numero
                );

                $bilhetesPremiadosDezenas = $this->bilhetePremiadoDezenaModel->create($arrayBilhetePremiadoDezena);
            }

            return [
                "success" => "Bilhete premiado gerado com suceso"
            ];
        } catch (\PDOException $e) {

            return [
                "error" => "Falha ao gerar bilhete premiado"
            ];
        }
    }
}
