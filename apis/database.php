<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

/**
 * Database config variables
 */
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "muhammadhaikal1!");
define("DB_DATABASE", "patroli");

class connection {
    private $conn;

    // Connecting to database
    public function connect() {
        //require_once 'include/Config.php';

        // Connecting to mysql database
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        // Error handling
        if(mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(), E_USER_ERROR);
            exit();
        }

        // return database handler
        return $this->conn;
    }

}

class database {

    private $conn;

    // constructor
    function __construct() {
        // connecting to database
        $db = new connection();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {
        //mysqli::close($this->conn);
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $phone, $password) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO parents(email, name, phone, password, salt, created, registered, premium) VALUES
                  (?, ?, ?, ?, ?, NOW(), 0, 0)");
        $stmt->bind_param("sssss", $email, $name, $phone, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM parents WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }

    public function checkRegisteredUser($email){
      $stmt = $this->conn->prepare("SELECT registered FROM parents WHERE email = ?");

      $stmt->bind_param("s", $email);

      if ($stmt->execute()) {
          $user = $stmt->get_result()->fetch_assoc();
          $stmt->close();

          if (($user['registered'] == 1) || ($user['registered'] == true)) return true;
          else return false;

      }
      return false;
    }

    public function checkPremiumUser($email){
      $stmt = $this->conn->prepare("SELECT premium FROM parents WHERE email = ?");

      $stmt->bind_param("s", $email);

      if ($stmt->execute()) {
          $user = $stmt->get_result()->fetch_assoc();
          $stmt->close();

          if (($user['premium'] == 1) || ($user['premium'] == true)) return true;
          else return false;

      }
      return false;
    }

    public function checkNumUserChilds($email){
        $iCount = 0;
        $stmt = $this->conn->prepare("SELECT parent FROM childs WHERE parent = ?");

        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            //$user = $stmt->get_result()->fetch_assoc();
            $stmt->store_result();
            $iCount = $stmt->num_rows;
            $stmt->close();
            return $iCount;
        }
        return -1;
    }

    /**
     * Storing new child
     * returns child details
     */
    public function storeChild($parent,$name,$device_id,$device_name) {
        $id = hash('sha256',$parent.$name);
        $stmt = $this->conn->prepare("INSERT INTO childs(id, parent, name, device_id, device_name, created) VALUES
                  (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $id,$parent,$name,$device_id,$device_name);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM childs WHERE parent = ? and name = ?");
            $stmt->bind_param("ss", $parent,$name);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }

    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {

        $stmt = $this->conn->prepare("SELECT * FROM parents WHERE email = ?");

        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from parents WHERE email = ?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * Check child is existed or not
     */
    public function isChildExisted($parent, $name, $device_id) {
        $stmt = $this->conn->prepare("SELECT parent, name, device_id from childs WHERE parent = ? and name = ? and device_id = ?");

        $stmt->bind_param("sss", $parent, $name, $device_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = hash('sha256', rand());//sha256(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(hash('sha256',$password . $salt) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(hash('sha256',$password . $salt) . $salt);

        return $hash;
    }

}
?>
