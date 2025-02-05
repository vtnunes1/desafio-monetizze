<?php

namespace App\Models;

use App\Database;

class BilhetePremiado
{
    /**
     * @var \PDO Objeto que representa a conexão com o banco de dados.
     */
    protected $pdo;

    /**
     * Construtor da classe, responsavel por estabelecer uma conexão com o banco de dados.
     */
    function __construct()
    {
        $connection = new Database();
        $this->pdo = $connection->connect();
    }

    /**
     * Recupera todos os bilhetes premiados.
     *
     * @return array Retorna um array contendo o status da operação, a mensagem e os dados dos bilhetes premiados.
     */
    public function index()
    {
        try {
            //** GET DADOS **//
            $sql = "SELECT 
            *
            FROM tb_bilhetes_premiados bp";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if ($resultset) {
                //** SUCCESS **//
                return [
                    "status" => 200,
                    "message" => "Bilhetes encontrados com sucesso",
                    "data" => $resultset
                ];
            } else {
                //** ERROR **//
                return [
                    "status" => 404,
                    "message" => "Nenhum bilhete encontrado",
                ];
            }
        } catch (\PDOException $e) {
            //** EXCEPTION **//
            return [
                "status" => 500,
                "message" => "Erro ao consultar bilhetes",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * 
     */
    public function show($id_bilhete_premiado) {}

    /**
     * Insere um novo bilhete premiado e retorna os dados inseridos.
     *
     * @param array $data Dados do bilhete premiado a ser inserido.
     * @return array Retorna um array com o status da operação, a mensagem e os dados do bilhete premiado inserido.
     */
    public function create()
    {
        try {
            //** INSERE DADOS **//
            $sql = "INSERT INTO tb_bilhetes_premiados () VALUES ()";
            $stmt = $this->pdo->prepare($sql);
            $resultset = $stmt->execute();

            if ($resultset) {

                //** RECUPERA DADOS **//
                $lastInsertId = $this->pdo->lastInsertId();

                $sql = "SELECT * FROM tb_bilhetes_premiados WHERE id_bilhete_premiado = :id_bilhete_premiado";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id_bilhete_premiado', $lastInsertId, \PDO::PARAM_INT);
                $stmt->execute();

                $resultset = $stmt->fetch(\PDO::FETCH_ASSOC);

                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Bilhete premiado inserido com sucesso",
                    "data" => [
                        "id_bilhete_premiado" => $resultset['id_bilhete_premiado'],
                        "criado_em" => $resultset['criado_em']
                    ]
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 400,
                    "message" => "Erro ao inserir bilhete premiado",
                ];
            }
        } catch (\PDOException $e) {
            //** EXCEPTION **//  
            return [
                "status" => 500,
                "message" => "Erro ao inserir bilhete premiado",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * 
     */
    public function update($data, $id_bilhete_premiado) {}

    /**
     * Remove todos os bilhetes premiados registrados.
     *
     * @return array Retorna um array com o status da operação, a mensagem e o resultado da operação de exclusão.
     */
    public function delete()
    {
        try {
            //** DELETA DADOS **//
            $sql = "DELETE FROM tb_bilhetes_premiados";
            $stmt = $this->pdo->prepare($sql);
            $resultset = $stmt->execute();

            if ($stmt->rowCount() > 0) {
                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Bilhete premiado removido com sucesso",
                    "data" => $resultset
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 400,
                    "message" => "Erro ao remover bilhete premiado",
                ];
            }
        } catch (\PDOException $e) {
            //** EXCEPTION **// 
            return [
                "status" => 500,
                "message" => "Erro ao remover bilhete premiado",
                "details" => $e->getMessage()
            ];
        }
    }
}
