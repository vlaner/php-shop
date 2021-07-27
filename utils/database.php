<?php

require __DIR__ .  "/../vendor/autoload.php";
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
    $dotenv->load();
}

class Database
{
    private $user = '';
    private $password = '';
    private $host = '';
    private $database = '';
    private $port = '';

    function __construct()
    {

        $this->user = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->host = $_ENV['DB_HOST'];
        $this->database = $_ENV['database'];
        $this->port = $_ENV['port'];
    }

    public function getConnection()
    {
        try {
            $conn = new PDO(
                "pgsql:host={$this->host};
                dbname={$this->database};
                port={$this->port}
                ",
                $this->user,
                $this->password
            );
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $conn;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function getAdmin($email, $password)
    {
        $sql = 'select email,password from admin where email=?';

        $stmt = $this->getConnection()->prepare($sql);

        $stmt->execute([$email]);

        $data = $stmt->fetch();

        if ($data == 0) {
            return json_encode(-1);
            die();
        }

        if (!password_verify($password, $data['password'])) {
            return json_encode(-1);
            die();
        }
        return json_encode(1);
    }
}