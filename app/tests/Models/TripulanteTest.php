<?php

require_once __DIR__ . '/../Database.php';

class Tripulante
{

    protected $pdo;

    /**
     * 
     */
    function __construct()
    {

        $connection = new Database();
        $this->pdo = $connection->connect();
    }

    /**
     * 
     */
    public function index()
    {
        try {
            //** GET DADOS **//
            $sql = "SELECT * FROM tb_tripulantes";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $resultset = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($resultset) {
                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Tripulantes encontrados com sucesso",
                    "data" => $resultset
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 404,
                    "message" => "Nenhum tripulante encontrado",
                ];
            }
        } catch (PDOException $e) {
            //** EXCEPTION **// 
            return [
                "status" => 500,
                "message" => "Erro ao consultar tripulantes",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * 
     */
    public function show($id_tripulante)
    {
        try {
            //** GET DADOS **//
            $sql = "SELECT * FROM tb_tripulantes WHERE id_tripulante = :id_tripulante";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_tripulante', $id_tripulante, PDO::PARAM_INT);
            $stmt->execute();

            $resultset = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultset) {
                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Tripulante encontrado com sucesso",
                    "data" => $resultset
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 404,
                    "message" => "Tripulante não encontrado",
                ];
            }
        } catch (PDOException $e) {
            //** EXCEPTION **// 
            return [
                "status" => 500,
                "message" => "Erro ao consultar tripulante",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * 
     */
    public function create($data)
    {
        try {
            //** INSERE DADOS **//
            $sql = "INSERT INTO tb_tripulantes (nome, email) VALUES (:nome, :email)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nome', $data['nome'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $resultset = $stmt->execute();

            if ($resultset) {

                //** RECUPERA DADOS **//
                $lastInsertId = $this->pdo->lastInsertId();

                $sql = "SELECT * FROM tb_tripulantes WHERE id_tripulante = :id_tripulante";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id_tripulante', $lastInsertId, PDO::PARAM_INT);
                $stmt->execute();

                $insertedData = $stmt->fetch(PDO::FETCH_ASSOC);

                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Tripulante inserido com sucesso",
                    "data" => $insertedData
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 400,
                    "message" => "Erro ao inserir tripulante",
                ];
            }
        } catch (PDOException $e) {
            //** EXCEPTION **//  
            return [
                "status" => 500,
                "message" => "Erro ao inserir tripulante",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * 
     */
    public function update($data, $id_tripulante)
    {
        try {
            //** ATUALIZA DADOS **//
            $sql = "UPDATE tb_tripulantes SET nome = :nome, email = :email WHERE id_tripulante = :id_tripulante;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_tripulante', $id_tripulante, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $data['nome'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $resultset = $stmt->execute();

            if ($resultset) {

                //** RECUPERA DADOS **//
                $sql = "SELECT * FROM tb_tripulantes WHERE id_tripulante = :id_tripulante";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id_tripulante', $id_tripulante, PDO::PARAM_INT);
                $stmt->execute();

                $updatedData = $stmt->fetch(PDO::FETCH_ASSOC);

                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Tripulante atualizado com sucesso",
                    "data" => $updatedData
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 400,
                    "message" => "Erro ao atualizar tripulante",
                ];
            }
        } catch (PDOException $e) {
            //** EXCEPTION **//  
            return [
                "status" => 500,
                "message" => "Erro ao atualizar tripulante",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * 
     */
    public function delete($id_tripulante)
    {
        try {
            //** DELETA DADOS **//
            $sql = "DELETE FROM tb_tripulantes WHERE id_tripulante = :id_tripulante";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_tripulante', $id_tripulante, PDO::PARAM_INT);
            $resultset = $stmt->execute();

            if ($stmt->rowCount() > 0) {
                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Tripulante removido com sucesso",
                    "data" => $resultset
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 400,
                    "message" => "Erro ao remover tripulante",
                ];
            }
        } catch (PDOException $e) {
            //** EXCEPTION **// 
            return [
                "status" => 500,
                "message" => "Erro ao remover tripulante",
                "details" => $e->getMessage()
            ];
        }
    }
}
