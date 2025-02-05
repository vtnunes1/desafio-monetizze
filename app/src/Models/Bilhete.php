<?php

namespace App\Models;

use App\Database;

class Bilhete
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
     * Recupera todos os bilhetes e seus respectivos tripulantes.
     *
     * @return array Retorna um array contendo o status da operação, a mensagem e os dados dos bilhetes.
     */
    public function index()
    {
        try {
            //** GET DADOS **//
            $sql = "SELECT 
            b.id_bilhete,
            b.criado_em,
            t.id_tripulante,
            t.nome AS nome_tripulante,
            t.email AS email_tripulante
            FROM tb_bilhetes b 
            JOIN tb_tripulantes t ON t.id_tripulante = b.id_tripulante";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if ($resultset) {
                //** SUCCESS **//
                $bilhetes = [];

                foreach ($resultset as $row) {
                    if (!isset($bilhetes[$row['id_bilhete']])) {
                        $bilhetes[$row['id_bilhete']] = [
                            'id_bilhete' => $row['id_bilhete'],
                            'criado_em' => $row['criado_em'],
                            'tripulante' => [
                                'id' => $row['id_tripulante'],
                                'nome' => $row['nome_tripulante'],
                                'email' => $row['email_tripulante']
                            ]
                        ];
                    }
                }

                $bilhetes = array_values($bilhetes);

                return [
                    "status" => 200,
                    "message" => "Bilhetes encontrados com sucesso",
                    "data" => $bilhetes
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
     * Recupera um bilhete específico e seus dados relacionados, baseado no ID fornecido.
     *
     * @param int $id_bilhete O ID do bilhete a ser recuperado.
     * @return array Retorna um array com o status da operação, a mensagem e os dados do bilhete.
     */
    public function show($id_bilhete)
    {
        try {
            //** GET DADOS **//
            $sql = "SELECT 
            b.id_bilhete,
            b.id_tripulante,
            b.criado_em,
            t.nome AS nome_tripulante,
            t.email AS email_tripulante
            FROM tb_bilhetes b 
            JOIN tb_tripulantes t ON t.id_tripulante = b.id_tripulante 
            WHERE id_bilhete = :id_bilhete";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_bilhete', $id_bilhete, \PDO::PARAM_INT);
            $stmt->execute();

            $resultset = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($resultset) {
                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Bilhete encontrado com sucesso",
                    "data" => [
                        "id_bilhete" => $resultset['id_bilhete'],
                        "criado_em" => $resultset['criado_em'],
                        "tripulante" => [
                            "id" => $resultset['id_tripulante'],
                            "nome" => $resultset['nome_tripulante'],
                            "email" => $resultset['email_tripulante']
                        ]
                    ]
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 404,
                    "message" => "Bilhete não encontrado",
                ];
            }
        } catch (\PDOException $e) {
            //** EXCEPTION **// 
            return [
                "status" => 500,
                "message" => "Erro ao consultar bilhete",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * Insere um novo bilhete e retorna os dados inseridos.
     *
     * @param array $data Dados do bilhete a ser inserido.
     * @return array Retorna um array com o status da operação, a mensagem e os dados do bilhete inserido.
     */
    public function create($data)
    {
        try {
            //** INSERE DADOS **//
            $sql = "INSERT INTO tb_bilhetes (id_tripulante) VALUES (:id_tripulante)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_tripulante', $data['id_tripulante'], \PDO::PARAM_INT);
            $resultset = $stmt->execute();

            if ($resultset) {

                //** RECUPERA DADOS **//
                $lastInsertId = $this->pdo->lastInsertId();

                $sql = "SELECT 
                b.id_bilhete,
                b.id_tripulante,
                b.criado_em,
                t.nome AS nome_tripulante,
                t.email AS email_tripulante
                FROM tb_bilhetes b 
                JOIN tb_tripulantes t ON t.id_tripulante = b.id_tripulante 
                WHERE id_bilhete = :id_bilhete";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id_bilhete', $lastInsertId, \PDO::PARAM_INT);
                $stmt->execute();

                $resultset = $stmt->fetch(\PDO::FETCH_ASSOC);

                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Bilhete inserido com sucesso",
                    "data" => [
                        "id_bilhete" => $resultset['id_bilhete'],
                        "criado_em" => $resultset['criado_em'],
                        "tripulante" => [
                            "id" => $resultset['id_tripulante'],
                            "nome" => $resultset['nome_tripulante'],
                            "email" => $resultset['email_tripulante']
                        ]
                    ]
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 400,
                    "message" => "Erro ao inserir bilhete",
                ];
            }
        } catch (\PDOException $e) {
            //** EXCEPTION **//  
            return [
                "status" => 500,
                "message" => "Erro ao inserir bilhete",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * Atualiza os dados de um bilhete específico com base no ID fornecido.
     *
     * @param array $data Dados do bilhete a ser atualizado.
     * @param int $id_bilhete O ID do bilhete a ser atualizado.
     * @return array Retorna um array com o status da operação, a mensagem e os dados do bilhete atualizado.
     */
    public function update($data, $id_bilhete)
    {
        try {
            //** ATUALIZA DADOS **//
            $sql = "UPDATE tb_bilhetes SET id_tripulante = :id_tripulante WHERE id_bilhete = :id_bilhete;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_bilhete', $id_bilhete, \PDO::PARAM_INT);
            $stmt->bindParam(':id_tripulante', $data['id_tripulante'], \PDO::PARAM_INT);
            $resultset = $stmt->execute();

            if ($resultset) {

                //** RECUPERA DADOS **//
                $sql = "SELECT 
                b.id_bilhete,
                b.id_tripulante,
                b.criado_em,
                t.nome AS nome_tripulante,
                t.email AS email_tripulante
                FROM tb_bilhetes b 
                JOIN tb_tripulantes t ON t.id_tripulante = b.id_tripulante 
                WHERE id_bilhete = :id_bilhete";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id_bilhete', $id_bilhete, \PDO::PARAM_INT);
                $stmt->execute();

                $resultset = $stmt->fetch(\PDO::FETCH_ASSOC);

                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Bilhete atualizado com sucesso",
                    "data" => [
                        "id_bilhete" => $resultset['id_bilhete'],
                        "criado_em" => $resultset['criado_em'],
                        "tripulante" => [
                            "id" => $resultset['id_tripulante'],
                            "nome" => $resultset['nome_tripulante'],
                            "email" => $resultset['email_tripulante']
                        ]
                    ]
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 400,
                    "message" => "Erro ao atualizar bilhete",
                ];
            }
        } catch (\PDOException $e) {
            //** EXCEPTION **//  
            return [
                "status" => 500,
                "message" => "Erro ao atualizar bilhete",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * Remove um bilhete específico baseado no ID fornecido.
     *
     * @param int $id_bilhete O ID do bilhete a ser removido.
     * @return array Retorna um array com o status da operação, a mensagem e o resultado da operação de exclusão.
     */
    public function delete($id_bilhete)
    {
        try {
            //** DELETA DADOS **//
            $sql = "DELETE FROM tb_bilhetes WHERE id_bilhete = :id_bilhete";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_bilhete', $id_bilhete, \PDO::PARAM_INT);
            $resultset = $stmt->execute();

            if ($stmt->rowCount() > 0) {
                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Bilhete removido com sucesso",
                    "data" => $resultset
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 400,
                    "message" => "Erro ao remover bilhete",
                ];
            }
        } catch (\PDOException $e) {
            //** EXCEPTION **// 
            return [
                "status" => 500,
                "message" => "Erro ao remover bilhete",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * Recupera a quantidade de bilhetes associados a um tripulante específico.
     *
     * @param int $id_tripulante O ID do tripulante para o qual a quantidade de bilhetes será contada.
     * @return array Retorna um array contendo a quantidade de bilhetes.
     */

    public function getQtdBilhetesByTripulante($id_tripulante)
    {
        //** GET DADOS **//
        $sql = "SELECT 
        COUNT(*) AS qtd
        FROM tb_bilhetes
        WHERE id_tripulante = :id_tripulante";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_tripulante', $id_tripulante, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Remove todos os bilhetes.
     *
     * @return array Retorna um array com o status da operação, a mensagem e o resultado da exclusão.
     */

    public function truncate()
    {
        try {
            //** DELETA DADOS **//
            $sql = "DELETE FROM tb_bilhetes";
            $stmt = $this->pdo->prepare($sql);
            $resultset = $stmt->execute();

            if ($stmt->rowCount() > 0) {
                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Bilhetes removidos com sucesso",
                    "data" => $resultset
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 400,
                    "message" => "Nenhum bilhete encontrado para remoção",
                ];
            }
        } catch (\PDOException $e) {
            //** EXCEPTION **// 
            return [
                "status" => 500,
                "message" => "Falha ao remover bilhetes",
                "details" => $e->getMessage()
            ];
        }
    }
}
