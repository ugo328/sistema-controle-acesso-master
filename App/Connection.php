<?php
namespace App;

use PDO;
use PDOException;

class Connection {
    public static function getDb() {
        $host = 'localhost';
        $dbname = 'controle_de_acesso'; // Substitua pelo nome do seu banco de dados
        $username = 'root';
        $password = '123456';

        try {
            $conn = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $username,
                $password
            );

            // Define o modo de erro do PDO para exceções
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $conn;
        } catch (PDOException $e) {
            // Em caso de erro na conexão, trate o erro de alguma forma adequada
            echo "Erro de conexão: " . $e->getMessage();
            return null;
        }
    }
}

namespace MF\Model;

use PDO;

abstract class Model {
    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }
}

namespace MF\Model;

use App\Connection as AppConnection;

class ModelContainer {
    public static function getModel($model) {
        $class = "\\App\\Models\\" . ucfirst($model);
        $conn = AppConnection::getDb();

        return new $class($conn);
    }
}
