<?php

namespace App\Models;

use App\Database;

class Tripulante
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
     * Recupera todos os tripulantes.
     *
     * @return array Retorna um array com o status da operação, a mensagem e os dados dos tripulantes.
     */

    public function index()
    {
        try {
            //** GET DADOS **//
            $sql = "SELECT * FROM tb_tripulantes";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if ($resultset) {
                return [
                    "status" => 200,
                    "message" => "Tripulantes encontrados com sucesso",
                    "data" => $resultset
                ];
            } else {
                return [
                    "status" => 404,
                    "message" => "Nenhum tripulante encontrado",
                ];
            }
        } catch (\PDOException $e) {
            return [
                "status" => 500,
                "message" => "Erro ao consultar tripulantes",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * Recupera um tripulante específico, baseado no ID fornecido.
     *
     * @param int $id_tripulante O ID do tripulante a ser recuperado.
     * @return array Retorna um array com o status da operação, a mensagem e os dados do tripulante.
     */
    public function show($id_tripulante)
    {

        try {
            //** GET DADOS **//
            $sql = "SELECT * FROM tb_tripulantes WHERE id_tripulante = :id_tripulante";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_tripulante', $id_tripulante, \PDO::PARAM_INT);  // Corrigido: \PDO em vez de PDO
            $stmt->execute();

            $resultset = $stmt->fetch(\PDO::FETCH_ASSOC);  // Corrigido: \PDO em vez de PDO

            if ($resultset) {
                return [
                    "status" => 200,
                    "message" => "Tripulante encontrado com sucesso",
                    "data" => $resultset
                ];
            } else {
                return [
                    "status" => 404,
                    "message" => "Tripulante não encontrado",
                ];
            }
        } catch (\PDOException $e) {  // Corrigido: \PDOException em vez de PDOException
            return [
                "status" => 500,
                "message" => "Erro ao consultar tripulante",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * Insere um novo tripulante e retorna os dados inseridos.
     *
     * @param array $data Dados do tripulante a ser inserido.
     * @return array Retorna um array com o status da operação, a mensagem e os dados do tripulante inserido.
     */
    public function create($data)
    {
        try {
            //** INSERE DADOS **//
            $sql = "INSERT INTO tb_tripulantes (nome, email) VALUES (:nome, :email)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nome', $data['nome'], \PDO::PARAM_STR);  // Corrigido: \PDO em vez de PDO
            $stmt->bindParam(':email', $data['email'], \PDO::PARAM_STR);  // Corrigido: \PDO em vez de PDO
            $resultset = $stmt->execute();

            if ($resultset) {
                $lastInsertId = $this->pdo->lastInsertId();

                $sql = "SELECT * FROM tb_tripulantes WHERE id_tripulante = :id_tripulante";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id_tripulante', $lastInsertId, \PDO::PARAM_INT);  // Corrigido: \PDO em vez de PDO
                $stmt->execute();

                $insertedData = $stmt->fetch(\PDO::FETCH_ASSOC);  // Corrigido: \PDO em vez de PDO

                return [
                    "status" => 200,
                    "message" => "Tripulante inserido com sucesso",
                    "data" => $insertedData
                ];
            } else {
                return [
                    "status" => 400,
                    "message" => "Erro ao inserir tripulante",
                ];
            }
        } catch (\PDOException $e) {  // Corrigido: \PDOException em vez de PDOException
            return [
                "status" => 500,
                "message" => "Erro ao inserir tripulante",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * Atualiza os dados de um tripulante específico com base no ID fornecido.
     *
     * @param array $data Dados do tripulante a ser atualizado.
     * @param int $id_tripulante O ID do tripulante a ser atualizado.
     * @return array Retorna um array com o status da operação, a mensagem e os dados do tripulante atualizado.
     */
    public function update($data, $id_tripulante)
    {
        try {
            $sql = "UPDATE tb_tripulantes SET nome = :nome, email = :email WHERE id_tripulante = :id_tripulante;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_tripulante', $id_tripulante, \PDO::PARAM_INT);
            $stmt->bindParam(':nome', $data['nome'], \PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], \PDO::PARAM_STR);
            $resultset = $stmt->execute();

            if ($resultset) {
                $sql = "SELECT * FROM tb_tripulantes WHERE id_tripulante = :id_tripulante";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id_tripulante', $id_tripulante, \PDO::PARAM_INT);
                $stmt->execute();

                $updatedData = $stmt->fetch(\PDO::FETCH_ASSOC);
                return [
                    "status" => 200,
                    "message" => "Tripulante atualizado com sucesso",
                    "data" => $updatedData
                ];
            } else {
                return [
                    "status" => 400,
                    "message" => "Erro ao atualizar tripulante",
                ];
            }
        } catch (\PDOException $e) {
            return [
                "status" => 500,
                "message" => "Erro ao atualizar tripulante",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * Remove um tripulante específico baseado no ID fornecido.
     *
     * @param int $id_tripulante O ID do tripulante a ser removido.
     * @return array Retorna um array com o status da operação, a mensagem e o resultado da operação de exclusão.
     */
    public function delete($id_tripulante)
    {
        try {
            $sql = "DELETE FROM tb_tripulantes WHERE id_tripulante = :id_tripulante";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_tripulante', $id_tripulante, \PDO::PARAM_INT);
            $resultset = $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return [
                    "status" => 200,
                    "message" => "Tripulante removido com sucesso",
                    "data" => $resultset
                ];
            } else {
                return [
                    "status" => 400,
                    "message" => "Erro ao remover tripulante",
                ];
            }
        } catch (\PDOException $e) {
            return [
                "status" => 500,
                "message" => "Erro ao remover tripulante",
                "details" => $e->getMessage()
            ];
        }
    }
}
