<?php

namespace App\Models;

use App\Database;

class BilheteDezena
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
     * Recupera todos os bilhetes e suas respectivas dezenas, juntamente com os dados dos tripulantes.
     *
     * @return array Retorna um array com o status da operação, a mensagem e os dados dos bilhetes e suas dezenas.
     */
    public function index()
    {
        try {
            //** GET DADOS **//
            $sql = "SELECT 
            bd.dezena,
            b.id_bilhete,
            b.criado_em,
            t.id_tripulante,
            t.nome AS nome_tripulante,
            t.email AS email_tripulante
            FROM tb_bilhetes_dezenas bd
            JOIN tb_bilhetes b ON b.id_bilhete = bd.id_bilhete 
            JOIN tb_tripulantes t ON t.id_tripulante = b.id_tripulante";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if ($resultset) {
                //** SUCCESS **// 
                $bilhetes = [];

                // Agrupando as dezenas por bilhete
                foreach ($resultset as $row) {
                    // Se o bilhete ainda não foi adicionado ao array, inicializa
                    if (!isset($bilhetes[$row['id_bilhete']])) {
                        $bilhetes[$row['id_bilhete']] = [
                            "dezenas" => [],
                            "bilhete" => [
                                "id_bilhete" => $row['id_bilhete'],
                                "criado_em" => $row['criado_em'],
                                "tripulante" => [
                                    "id" => $row['id_tripulante'],
                                    "nome" => $row['nome_tripulante'],
                                    "email" => $row['email_tripulante']
                                ]
                            ]
                        ];
                    }
                    // Adiciona a dezena ao bilhete
                    $bilhetes[$row['id_bilhete']]['dezenas'][] = $row['dezena'];
                }

                // Converte para um array para retornar como resposta
                return [
                    "status" => 200,
                    "message" => "Dezenas encontradas com sucesso",
                    "data" => array_values($bilhetes) // Garante que os bilhetes sejam um array e não um objeto
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
                "message" => "Erro ao consultar dezenas",
                "details" => $e->getMessage()
            ];
        }
    }

    /**
     * Recupera um bilhete específico e suas dezenas, juntamente com os dados do tripulante.
     *
     * @param int $id_bilhete O ID do bilhete a ser recuperado.
     * @return array Retorna um array com o status da operação, a mensagem e os dados do bilhete e suas dezenas.
     */
    public function show($id_bilhete)
    {
        try {
            //** GET DADOS **//
            $sql = "SELECT 
            bd.dezena,
            b.id_bilhete,
            b.criado_em,
            t.id_tripulante,
            t.nome AS nome_tripulante,
            t.email AS email_tripulante
            FROM tb_bilhetes_dezenas bd
            JOIN tb_bilhetes b ON b.id_bilhete = bd.id_bilhete 
            JOIN tb_tripulantes t ON t.id_tripulante = b.id_tripulante 
            WHERE b.id_bilhete = :id_bilhete";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_bilhete', $id_bilhete, \PDO::PARAM_INT);
            $stmt->execute();

            $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if ($resultset) {
                //** SUCCESS **// 
                return [
                    "status" => 200,
                    "message" => "Dezenas encontradas com sucesso",
                    "data" => [
                        "dezenas" => array_map(function ($row) {
                            return $row['dezena'];
                        }, $resultset),
                        "bilhete" => [
                            "id_bilhete" => $resultset[0]['id_bilhete'], // Utilizando o primeiro registro para pegar as informações do bilhete
                            "criado_em" => $resultset[0]['criado_em'],
                            "tripulante" => [
                                "id" => $resultset[0]['id_tripulante'], // Utilizando o primeiro registro para pegar as informações do tripulante
                                "nome" => $resultset[0]['nome_tripulante'],
                                "email" => $resultset[0]['email_tripulante']
                            ]
                        ]
                    ]
                ];
            } else {
                //** ERROR **// 
                return [
                    "status" => 404,
                    "message" => "Dezenas não encontradas",
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
     * Insere uma nova dezena em um bilhete existente.
     *
     * @param array $data Dados da dezena a ser inserida.
     * @return array Retorna um array com o status da operação, a mensagem e os dados do bilhete e dezena inseridos.
     */
    public function create($data)
    {
        try {
            //** INSERE DADOS **//
            $sql = "INSERT INTO tb_bilhetes_dezenas (id_bilhete, dezena) VALUES (:id_bilhete, :dezena)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_bilhete', $data['id_bilhete'], \PDO::PARAM_INT);
            $stmt->bindParam(':dezena', $data['dezena'], \PDO::PARAM_INT);
            $resultset = $stmt->execute();

            if ($resultset) {

                //** RECUPERA DADOS **//
                $lastInsertId = $this->pdo->lastInsertId();

                $sql = "SELECT 
                bd.dezena,
                b.id_bilhete,
                b.criado_em,
                t.id_tripulante,
                t.nome AS nome_tripulante,
                t.email AS email_tripulante
                FROM tb_bilhetes_dezenas bd
                JOIN tb_bilhetes b ON b.id_bilhete = bd.id_bilhete 
                JOIN tb_tripulantes t ON t.id_tripulante = b.id_tripulante 
                WHERE b.id_bilhete = :id_bilhete";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id_bilhete', $data['id_bilhete'], \PDO::PARAM_INT);
                $stmt->execute();

                $resultset = $stmt->fetch(\PDO::FETCH_ASSOC);

                return [
                    "status" => 200,
                    "message" => "Dezena inserida com sucesso",
                    "data" => [
                        "dezena" => $resultset['dezena'],
                        "bilhete" => [
                            "id_bilhete" => $resultset['id_bilhete'],
                            "criado_em" => $resultset['criado_em'],
                            "tripulante" => [
                                "id" => $resultset['id_tripulante'],
                                "nome" => $resultset['nome_tripulante'],
                                "email" => $resultset['email_tripulante']
                            ]
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
    public function update($data, $id_bilhete) {}

    /**
     * Remove uma dezena específica de um bilhete.
     *
     * @param int $id_bilhete O ID do bilhete do qual a dezena será removida.
     * @param int $dezena O valor da dezena a ser removida.
     * @return array Retorna um array com o status da operação, a mensagem e o resultado da exclusão.
     */
    public function delete($id_bilhete, $dezena)
    {
        try {
            //** DELETA DADOS **//
            $sql = "DELETE FROM tb_bilhetes_dezenas WHERE id_bilhete = :id_bilhete AND dezena = :dezena";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_bilhete', $id_bilhete, \PDO::PARAM_INT);
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
