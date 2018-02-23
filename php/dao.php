<?php
// MySQL credentials
define('DATABASE', 'DerpPlay');
define('MYSQL_HOST', 'mysql:dbname='.DATABASE.';host=127.0.0.1;charset=utf8mb4');
define('MYSQL_USER', 'derpusr');
define('MYSQL_PASS', 'derpazo123');

// Users table
define('TABLE_USER', 'Users');
define('USER_ID', 'user_id');
define('USER_NAME', 'user_name');
define('USER_EMAIL', 'user_email');
define('USER_PASS', 'user_password');
define('USER_IMG', 'user_img');
define('USER_VERIFIED', 'user_verified');
define('USER_HASH', 'user_hash');

// Videos table
define('TABLE_VIDEO', 'Videos');
define('VIDEO_ID', 'video_id');
define('VIDEO_NAME', 'video_name');
define('VIDEO_DESC', 'video_desc'); // Video description
define('VIDEO_UPLOADER', 'video_uploader');
define('VIDEO_URI', 'video_uri');
define('VIDEO_UPLOAD', 'video_uploaded');
define('VIDEO_PREVIEW', 'video_preview');

class DerpDao {
    protected $conn;
    protected $error;

    /* A database connection object is created by the class constructor and all remaining data gets initialised */
    function __construct() {
        try {
            $this->conn = new PDO(MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->error = "Connection error: ".$e->getMessage();
            $this->conn = null;
        }
    }

    /* Class destructor */
    function __destruct() {
        if ($this->isConnected()) {
            $this->conn = null;
        }
    }

    /* Getter for $error */
    function getError() {
        return $this->error;
    }

    /* This function checks whether there is a connection to the MySQL server */
    function isConnected() {
        return (!($this->conn == null));
    }

    private function execute($sql) {
        $statement = $this->conn->query($sql);
        if (!$statement)
            $this->error = "Data statement error";

        return $statement;
    }

    private function prepareSql($sql) {
        $statement = $this->conn->prepare($sql);
        if (!$statement) 
            $this->error = "Data statement error";
            
        return $statement;
    }

    function userLogin($email, $pass) {
        $sql = "SELECT * FROM ".TABLE_USER." WHERE ".USER_EMAIL." = :email AND ".USER_PASS." = SHA2(:pass, 256) AND ".USER_VERIFIED." = TRUE";
        $dbquery = $this->prepareSql($sql);
        $dbquery->bindParam(":email", $email);
        $dbquery->bindParam(":pass", $pass);
        $dbquery->execute();
        $statement = $dbquery->fetchAll();

        if ($statement != null) {
            return true;
        } else {
            return false;
        }
    }

    function getUserId($email) {
        $sql = "SELECT ".USER_ID." FROM ".TABLE_USER." WHERE ".USER_EMAIL."= :email";
        $dbquery = $this->prepareSql($sql);
        $dbquery->bindParam(":email", $email);
        $dbquery->execute();
        $statement = $dbquery->fetch();

        return $statement[USER_ID];
    }

    function userSignup($username, $email, $pass, $hash) {
        $inserted = false;
        try {
            $sql = "INSERT INTO ".TABLE_USER." (".USER_NAME.", ".USER_EMAIL.", ".USER_PASS.", ".USER_HASH.") VALUES (:name, :email, SHA2(:pass, 256), :hash)";
            $statement = $this->prepareSql($sql);
            $statement->bindParam(":name", $username);
            $statement->bindParam(":email", $email);
            $statement->bindParam(":pass", $pass);
            $statement->bindParam(":hash", $hash);
            $inserted = $statement->execute();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
        return $inserted;
    }

    function userVerify($username, $hash) {
        $verified = false;
        try {
            $sql = "UPDATE ".TABLE_USER." SET ".USER_HASH." = NULL, ".USER_VERIFIED." = TRUE WHERE ".USER_NAME." LIKE :user AND ".USER_HASH." LIKE :hash";
            $statement = $this->prepareSql($sql);
            $statement->bindParam(":user", $username);
            $statement->bindParam(":hash", $hash);
            $verified = $statement->execute();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
        return $verified;
    }

    function checkEmail($email) {
        $exists = false;

        $sql = "SELECT ".USER_ID." FROM ".TABLE_USER." WHERE ".USER_EMAIL." LIKE :email";
        $dbquery = $this->prepareSql($sql);
        $dbquery->bindParam(":email", $email);
        $dbquery->execute();
        $statement = $dbquery->fetchAll();

        if ($statement != null)
            $exists = true;

        return $exists;
    }

    function checkUsername($username) {
        $exists = false;
        
        $sql = "SELECT ".USER_ID." FROM ".TABLE_USER." WHERE ".USER_NAME." LIKE :name";
        $dbquery = $this->prepareSql($sql);
        $dbquery->bindParam(":name", $username);
        $dbquery->execute();
        $statement = $dbquery->fetchAll();

        if ($statement != null)
            $exists = true;

        return $exists;
    }



    function getVideos() {
        $sql = "SELECT  ".VIDEO_PREVIEW.", ".VIDEO_NAME.", ".USER_NAME.", ".USER_ID.", ".VIDEO_UPLOAD.", ".VIDEO_ID." FROM ".TABLE_VIDEO." INNER JOIN ".TABLE_USER." ON ".VIDEO_UPLOADER." = ".USER_ID." ORDER BY ".VIDEO_UPLOAD." DESC LIMIT 15";
        return $this->execute($sql);
    }

    function searchVideo($query) {
        $sql = "SELECT  ".VIDEO_PREVIEW.", ".VIDEO_NAME.", ".USER_NAME.", ".USER_ID.", ".VIDEO_UPLOAD.", ".VIDEO_ID." FROM ".TABLE_VIDEO." INNER JOIN ".TABLE_USER." ON ".VIDEO_UPLOADER." = ".USER_ID." WHERE ".VIDEO_NAME." LIKE CONCAT('%', :query, '%') ORDER BY ".VIDEO_UPLOAD." DESC";
        $dbquery = $this->prepareSql($sql);
        $dbquery->bindParam(':query', $query);
        $statement = $dbquery->execute();

        if ($statement)
            return $dbquery;
        else
            return false;
    }

    function getVideo($id) {
        $sql = "SELECT ".VIDEO_NAME.", ".USER_NAME.", ".USER_ID.", ".USER_IMG.", ".VIDEO_UPLOAD.", ".VIDEO_URI.", ".VIDEO_DESC.", ".VIDEO_PREVIEW." FROM ".TABLE_VIDEO." INNER JOIN ".TABLE_USER." ON ".VIDEO_UPLOADER." = ".USER_ID." WHERE ".VIDEO_ID." = ".$id;

        return $this->execute($sql);
    }



    function getUserInfo($id) {
        $sql = "SELECT ".USER_NAME.", ".USER_IMG." FROM ".TABLE_USER." WHERE ".USER_ID." = ".$id;

        return $this->execute($sql);
    }

    function getUserVideos($uploader) {
        $sql = "SELECT ".VIDEO_ID.", ".VIDEO_NAME.", ".VIDEO_UPLOAD.", ".VIDEO_PREVIEW." FROM ".TABLE_VIDEO." WHERE ".VIDEO_UPLOADER." = ".$uploader;

        return $this->execute($sql);
    }
}
?>