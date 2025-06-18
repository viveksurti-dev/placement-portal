<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
</head>
<?php
$database = 'placementportal';

$conn = mysqli_connect("localhost", "root", "");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$createDatabase = "CREATE DATABASE IF NOT EXISTS $database";
if (!mysqli_query($conn, $createDatabase)) {
    echo "Error creating database: " . mysqli_error($conn);
}

mysqli_close($conn);
class Config
{
    private $con;

    function construct($database)
    {
        $dsn = "mysql:host=localhost;dbname=$database";
        $username = "root";
        $password = "";

        try {
            $this->con = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}

// table queries
// $obj = new Config($database);
$con = $obj->getConnection();

// Create auth table
try {
    $create_auth = "CREATE TABLE IF NOT EXISTS auth (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(256) NOT NULL,
    middlename VARCHAR(256) NOT NULL,
    lastname VARCHAR(256) NOT NULL,
    mail VARCHAR(256) NOT NULL,
    password VARCHAR(100) NOT NULL,
    authrole VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP NOT NULL,
    status VARCHAR(255),
    accept_terms BOOLEAN
)";
    $con->exec($create_auth);
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}

try {
    $create_student = "CREATE TABLE IF NOT EXISTS students(
        id INT AUTO_INCREMENT PRIMARY KEY,
        enrollid VARCHAR(100) NOT NULL,
        branch VARCHAR(100) NOT NULL,
        
    )";
    $con->exec($create_student);
} catch (PDOException $e) {
    echo "Error : " . $e->getMessage();
}
