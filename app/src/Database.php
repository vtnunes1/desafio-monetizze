<?php

namespace App;

use PDO;
use PDOException;

class Database
{
    /**
     * @var string O host do banco de dados, geralmente 'localhost' ou o nome do servidor.
     */
    protected $host = 'mysql';

    /**
     * @var int A porta do banco de dados, padrão para MySQL é 3306.
     */
    protected $port = 3306;

    /**
     * @var string O nome do banco de dados ao qual a conexão será estabelecida.
     */
    protected $db = 'monetizze';

    /**
     * @var string O nome de usuário utilizado para autenticação no banco de dados.
     */
    protected $user = 'root';

    /**
     * @var string A senha do usuário utilizado para autenticação no banco de dados.
     */
    protected $password = 'password';

    /**
     * Estabelece uma conexão com o banco de dados MySQL.
     *
     * @return \PDO Retorna o objeto `PDO` que representa a conexão com o banco de dados.
     */
    public function connect()
    {
        $dsn = "mysql:host=$this->host;port=$this->port;dbname=$this->db;charset=utf8";

        try {
            $pdo = new PDO($dsn, $this->user, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo 'Erro na conexão: ' . $e->getMessage();
            exit;
        }
    }
}
