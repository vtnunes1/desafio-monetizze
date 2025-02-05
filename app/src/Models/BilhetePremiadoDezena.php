<?php

namespace App\Models;

use App\Database;

class BilhetePremiadoDezena
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
     * Recupera todos os bilhetes e suas respectivas dezenas.
     *
     * @return array Retorna um array com o status da operação, a mensagem e os dados dos bilhetes premiados e suas dezenas.
     */
    public function index()
    {
        try {
            //** GET DADOS **//
            $sql = "SELECT 
            bpd.dezena,
            bp.id_bilhete_premiado,
            bp.criado_em
            FROM tb_bilhetes_premiados_dezenas bpd
            RIGHT JOIN tb_bilhetes_premiados bp ON bp.id_bilhete_premiado = bpd.id_bilhete_premiado";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if ($resultset) {
                //** SUCCESS **// 
                $bilhetes = [];

                //** AGRUPA AS DEZENAS POR BILHETE **//
                foreach ($resultset as $row) {
                    if (!isset($bilhetes[$row['id_bilhete_premiado']])) {
                        $bilhetes[$row['id_bilhete_premiado']] = [
                            "dezenas" => [],
                            "bilhete" => [
                                "id_bilhete_premiado" => $row['id_bilhete_premiado'],
                                "criado_em" => $row['criado_em']
                            ]
                        ];
                    }
                    $bilhetes[$row['id_bilhete_premiado']]['dezenas'][] = $row['dezena'];
                }

                //** RETURN **//
                return [
                    "status" => 200,
                    "message" => "Dezenas encontradas com sucesso",
                    "data" => array_values($bilhetes)
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 404,
                    "message" => "Nenhum bilhete premiado encontrado",
                ];
            }
        } catch (\PDOException $e) {
            //** EXCEPTION **// 
            return [
                "status" => 500,
                "message" => "Erro ao consultar dezenas",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * 
     */
    public function show($id_bilhete_premiado) {}

    /**
     * Insere uma nova dezena em um bilhete premiado existente.
     *
     * @param array $data Dados da dezena a ser inserida.
     * @return array Retorna um array com o status da operação, a mensagem e os dados do bilhete premiado e dezena inseridos.
     */
    public function create($data)
    {
        try {
            //** INSERE DADOS **//
            $sql = "INSERT INTO tb_bilhetes_premiados_dezenas (id_bilhete_premiado, dezena) VALUES (:id_bilhete_premiado, :dezena)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_bilhete_premiado', $data['id_bilhete_premiado'], \PDO::PARAM_INT);
            $stmt->bindParam(':dezena', $data['dezena'], \PDO::PARAM_INT);
            $resultset = $stmt->execute();

            if ($resultset) {

                //** RECUPERA DADOS **//
                $lastInsertId = $this->pdo->lastInsertId();

                $sql = "SELECT 
                bpd.dezena,
                bp.id_bilhete_premiado,
                bp.criado_em
                FROM tb_bilhetes_premiados_dezenas bpd
                RIGHT JOIN tb_bilhetes_premiados bp ON bp.id_bilhete_premiado = bpd.id_bilhete_premiado
                WHERE bp.id_bilhete_premiado = :id_bilhete_premiado";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id_bilhete_premiado', $data['id_bilhete_premiado'], \PDO::PARAM_INT);
                $stmt->execute();

                $resultset = $stmt->fetch(\PDO::FETCH_ASSOC);

                return [
                    "status" => 200,
                    "message" => "Dezena inserida com sucesso",
                    "data" => [
                        "dezena" => $resultset['dezena'],
                        "bilhete" => [
                            "id_bilhete_premiado" => $resultset['id_bilhete_premiado'],
                            "criado_em" => $resultset['criado_em']
                        ]
                    ]
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 400,
                    "message" => "Erro ao inserir dezena",
                ];
            }
        } catch (\PDOException $e) {
            //** EXCEPTION **//  
            return [
                "status" => 500,
                "message" => "Erro ao inserir dezena",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * 
     */
    public function update($data, $id_bilhete_premiado) {}

    /**
     * Remove uma dezena específica de um bilhete.
     *
     * @param int $id_bilhete_premiado O ID do bilhete premiado do qual a dezena será removida.
     * @param int $dezena O valor da dezena a ser removida.
     * @return array Retorna um array com o status da operação, a mensagem e o resultado da exclusão.
     */
    public function delete($id_bilhete_premiado, $dezena)
    {
        try {
            //** DELETA DADOS **//
            $sql = "DELETE FROM tb_bilhetes_premiados_dezenas WHERE id_bilhete_premiado = :id_bilhete_premiado AND dezena = :dezena";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_bilhete_premiado', $id_bilhete_premiado, \PDO::PARAM_INT);
            $stmt->bindParam(':dezena', $dezena, \PDO::PARAM_INT);
            $resultset = $stmt->execute();

            if ($stmt->rowCount() > 0) {
                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Dezena removida com sucesso",
                    "data" => $resultset
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 400,
                    "message" => "Erro ao remover dezena",
                ];
            }
        } catch (\PDOException $e) {
            //** EXCEPTION **// 
            return [
                "status" => 500,
                "message" => "Erro ao remover dezena",
                "details" => $e->getMessage()
            ];
        }
    }
}
