<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
</head>
<?php
$database = 'placementportal';

// set path
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/placementportal/');
define('BASE_URL', '/placementportal/');




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

        if ($ins->execute()) {
            $authId = $this->con->lastInsertId();

            $tableMap = [
                'student'     => 'student',
                'company'     => 'company',
                'co-ordinator' => 'coordinator'
            ];

            if (isset($tableMap[$role])) {
                $table = $tableMap[$role];
                $stmt = $this->con->prepare("INSERT INTO {$table} (authid) VALUES (:authid)");
                $stmt->bindParam(':authid', $authId);
                $stmt->execute();
            }

            return $authId;
        }


        return false;
    }

    // update basic info
    public function updateProfile($email, $firstname, $middlename, $lastname, $contact, $city, $state, $gender, $bio)
    {
        $stmt = $this->con->prepare("
        UPDATE auth SET 
            firstname = :firstname,
            middlename = :middlename,
            lastname = :lastname,
            contact = :contact,
            city = :city,
            state = :state,
            gender = :gender,
            bio = :bio
        WHERE mail = :email
    ");

        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':middlename', $middlename);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':email', $email);

        return $stmt->execute();
    }

    // update User Image
    public function updateUserImage($mail, $filename)
    {
        $stmt = $this->con->prepare("UPDATE auth SET userimage = :img WHERE mail = :em");
        $stmt->bindParam(':img', $filename);
        $stmt->bindParam(':em', $mail);
        return $stmt->execute();
    }


    // Generate a secure random token
    public function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    // Encrypt email for URL
    public function encryptEmail($email)
    {
        $key = 'your-secret-key-123';
        $iv = substr(hash('sha256', $key), 0, 16);
        $encrypted = openssl_encrypt($email, 'AES-128-CBC', $key, 0, $iv);
        return urlencode(base64_encode($encrypted));
    }

    // Decrypt email from URL
    public function decryptEmail($encryptedEmail)
    {
        $key = 'your-secret-key-123';
        $iv = substr(hash('sha256', $key), 0, 16);
        $decoded = base64_decode(urldecode($encryptedEmail));
        return openssl_decrypt($decoded, 'AES-128-CBC', $key, 0, $iv);
    }

    // Update password for auth by email
    public function updatePassword($email, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->con->prepare("UPDATE auth SET password = :pw WHERE mail = :em");
        $stmt->bindParam(':pw', $hashedPassword);
        $stmt->bindParam(':em', $email);
        return $stmt->execute();
    }
    public function updateStudentProfile($authId, $fields, $files)
    {
        $updates = [];
        $params = [];

        // Allowed fields
        $allowedFields = [
            'sid',
            'authid',
            'studentid',
            'branch',
            'cgpa',
            'skills',
            'resume',
            'certificate',
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
            'bachelordegree',
            'status'
        ];

        // Handle skills 
        if (!empty($fields['skills'])) {
            $fields['skills'] = implode(',', array_map('trim', explode(',', $fields['skills'])));
        }

        // Handle allowed fields
        foreach ($allowedFields as $field) {
            if (isset($fields[$field]) && $fields[$field] !== '') {
                $updates[] = "$field = :$field";
                $params[":$field"] = $fields[$field];
            }
        }

        // Handle resume upload (single file)
        if (isset($files['resume']) && $files['resume']['error'] === UPLOAD_ERR_OK) {
            $resumeName = basename($files['resume']['name']);
            $resumePath = 'uploads/resumes/' . $resumeName;
            $targetDir = __DIR__ . '/uploads/resumes/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            // Delete old resume
            $oldResume = $this->con->prepare("SELECT resume FROM student WHERE authid = :id");
            $oldResume->execute([':id' => $authId]);
            $oldResumePath = $oldResume->fetchColumn();
            if ($oldResumePath && file_exists(__DIR__ . '/' . $oldResumePath)) {
                unlink(__DIR__ . '/' . $oldResumePath);
            }

            if (move_uploaded_file($files['resume']['tmp_name'], $targetDir . $resumeName)) {
                $updates[] = "resume = :resume";
                $params[':resume'] = $resumePath;
            }
        }

        // Handle certificate upload
        if (isset($files['certificate']) && $files['certificate']['error'] === UPLOAD_ERR_OK) {
            $certName = basename($files['certificate']['name']);
            $certPath = 'uploads/certificates/' . $certName;
            $targetDir = __DIR__ . '/uploads/certificates/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            // Delete old certificate
            $oldCert = $this->con->prepare("SELECT certificate FROM student WHERE authid = :id");
            $oldCert->execute([':id' => $authId]);
            $oldCertPath = $oldCert->fetchColumn();
            if ($oldCertPath && file_exists(__DIR__ . '/' . $oldCertPath)) {
                unlink(__DIR__ . '/' . $oldCertPath);
            }

            if (move_uploaded_file($files['certificate']['tmp_name'], $targetDir . $certName)) {
                $updates[] = "certificate = :certificate";
                $params[':certificate'] = $certPath;
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

    // delete resume
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

    // delete a specific certificate
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
        $stmt = $this->con->prepare("SELECT * FROM auth WHERE mail = :em");
        $stmt->bindParam(':em', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // company
    // Get company by authid
    public function getCompany($authid)
    {
        $stmt = $this->con->prepare("SELECT * FROM company WHERE authid = :authid LIMIT 1");

        $stmt->bindParam(':authid', $authid);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Update company profile
    public function updateCompanyProfile($authid, $cname, $ctype, $cweb, $cabout, $csize, $cspecialization, $caddress, $clinkedin)
    {
        $stmt = $this->con->prepare("
        UPDATE company SET 
            cname = :cname, 
            ctype = :ctype, 
            cweb = :cweb, 
            cabout = :cabout, 
            csize = :csize, 
            cspecialization = :cspecialization, 
            caddress = :caddress, 
            clinkedin = :clinkedin
        WHERE authid = :authid
    ");

        $stmt->bindParam(':cname', $cname);
        $stmt->bindParam(':ctype', $ctype);
        $stmt->bindParam(':cweb', $cweb);
        $stmt->bindParam(':cabout', $cabout);
        $stmt->bindParam(':csize', $csize);
        $stmt->bindParam(':cspecialization', $cspecialization);
        $stmt->bindParam(':caddress', $caddress);
        $stmt->bindParam(':clinkedin', $clinkedin);
        $stmt->bindParam(':authid', $authid);

        return $stmt->execute();
    }

    // Update company image only
    public function updateCompanyImage($authid, $cimage)
    {
        $stmt = $this->con->prepare("
        UPDATE company SET cimage = :cimage 
        WHERE authid = :authid
    ");

        $stmt->bindParam(':cimage', $cimage);
        $stmt->bindParam(':authid', $authid);

        return $stmt->execute();
    }


    // co-ordinator
    // Get coordinator 
    public function getCoordinator($authId)
    {
        $stmt = $this->con->prepare("SELECT * FROM coordinator WHERE authid = :authid");
        $stmt->bindParam(':authid', $authId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update coordinator
    public function updateCoordinator($coordinatorId, $employeecode, $designation, $department, $joiningdate, $remarks)
    {
        $stmt = $this->con->prepare(
            "UPDATE coordinator 
             SET employeecode = :employeecode,
                 designation = :designation,
                 department = :department,
                 joiningdate = :joiningdate,
                 remarks = :remarks
             WHERE coordinatorid = :coordinatorid"
        );

        $stmt->bindParam(':employeecode', $employeecode);
        $stmt->bindParam(':designation', $designation);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':joiningdate', $joiningdate);
        $stmt->bindParam(':remarks', $remarks);
        $stmt->bindParam(':coordinatorid', $coordinatorId);

        return $stmt->execute();
    }
    // admin
    // get all students
    function getStudets()
    {
        $stmt = $this->con->prepare('SELECT s.*,a.* FROM student s JOIN auth a ON a.id = s.authid');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function getCompanies()
    {
        $stmt = $this->con->prepare('SELECT c.*,a.* FROM company c JOIN auth a ON a.id = c.authid');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function getCoordinators()
    {
        $stmt = $this->con->prepare('SELECT co.*,a.* FROM coordinator co JOIN auth a ON a.id = co.authid');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch all links for a student
    function getSocialLinks($authid)
    {
        $stmt = $this->con->prepare("SELECT * FROM sociallinks WHERE authid=? ORDER BY created_at DESC");
        $stmt->execute([$authid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch link by platform
    function getSocialLinkByPlatform($authid, $platform)
    {
        $stmt = $this->con->prepare("SELECT * FROM sociallinks WHERE authid=? AND platform=?");
        $stmt->execute([$authid, $platform]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insert new link
    function insertSocialLink($authid, $platform, $link)
    {
        $stmt = $this->con->prepare("INSERT INTO sociallinks (authid, platform, link, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$authid, $platform, $link]);
    }

    // Update existing link
    function updateSocialLink($slid, $link)
    {
        $stmt = $this->con->prepare("UPDATE sociallinks SET link=? WHERE slid=?");
        return $stmt->execute([$link, $slid]);
    }

    // Delete link
    function deleteSocialLink($slid)
    {
        $stmt = $this->con->prepare("DELETE FROM sociallinks WHERE slid=?");
        return $stmt->execute([$slid]);
    }

    public function getStudentProfile($authId)
    {
        $stmt = $this->con->prepare("SELECT s.*,a.* FROM student s JOIN auth a ON a.id = s.authid WHERE authid = :authid LIMIT 1");
        $stmt->bindParam(':authid', $authId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getConnection()
    {
        return $this->con;
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
    bio TEXT NOT NULL,
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
        bachelordegree VARCHAR(100),
        linkedin VARCHAR(255),
        github VARCHAR(255),
        FOREIGN KEY (authid) REFERENCES auth(id)
    )";

    $con->exec($create_student);
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}


// company table
try {
    $create_company = "CREATE TABLE IF NOT EXISTS company (
        cid INT AUTO_INCREMENT PRIMARY KEY,
        authid INT NOT NULL,
        cimage VARCHAR(255),
        cname VARCHAR(255),
        ctype VARCHAR(100),
        cweb VARCHAR(255),
        cabout TEXT,
        csize INT,
        cspecialization VARCHAR(255),
        caddress TEXT,
        clinkedin TEXT,
        FOREIGN KEY (authid) REFERENCES auth(id)
    )";

    $con->exec($create_company);
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}

// coordinator table
try {
    $create_coordinator = "CREATE TABLE IF NOT EXISTS coordinator (
        coordinatorid INT AUTO_INCREMENT PRIMARY KEY,
        authid INT NOT NULL,
        employeecode VARCHAR(100),
        designation VARCHAR(100),
        department VARCHAR(100),
        joiningdate DATE,
        remarks TEXT,
        FOREIGN KEY (authid) REFERENCES auth(id)
    )";

    $con->exec($create_coordinator);
} catch (PDOException $e) {
    echo "Error creating coordinator table: " . $e->getMessage();
}

try {
    $create_social_links = "CREATE TABLE IF NOT EXISTS sociallinks (
        slid INT AUTO_INCREMENT PRIMARY KEY,
        authid INT NOT NULL,
        platform VARCHAR(100) NOT NULL,
        link VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (authid) REFERENCES auth(id) ON DELETE CASCADE
    )";

    $con->exec($create_social_links);
} catch (PDOException $e) {
    echo "Error creating sociallinks table: " . $e->getMessage();
}