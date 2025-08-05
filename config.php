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

    function __construct($database)
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

    // Login
    function login($email)
    {
        $stmt = $this->con->prepare("SELECT * FROM auth WHERE mail = :em");
        $stmt->bindParam(":em", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // register
    function register($firstname, $middlename, $lastname, $email, $gender, $city, $state, $contact, $role, $password)
    {
        // 1. Insert into auth table
        $ins = $this->con->prepare("INSERT INTO auth 
        (firstname, middlename, lastname, mail, gender, city, state, contact, authrole, password) 
        VALUES 
        (:fn, :mn, :ln, :em, :gn, :ct, :st, :co, :ro, :pw)");

        $ins->bindParam(':fn', $firstname);
        $ins->bindParam(':mn', $middlename);
        $ins->bindParam(':ln', $lastname);
        $ins->bindParam(':em', $email);
        $ins->bindParam(':gn', $gender);
        $ins->bindParam(':ct', $city);
        $ins->bindParam(':st', $state);
        $ins->bindParam(':co', $contact);
        $ins->bindParam(':ro', $role);
        $ins->bindParam(':pw', $password);

        // Execute the auth insert
        if ($ins->execute()) {
            $authId = $this->con->lastInsertId();

            // 2. If role is student, insert into student table
            if ($role === 'student') {
                $studentInsert = $this->con->prepare("INSERT INTO student (Authid, Status) VALUES (:authid, :status)");
                $studentInsert->bindParam(':authid', $authId);
                $status = 'active';
                $studentInsert->bindParam(':status', $status);
                $studentInsert->execute();
            }

            return $authId;
        }

        return false;
    }

    public function updateStudentProfile($authId, $fields, $files)
    {
        $updates = [];
        $params = [];

        // Allowed fields for update (all lowercase)
        $allowedFields = [
            'studentid',
            'branch',
            'cgpa',
            'skills',
            'pass10year',
            'pass10board',
            'pass10percentage',
            'pass12year',
            'pass12board',
            'pass12percentage',
            'bachelortype',
            'diplomainstitute',
            'diplomayear',
            'diplomapercentage',
            'bacheloruniversity',
            'bacheloryear',
            'bachelorgpa',
            'bachelorbranch',
            'status'
        ];

        // Handle skills (array to comma-separated)
        if (isset($fields['skills'])) {
            if (is_array($fields['skills'])) {
                $fields['skills'] = implode(',', $fields['skills']);
            }
            $updates[] = "skills = :skills";
            $params[':skills'] = $fields['skills'];
            unset($fields['skills']);
        }

        // Other allowed fields
        foreach ($allowedFields as $field) {
            if (isset($fields[$field]) && $fields[$field] !== '') {
                $updates[] = "$field = :$field";
                $params[":$field"] = $fields[$field];
            }
        }

        // Handle resume upload (single)
        if (isset($files['resume']) && $files['resume']['error'] === UPLOAD_ERR_OK) {
            $resumeName = basename($files['resume']['name']); // Use original name
            $resumePath = 'uploads/resumes/' . $resumeName;
            $targetDir = __DIR__ . '/uploads/resumes/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            if (move_uploaded_file($files['resume']['tmp_name'], $targetDir . $resumeName)) {
                $updates[] = "resume = :resume";
                $params[':resume'] = $resumePath;
            }
        }

        // Handle certificates upload (multiple)
        if (isset($files['certificate']) && is_array($files['certificate']['name'])) {
            $certPaths = [];
            $targetDir = __DIR__ . '/uploads/certificates/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            foreach ($files['certificate']['name'] as $i => $certName) {
                if ($files['certificate']['error'][$i] === UPLOAD_ERR_OK) {
                    $certBaseName = basename($certName); // Use original name
                    $certPath = $targetDir . $certBaseName;
                    if (move_uploaded_file($files['certificate']['tmp_name'][$i], $certPath)) {
                        $certPaths[] = 'uploads/certificates/' . $certBaseName;
                    }
                }
            }
            if (!empty($certPaths)) {
                $updates[] = "certificate = :certificate";
                $params[':certificate'] = implode(',', $certPaths);
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE student SET " . implode(', ', $updates) . " WHERE authid = :authid";
        $params[':authid'] = $authId;

        $stmt = $this->con->prepare($sql);
        return $stmt->execute($params);
    }


    // totalStudents
    function totalStudent()
    {
        $stmt = $this->con->prepare('SELECT * FROM student ');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);;
    }

    // Function to delete resume file and update DB
    public function deleteResume($authId)
    {
        $stmt = $this->con->prepare("SELECT resume FROM student WHERE authid = :id");
        $stmt->execute([':id' => $authId]);
        $resume = $stmt->fetchColumn();

        if ($resume && file_exists("../" . $resume)) {
            unlink("../" . $resume);
        }

        $update = $this->con->prepare("UPDATE student SET resume = '' WHERE authid = :id");
        return $update->execute([':id' => $authId]);
    }

    // Function to delete a specific certificate
    public function deleteCertificate($authId, $certToDelete)
    {
        $stmt = $this->con->prepare("SELECT certificate FROM student WHERE authid = :id");
        $stmt->execute([':id' => $authId]);
        $certListStr = $stmt->fetchColumn();

        $certList = explode(',', $certListStr);
        $certList = array_filter($certList, fn($c) => $c !== $certToDelete);
        $newCertList = implode(',', $certList);

        if ($certToDelete && file_exists("../" . $certToDelete)) {
            unlink("../" . $certToDelete);
        }

        $update = $this->con->prepare("UPDATE student SET certificate = :cert WHERE authid = :id");
        return $update->execute([':cert' => $newCertList, ':id' => $authId]);
    }

    // forgot password via Mail
    function mailSendForPassword($email)
    {
        $stmt = $this->con->prepare("SELECT mail FROM auth WHERE mail = :em");
        $stmt->bindParam(':em', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getConnection()
    {
        return $this->con;
    }

    public function getStudentProfile($authId)
    {
        $stmt = $this->con->prepare("SELECT * FROM student WHERE authid = :authid LIMIT 1");
        $stmt->bindParam(':authid', $authId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// table queries
$obj = new Config($database);
$con = $obj->getConnection();

// Create auth table
try {
    $create_auth = "CREATE TABLE IF NOT EXISTS auth (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userimage VARCHAR(256) NOT NULL,
    firstname VARCHAR(256) NOT NULL,
    middlename VARCHAR(256) NOT NULL,
    lastname VARCHAR(256) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    contact VARCHAR(15) NOT NULL,
    mail VARCHAR(256) NOT NULL,
    password VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    authrole VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP NOT NULL,
    status VARCHAR(255),
    accept_terms BOOLEAN
)";
    $con->exec($create_auth);
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}

// create student table
try {
    $create_student = "CREATE TABLE IF NOT EXISTS student (
        sid INT AUTO_INCREMENT PRIMARY KEY,
        authid INT NOT NULL,
        studentid VARCHAR(100) NOT NULL,
        branch VARCHAR(100),
        cgpa DECIMAL(4,2),
        skills TEXT,
        resume VARCHAR(255),
        certificate VARCHAR(255),
        pass10year YEAR,
        pass10board VARCHAR(100),
        pass10percentage DECIMAL(5,2),
        pass12year YEAR,
        pass12board VARCHAR(100),
        pass12percentage DECIMAL(5,2),
        bachelortype VARCHAR(100),
        diplomainstitute VARCHAR(255),
        diplomayear YEAR,
        diplomapercentage DECIMAL(5,2),
        bacheloruniversity VARCHAR(255),
        bacheloryear YEAR,
        bachelorgpa DECIMAL(4,2),
        bachelorbranch VARCHAR(100),
        status VARCHAR(100),
        FOREIGN KEY (authid) REFERENCES auth(id)
    )";

    $con->exec($create_student);
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
