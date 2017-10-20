<?php
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors',1);

/**
 * Database config variables
 */
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_DATABASE", "wo");
/*
class Connection {
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
*/
class Database {

    private $conn;

    // constructor
    function __construct() {
        // connecting to database
        //$db = new Connection();
        
        // Connecting to mysql database
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        // Error handling
        if(mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(), E_USER_ERROR);
            exit();
        }
        
        //$this->conn = $db->connect();
    }

    // destructor
    function __destruct() {
        //mysqli::close($this->conn);
    }

//------------ ADMIN HANDLE

    /**
     * Check user is existed or not
     */
    public function isAdminExisted($mail) {
        $stmt = $this->conn->prepare("SELECT email from admin WHERE email = ?");
        $stmt->bind_param("s", $mail);
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
     * Storing new administrator
     * returns administrator details
     */
    public function storeAdmin($name, $mail, $pass) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($pass);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO admin (id, name, email, pass, salt, created, role) VALUES
                  ('', ?, ?, ?, ?, NOW(), 'ADMINISTRATOR')");
        $stmt->bind_param("ssssss", $name, $mail, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM admin WHERE email = ?");
            $stmt->bind_param("s", $mail);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return true;
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * Get user by email and password
     */
    public function getAdminByEmailAndPassword($mail, $pass) {
        $stmt = $this->conn->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['pass'];
            $hash = $this->checkhashSSHA($salt, $pass);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return true;
            }
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * Get user name by email
     */
    public function getAdminId($mail) {
        $stmt = $this->conn->prepare("SELECT id FROM admin WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['id'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user name by email
     */
    public function getAdminName($mail) {
        $stmt = $this->conn->prepare("SELECT name FROM admin WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['name'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    public function adminGetWOOnTable(){

        $stmt = $this->conn->prepare("SELECT * FROM wo WHERE 1");

        $result = $stmt->execute();
        //$stmt->close();

        // check for successful get
        if ($result) {

            /* Get the result */
            $result = $stmt->get_result();

            while ($data = $result->fetch_assoc()){
              echo "<tr>";
                echo "<td>".$data['name']."</td>";
                echo "<td>".$data['owner']."</td>";
                echo "<td>".$data['email']."</td>";
                echo "<td>".$data['phone']."</td>";
                echo "<td>".$data['address']."</td>";
                echo "<td>".$data['deskripsi']."</td>";
                echo "<td>".$data['created']."</td>";
                if ($data['approved']=="0"){
                  echo "<td class='wo_aprv' id='wo_aprv'>Waiting</td>";
                } else if ($data['approved']=="1"){
                  echo "<td class='wo_aprv' id='wo_aprv'>Approved</td>";
                }

                echo "<td style='text-align:center'>";
                  echo "<a class='user' id='wo_".$data['id']."' style='padding: 0px 5px 0px 5px; cursor:pointer;' data-toggle='tooltip' title='Chat'>";
                    echo "<img src='assets/css/images/chat.png' id='btn-set'";
                  echo "</a>";
                  //echo "<a style='padding: 0px 5px 0px 5px; cursor:pointer;' data-toggle='tooltip' title='Ubah'>";
                  //  echo "<img src='assets/css/images/write.png' id='btn-set' data-toggle='modal' data-title='Approve wo' data-target='#mdl_awo' data-paket='".$data['paket_id']."' data-tipe='".$data['type']."' data-item='".$data['item']."' data-total='".$data['total']."'/></a>";
                  //echo "</a>";
                  echo "<a style='padding: 0px 5px 0px 5px; cursor:pointer;' data-toggle='tooltip' title='Hapus'>";
                    echo "<img src='assets/css/images/clear.png' data-toggle='modal' data-title='Delete wo' data-msg='delete this user?' data-target='#mdl_confirm' data-href='index.php?page=delwo&o1=".$data['id']."&o2=".$data['name']."'/>";
                  echo "</a>";
                echo "</td>";

              echo "</tr>";
            }

        } else {
            //echo $stmt->error."</br>";
        }
    }

    public function adminGetClientOnTable(){

        $stmt = $this->conn->prepare("SELECT * FROM klien WHERE 1");

        $result = $stmt->execute();
        //$stmt->close();

        // check for successful get
        if ($result) {

            /* Get the result */
            $result = $stmt->get_result();

            while ($data = $result->fetch_assoc()){
              echo "<tr>";
                echo "<td>".$data['name']."</td>";
                echo "<td>".$data['email']."</td>";
                echo "<td>".$data['gender']."</td>";
                echo "<td>".$data['phone']."</td>";
                echo "<td>".$data['address']."</td>";
                echo "<td>".$data['created']."</td>";
                echo "<td>".$data['confirmed']."</td>";

                echo "<td style='text-align:center'>";
                  echo "<a class='user' id='cl_".$data['id']."' style='padding: 0px 5px 0px 5px; cursor:pointer;' data-toggle='tooltip' title='Chat'>";
                    echo "<img src='assets/css/images/chat.png' id='btn-set'";
                  echo "</a>";
                  //echo "<a style='padding: 0px 5px 0px 5px; cursor:pointer;' data-toggle='tooltip' title='Ubah'>";
                    //echo "<img src='assets/css/images/write.png' id='btn-set' data-toggle='modal' data-title='Approve WO' data-target='#mdl_awo' data-aprv='".$data['paket_id']."' data-tipe='".$data['type']."' data-item='".$data['item']."' data-total='".$data['total']."'/></a>";
                  //echo "</a>";
                  echo "<a style='padding: 0px 5px 0px 5px; cursor:pointer;' data-toggle='tooltip' title='Hapus'>";
                    echo "<img src='assets/css/images/clear.png' data-toggle='modal' data-title='Delete client' data-msg='delete this user?' data-target='#mdl_confirm' data-href='index.php?page=delcl&o1=".$data['id']."&o2=".$data['name']."'/>";
                  echo "</a>";
                echo "</td>";

              echo "</tr>";
            }

        } else {
            //echo $stmt->error."</br>";
        }
    }

    public function adminGetWOChats(){

        $stmt = $this->conn->prepare("SELECT * FROM wo WHERE 1");

        $result = $stmt->execute();
        //$stmt->close();

        // check for successful get
        if ($result) {

            /* Get the result */
            $result = $stmt->get_result();

            while ($data = $result->fetch_assoc()){

                          echo '<div class="msg_box" id="wo_'.$data["id"].'">';
                            echo '<div class="msg_head">'.$data["name"];
                              echo '<div class="closec">x</div>';
                            echo '</div>';
                            echo '<div class="msg_wrap">';
                              echo '<div class="msg_body">';
                                $this->pullChatAW('admin',$data["id"]);
                                echo '<div class="msg_push" id="wo_'.$data["id"].'"></div>';
                              echo '</div>';
                              echo '<div class="msg_footer">';
                                echo '<textarea class="msg_input" rows="2" style="resize: none; color:#555;"></textarea>';
                              echo '</div>';
                            echo '</div>';
                          echo '</div>';
            }

        }
    }

    public function adminGetClChats(){

        $stmt = $this->conn->prepare("SELECT * FROM klien WHERE 1");

        $result = $stmt->execute();
        //$stmt->close();

        // check for successful get
        if ($result) {

            /* Get the result */
            $result = $stmt->get_result();

            while ($data = $result->fetch_assoc()){

                          echo '<div class="msg_box" id="cl_'.$data["id"].'">';
                            echo '<div class="msg_head">'.$data["name"];
                              echo '<div class="closec">x</div>';
                            echo '</div>';
                            echo '<div class="msg_wrap">';
                              echo '<div class="msg_body">';
                                $this->pullChatAC('admin',$data["id"]);
                                echo '<div class="msg_push" id="cl_'.$data["id"].'"></div>';
                              echo '</div>';
                              echo '<div class="msg_footer">';
                                echo '<textarea class="msg_input" rows="2" style="resize: none; color:#555;"></textarea>';
                              echo '</div>';
                            echo '</div>';
                          echo '</div>';
            }

        }
    }

    public function adminGetGuestChatBox(){
        include_once "security.php";
        $sec = new Security();

        $stmt = $this->conn->prepare("SELECT * FROM messages WHERE 1");

        $result = $stmt->execute();
        //$stmt->close();

        // check for successful get
        if ($result) {

            /* Get the result */
            $result = $stmt->get_result();

            echo '<div class="chata_box">';
              echo '<div class="chat_head"> Guest chat</div>';
              echo '<div class="chat_body">';

                  while ($data = $result->fetch_assoc()){
                    if (!filter_var($data['user_from'], FILTER_VALIDATE_IP) === false) {
                      $ipn = str_replace(".", "_", $data['user_from']);

                      $enc = $sec->encrypt_decrypt("encrypt",$data['user_from']);
                      echo '<div class="user" id="guest_'.$ipn.'"> '.$data['name_from'].'</div>';

                    }
                  }


              echo '</div>';
            echo '</div>';

        }
    }

    public function adminGetGuestChats(){
        include_once "security.php";
        $sec = new Security();

        $stmt = $this->conn->prepare("SELECT * FROM messages WHERE 1");

        $result = $stmt->execute();
        //$stmt->close();

        // check for successful get
        if ($result) {

            /* Get the result */
            $result = $stmt->get_result();


            while ($data = $result->fetch_assoc()){
              if (!filter_var($data['user_from'], FILTER_VALIDATE_IP) === false) {
                $ipn = str_replace(".", "_", $data['user_from']);

                echo '<div class="msg_box" id="guest_'.$ipn.'">';
                  echo '<div class="msg_head">'.$data["name_from"];
                    echo '<div class="closec">x</div>';
                  echo '</div>';
                  echo '<div class="msg_wrap">';
                    echo '<div class="msg_body">';
                      $this->pullChatAG('admin',$data['user_from']);
                      //$this->pullChatGA($data['user_from'],'admin');
                      echo '<div class="msg_push" id="guest_'.$ipn.'"></div>';
                    echo '</div>';
                    echo '<div class="msg_footer">';
                      echo '<textarea class="msg_input" rows="2" style="resize: none; color:#555;"></textarea>';
                    echo '</div>';
                  echo '</div>';
                echo '</div>';

              }
            }

        }
    }

    public function adminGetOrderOnTable(){
      $stmt = $this->conn->prepare("SELECT pesanan.id as peid, wo.id as wid, paket.id as pid, klien.id as kid,
                                           wo.name as wname, paket.name as pname, klien.name as kname,
                                           paket.price, paket.capacity, status, user_payment,
                                           wo_payment, pay_name, date_order, date_complete
                                    FROM pesanan
                                    INNER JOIN wo ON wo.id = pesanan.wo_id
                                    INNER JOIN klien ON klien.id = pesanan.klien_id
                                    INNER JOIN paket ON paket.id = pesanan.paket_id
                                    WHERE pesanan.wo_id = wo.id and pesanan.klien_id = klien.id
                                      and pesanan.paket_id = paket.id");
      //$stmt->bind_param("s", $wo_id);
      $result = $stmt->execute();
      //echo $stmt->error;
      //$stmt->close();

      // check for successful get
      if ($result) {
            /* Get the result */
            $result = $stmt->get_result();
            while ($data = $result->fetch_assoc()){
              echo "<tr>";
                echo "<td>".$data['pname']."</td>";
                echo "<td>".$data['kname']."</td>";
                echo "<td>".$data['wname']."</td>";
                echo "<td>".$data['date_order']."</td>";
                echo "<td>".$data['date_complete']."</td>";
                echo "<td>".$data['status']."</td>";
                echo "<td>".$data['user_payment']."</td>";
                echo "<td>".$data['wo_payment']."</td>";
                echo "<td>".$data['pay_name']."</td>";

                echo "<td style='text-align:center'>";
                  echo "<a style='padding: 0px 5px 0px 5px; cursor:pointer;' data-toggle='tooltip' title='Ubah'>";
                    echo "<img src='assets/css/images/write.png' id='btn-set' data-toggle='modal' data-title='Change transaction' data-target='#mdl_ont' data-wid='".$data['wid']."'/></a>";
                  echo "</a>";
                  echo "<a style='padding: 0px 5px 0px 5px; cursor:pointer;' data-toggle='tooltip' title='Hapus'>";
                    echo "<img src='assets/css/images/clear.png' data-toggle='modal' data-title='Delete order' data-msg='delete this order?' data-target='#mdl_confirm' data-href='index.php?page=delcl&o1=".$data['peid']."&o2=".$data['pname']."'/>";
                  echo "</a>";
                echo "</td>";

              echo "</tr>";
            }
              //return $stmt->get_result()->fetch_assoc();
      } else {
          //echo $stmt->error."</br>";
          //echo "error";
      }
    }

    public function deleteWO($id,$name){
        if (!empty($wid) && !empty($pid) && !empty($pname)){
          $stmt = $this->conn->prepare("DELETE FROM wo WHERE id = ? and name = ?");
          $stmt->bind_param("ss", $id, $pname);
        } else {
          return false;
        }

          // check for successful get
          if ($stmt->execute()) {
              return true;
          } else {
              //echo $stmt->error."</br>";
              return false;
          }

    }

    public function deleteClient($id,$name){
        if (!empty($wid) && !empty($pid) && !empty($pname)){
          $stmt = $this->conn->prepare("DELETE FROM klien WHERE id = ? and name = ?");
          $stmt->bind_param("ss", $id, $pname);
        } else {
          return false;
        }

          // check for successful get
          if ($stmt->execute()) {
              return true;
          } else {
              //echo $stmt->error."</br>";
              return false;
          }

    }

//------------ CLIENT HANDLE

    /**
     * Check user is existed or not
     */
    public function isClientExisted($mail) {
        $stmt = $this->conn->prepare("SELECT email from klien WHERE email = ?");
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $stmt->store_result();
        //echo $stmt->num_rows;
        echo $stmt->error;
        if ($stmt->num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            //echo $stmt->error."</br>";
            $stmt->close();
            return false;
        }
    }

    /**
     * Check client pass
     * returns client details
     */
    public function checkClientPass($mail,$pass){
          $stmt = $this->conn->prepare("SELECT salt,pass from klien WHERE email = ?");
          $stmt->bind_param("s", $mail);

          if ($stmt->execute()) {
              $user = $stmt->get_result()->fetch_assoc();
              $stmt->close();

              $salt = $user['salt'];
              $encrypted_password = $user['pass'];
              $hash = $this->checkhashSSHA($salt, $pass);
              // check for password equality
              if ($encrypted_password == $hash) {
                  // user authentication details are correct
                  return true;
              } else {
                  return false;
              }
          } else {
              //echo $stmt->error."</br>";
          }
    }

    /**
     * Storing new client
     * returns client details
     */
    public function storeClient($name, $gender, $mail, $phone, $address, $pass) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($pass);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO klien (id, name, gender, email, phone, address, pass, salt, created, confirmed) VALUES
                  ('', ?, ?, ?, ?, ?, ?, ?, NOW(),0)");
        $stmt->bind_param("sssssss", $name, $gender, $mail, $phone, $address, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * from klien WHERE email = ?");
            $stmt->bind_param("s", $mail);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return true;
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * Get user by email and password
     */
    public function getClientByEmailAndPassword($mail, $pass) {
        $stmt = $this->conn->prepare("SELECT * from klien WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['pass'];
            $hash = $this->checkhashSSHA($salt, $pass);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return true;
            }
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * Get user gender by email
     */
    public function getClientId($mail) {
        $stmt = $this->conn->prepare("SELECT id from klien WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['id'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user name by email
     */
    public function getClientName($mail) {
        $stmt = $this->conn->prepare("SELECT name from klien WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['name'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user name by email
     */
    public function getClientNameByID($id) {
        $stmt = $this->conn->prepare("SELECT name from klien WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['name'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user email by id
     */
    public function getClientEmailByID($id) {
        $stmt = $this->conn->prepare("SELECT email FROM klien WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['email'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }


    /**
     * Get user gender by email
     */
    public function getClientGender($mail) {
        $stmt = $this->conn->prepare("SELECT gender from klien WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['gender'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user email by email
     */
    public function getClientEmail($mail) {
        $stmt = $this->conn->prepare("SELECT email from klien WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['email'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user phone by email
     */
    public function getClientPhone($mail) {
        $stmt = $this->conn->prepare("SELECT phone from klien WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['phone'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user address by email
     */
    public function getClientAddress($mail) {
        $stmt = $this->conn->prepare("SELECT address from klien WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['address'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user confirm by email
     */
    public function getClientConfirm($mail) {
        $stmt = $this->conn->prepare("SELECT confirmed from klien WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['confirmed'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user gender by email
     */
    public function getClientConf($mail) {
        $stmt = $this->conn->prepare("SELECT confirmed from klien WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['confirmed'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }



    /**
     * Set user name by email
     */
    public function setClientName($mail,$name) {
        $stmt = $this->conn->prepare("UPDATE klien SET name = ? WHERE email = ?");
        $stmt->bind_param("ss", $name, $mail);

        return $stmt->execute();
    }

        /**
         * Set user name by email
         */
    public function setClientPass($mail,$pass) {
        $hash = $this->hashSSHA($pass);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("UPDATE klien SET pass = ?, salt = ? WHERE email = ?");
        $stmt->bind_param("sss", $encrypted_password, $salt, $mail);

        return $stmt->execute();
    }

    /**
     * Set user gender by email
     */
    public function setClientGender($mail,$gender) {
        $stmt = $this->conn->prepare("UPDATE klien SET gender = ? WHERE email = ?");
        $stmt->bind_param("ss", $gender, $mail);

        return $stmt->execute();
    }

    /**
     * Set user email by email
     */
    public function setClientEmail($mailo,$mailn) {
        $stmt = $this->conn->prepare("UPDATE klien SET email = ? WHERE email = ?");
        $stmt->bind_param("ss", $mailn, $mailo);

        return $stmt->execute();
    }

    /**
     * Set user phone by email
     */
    public function setClientPhone($mail,$phone) {
        $stmt = $this->conn->prepare("UPDATE klien SET phone = ? WHERE email = ?");
        $stmt->bind_param("ss", $phone, $mail);

        return $stmt->execute();
    }

    /**
     * Set user address by email
     */
    public function setClientAddress($mail,$address) {
        $stmt = $this->conn->prepare("UPDATE klien SET address = ? WHERE email = ?");
        $stmt->bind_param("ss", $address, $mail);

        return $stmt->execute();
    }

    /**
     * Set user confirmation by email
     */
    public function setClientConfirm($mail) {
        $stmt = $this->conn->prepare("UPDATE klien SET confirmed = 1 WHERE email = ?");
        $stmt->bind_param("s", $mail);

        return $stmt->execute();
    }


//------------ WO HANDLE

    /**
     * Check user is existed or not
     */
    public function isWOExisted($mail) {
        $stmt = $this->conn->prepare("SELECT email from wo WHERE email = ?");
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            //echo $stmt->error."</br>";
            return false;
        }
    }


    /**
     * Check user is existed or not
     */
    public function isWOExistedByID($id) {
        $stmt = $this->conn->prepare("SELECT * from wo WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * Check client pass
     * returns client details
     */
    public function checkWOPass($mail,$pass){
          $stmt = $this->conn->prepare("SELECT salt,pass from wo WHERE email = ?");
          $stmt->bind_param("s", $mail);

          if ($stmt->execute()) {
              $user = $stmt->get_result()->fetch_assoc();
              $stmt->close();

              $salt = $user['salt'];
              $encrypted_password = $user['pass'];
              $hash = $this->checkhashSSHA($salt, $pass);
              // check for password equality
              if ($encrypted_password == $hash) {
                  // user authentication details are correct
                  return true;
              } else {
                  return false;
              }
          } else {
            //echo $stmt->error."</br>";
          }
    }

    /**
     * Storing new wo
     * returns wo details
     */
    public function storeWO($name, $owner, $mail, $phone, $address, $pass) {

        $hash = $this->hashSSHA($pass);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO wo (id, name, owner, email, phone, address, deskripsi, pass, salt, created, approved_by, approved) VALUES
                  ('', ?, ?, ?, ?, ?, '', ?, ?, NOW(), '1', 'NULL')");

        $stmt->bind_param("sssssss", $name, $owner, $mail, $phone, $address, $encrypted_password, $salt);
        //echo "bind";
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM wo WHERE email = ?");
            $stmt->bind_param("s", $mail);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return true;
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }



    /**
     * Get the wo package
     * returns wo details
     */
    public function getWODetails() {

      $stmt = $this->conn->prepare("SELECT * FROM wo");

          $result = $stmt->execute();
          //$stmt->close();

            // check for successful get
            if ($result) {
                /* Get the result */
                $result = $stmt->get_result();
                //$stmt->close;
                
				echo '<section id="thumbnails">';

                while ($data = $result->fetch_assoc()){
                    echo '<article>';
                      if (file_exists('images/banner/banner_'.$data["id"].'.jpg') )
                        echo '<a class="thumbnail" href="images/banner/banner_'.$data["id"].'.jpg" data-position="left center"><img src="images/banner/banner_'.$data["id"].'.jpg" alt="NO BANNER YET" /></a>';
                      else
                        echo '<a class="thumbnail" href="assets/img/thumbs/01.jpg" data-position="left center"><img src="assets/img/thumbs/01.jpg" alt="BANNER" /></a>';

                      echo '<h2><a href="index.php?wo='.$data["id"].'">'.$data["name"].'</a></h2>';

                      echo '<p>';
                        echo $data["deskripsi"];
                      echo '</p>';
                    echo '</article>';
                }
                echo '</section>';
                //return $stmt->get_result()->fetch_assoc();
            } else {
                //echo $stmt->error."</br>";
                //echo "error";
            }
    }

    /**
     * Get the wo package
     * returns wo details
     */
    public function getWODetailsByQuery($key) {

      $stmt = $this->conn->prepare("SELECT * FROM wo where name LIKE '%$key%'");

          $result = $stmt->execute();
          //$stmt->close();

            // check for successful get
            if ($result) {
                /* Get the result */
                $result = $stmt->get_result();
                //$stmt->close;

				echo '<section id="thumbnails">';
                while ($data = $result->fetch_assoc()){
                    echo '<article>';
                      if (file_exists('images/banner/banner_'.$data["id"].'.jpg') )
                        echo '<a class="thumbnail" href="images/banner/banner_'.$data["id"].'.jpg" data-position="left center"><img src="images/banner/banner_'.$data["id"].'.jpg" alt="NO BANNER YET" /></a>';
                      else
                        echo '<a class="thumbnail" href="assets/img/thumbs/01.jpg" data-position="left center"><img src="assets/img/thumbs/01.jpg" alt="BANNER" /></a>';

                      echo '<h2><a href="index.php?wo='.$data["id"].'">'.$data["name"].'</a></h2>';

                      echo '<p>';
                        echo $data["deskripsi"];
                      echo '</p>';
                    echo '</article>';
                }
                echo '</section>';
                //return $stmt->get_result()->fetch_assoc();
            } else {
                //echo $stmt->error."</br>";
                //echo "error";
            }
    }
    
    /**
     * Get user by email and password
     */
    public function getWOByEmailAndPassword($mail, $pass) {
        $stmt = $this->conn->prepare("SELECT * FROM wo WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['pass'];
            $hash = $this->checkhashSSHA($salt, $pass);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return true;
            } else {
                return false;
            }
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * Get user id by email
     */
    public function getWOId($mail) {
        $stmt = $this->conn->prepare("SELECT id FROM wo WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['id'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user name by email
     */
    public function getWODeskripsi($mail) {
        $stmt = $this->conn->prepare("SELECT deskripsi FROM wo WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['deskripsi'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user name by email
     */
    public function getWOName($mail) {
        $stmt = $this->conn->prepare("SELECT name FROM wo WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['name'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user owner by email
     */
    public function getWOOwner($mail) {
        $stmt = $this->conn->prepare("SELECT owner FROM wo WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user owner
            $user = $user['owner'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user email by email
     */
    public function getWOEmail($mail) {
        $stmt = $this->conn->prepare("SELECT email FROM wo WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['email'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user phone by email
     */
    public function getWOPhone($mail) {
        $stmt = $this->conn->prepare("SELECT phone FROM wo WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['phone'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user address by email
     */
    public function getWOAddress($mail) {
        $stmt = $this->conn->prepare("SELECT address FROM wo WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['address'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user gender by email
     */
    public function getWOApprv($mail) {
        $stmt = $this->conn->prepare("SELECT approved from wo WHERE email = ?");
        $stmt->bind_param("s", $mail);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['approved'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }



    /**
     * Get user name by id
     */
    public function getWONameByID($id) {
        $stmt = $this->conn->prepare("SELECT name FROM wo WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['name'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user owner by id
     */
    public function getWOOwnerByID($id) {
        $stmt = $this->conn->prepare("SELECT owner FROM wo WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user owner
            $user = $user['owner'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user email by id
     */
    public function getWOEmailByID($id) {
        $stmt = $this->conn->prepare("SELECT email FROM wo WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['email'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user phone by id
     */
    public function getWOPhoneByID($id) {
        $stmt = $this->conn->prepare("SELECT phone FROM wo WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['phone'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user address by id
     */
    public function getWOAddressByID($id) {
        $stmt = $this->conn->prepare("SELECT address FROM wo WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['address'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get user deskripsi by id
     */
    public function getWODeskripsiByID($id) {
        $stmt = $this->conn->prepare("SELECT deskripsi FROM wo WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['deskripsi'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }


    /**
     * Set user name by email
     */
    public function setWODesk($mail,$desk) {
        $stmt = $this->conn->prepare("UPDATE wo SET deskripsi = ? WHERE email = ?");
        $stmt->bind_param("ss", $desk, $mail);

        return $stmt->execute();
    }

    /**
     * Set user name by email
     */
    public function setWOName($mail,$name) {
        $stmt = $this->conn->prepare("UPDATE wo SET name = ? WHERE email = ?");
        $stmt->bind_param("ss", $name, $mail);

        return $stmt->execute();
    }

        /**
         * Set user name by email
         */
    public function setWOPass($mail,$pass) {
        $hash = $this->hashSSHA($pass);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("UPDATE wo SET name = ? WHERE email = ?");
        $stmt->bind_param("ss", $name, $mail);

        return $stmt->execute();
    }

    /**
     * Set user gender by email
     */
    public function setWOGender($mail,$gender) {
        $stmt = $this->conn->prepare("UPDATE wo SET gender = ? WHERE email = ?");
        $stmt->bind_param("ss", $gender, $mail);

        return $stmt->execute();
    }

    /**
     * Set user email by email
     */
    public function setWOEmail($mailo,$mailn) {
        $stmt = $this->conn->prepare("UPDATE wo SET email = ? WHERE email = ?");
        $stmt->bind_param("ss", $mailn, $mailo);

        return $stmt->execute();
    }

    /**
     * Set user phone by email
     */
    public function setWOPhone($mail,$phone) {
        $stmt = $this->conn->prepare("UPDATE wo SET phone = ? WHERE email = ?");
        $stmt->bind_param("ss", $phone, $mail);

        return $stmt->execute();
    }

    /**
     * Set user address by email
     */
    public function setWOAddress($mail,$address) {
        $stmt = $this->conn->prepare("UPDATE wo SET address = ? WHERE email = ?");
        $stmt->bind_param("ss", $address, $mail);

        return $stmt->execute();
    }


//------------ PASSWORD HANDLE

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

//------------ PACKAGE HANDLE


    /**
     * Storing new package wo
     * returns wo details
     */
    public function storeWOPackage($wo_id, $name, $price, $cpct) {
        //$mt = microtime();
        $ts = date("Y-m-d");
        $idx = hash('crc32',$wo_id.$name.$ts);
        echo $wo_id.$name.$ts."</br>".$idx;
        $stmt = $this->conn->prepare("INSERT INTO paket (id, wo_id, name, price, capacity, created) VALUES
                  (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $idx, $wo_id, $name, $price, $cpct, $ts);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM paket WHERE id = ?");
            $stmt->bind_param("s", $idx);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return true;
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * check packages wo
     * returns wo details
     */
    public function checkWOPackages($id, $name, $price, $cpct) {
        $stmt = $this->conn->prepare("SELECT * FROM paket WHERE id = ? and name = ? and price = ? and capacity = ?");
        $stmt->bind_param("ssss", $id, $name, $price, $cpct);
        $result = $stmt->execute();
        //$stmt->close();

        // check for successful store
        if ($result) {
          $stmt->store_result();
            echo $stmt->num_rows;
            if ($stmt->num_rows > 0) return true;
            else return false;
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * Storing new fasilitas wo
     * returns wo details
     */
    public function checkWOFasilitas($id, $type, $item, $total) {
        $stmt = $this->conn->prepare("SELECT * FROM fasilitas WHERE paket_id = ? and type = ? and item = ?");
        $stmt->bind_param("sss", $id, $type, $item);
        $result = $stmt->execute();
        //$stmt->close();

        // check for successful store
        if ($result) {
          $stmt->store_result();
            echo $stmt->num_rows;
            if ($stmt->num_rows > 0) return true;
            else return false;
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * Update packages wo
     * returns wo details
     */
    public function updateWOPackages($id, $name, $price, $cpct, $oid, $oname) {
        //echo "select created ";
        $istmt = $this->conn->prepare("SELECT created, wo_id FROM paket WHERE id=?");
        $istmt->bind_param("s", $id);
        $iresult = $istmt->execute();
        //echo $idata['created']."</br>";
        $idata = $istmt->get_result()->fetch_assoc();
        $istmt->close();

        $ts = date("Y-m-d");
        $idx = hash('crc32',$idata['wo_id'].$name.$ts);
        //echo "new id ".$idx."</br>";
        $stmt = $this->conn->prepare("UPDATE paket SET id=?,name=?,price=?,capacity=?, created=NOW()
                                      WHERE id=? and name=?");
        $stmt->bind_param("ssssss", $idx, $name, $price, $cpct, $oid, $oname);
        $result = $stmt->execute();
        $stmt->close();
        //echo "Update WO Package</br>";
        // check for successful store
        if ($result) {
            $dstmt = $this->conn->prepare("DELETE FROM paket WHERE id = ? and name =?");
            $dstmt->bind_param("ss", $oid, $oname);
            $dresult = $dstmt->execute();
            $dstmt->close();
            if ($dresult) {
              return true;
            }else {
              //echo $dstmt->error."</br>";
              return false;
            }
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * Update fasilitas wo
     * returns wo details
     */
    public function updateWOFasilitas($id, $type, $item, $total, $oid, $oitem) {

        $stmt = $this->conn->prepare("UPDATE fasilitas SET paket_id=?,type=?,item=?,total=?
                                                         WHERE paket_id=? and item=?");
        $stmt->bind_param("ssssss", $id, $type, $item, $total, $oid, $oitem);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $dstmt = $this->conn->prepare("DELETE FROM fasilitas WHERE paket_id = ? and item =?");
            $dstmt->bind_param("ss", $oid, $oitem);
            $dresult = $dstmt->execute();
            $dstmt->close();
            if ($dresult) {
              return true;
            }else {
              //echo $dstmt->error."</br>";
              return false;
            }
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }


    /**
     * Storing new fasilitas wo
     * returns wo details
     */
    public function storeWOFasilitas($id, $type, $item, $total) {
        $stmt = $this->conn->prepare("INSERT INTO fasilitas (paket_id, type, item, total) VALUES
                      (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id, $type, $item, $total);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT paket_id FROM fasilitas WHERE paket_id = ? and item =?");
            $stmt->bind_param("ss", $id, $item);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return true;
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * Get the wo package
     * returns wo details
     */
    public function isWOPackageExists($id) {
      if (!empty($id)){
        $stmt = $this->conn->prepare("SELECT * FROM paket WHERE id = ?");
        $stmt->bind_param("s", $id);
      } else {
        $stmt = $this->conn->prepare("SELECT * FROM paket");
      }

        // check for successful get
        if ($stmt->execute()) {
            return true;
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * Get package name by id
     */
    public function getWOPackageNameByID($id) {
        $stmt = $this->conn->prepare("SELECT name from paket WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user name
            $user = $user['name'];
            return $user;
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get the wo package
     * returns wo details
     */
    public function getWOPackageOnTable($wo_id) {

      if (!empty($wo_id)){
        $stmt = $this->conn->prepare("SELECT * FROM paket WHERE wo_id = ?");
        $stmt->bind_param("s", $wo_id);
      } else {
        //echo "error";
      }

      $result = $stmt->execute();
      //$stmt->close();

        // check for successful get
        if ($result) {

            /* Get the result */
            $result = $stmt->get_result();

            while ($data = $result->fetch_assoc()){
                echo "<tr>";
                  echo "<td>".$data['name']."</td>";
                  echo "<td>".$data['capacity']."</td>";
                  echo "<td>".$data['price']."</td>";
                  echo "<td style='text-align:center'>";
                    echo "<a style='padding: 0px 5px 0px 5px; cursor:pointer;' data-toggle='tooltip' title='Ubah'>";
                      echo "<img src='assets/css/images/write.png' id='btn-set' data-toggle='modal' data-title='Edit paket' data-target='#mdl_pupdate' data-oid='".$data['id']."' data-oname='".$data['name']."' data-name='".$data['name']."' data-cpct='".$data['capacity']."' data-price='".$data['price']."'/>";
                    echo "</a>";
                    echo "<a style='padding: 0px 5px 0px 5px; cursor:pointer;' data-toggle='tooltip' title='Hapus'>";
                      echo "<img src='assets/css/images/clear.png' data-toggle='modal' data-title='Delete package' data-msg='delete this package?' data-target='#mdl_confirm' data-href='index.php?page=delp&o1=".$wo_id."&o2=".$data['id']."&o3=".$data['name']."'/>";
                    echo "</a>";
                  echo "</td>";
                echo "</tr>";
            }
            //return $stmt->get_result()->fetch_assoc();
        } else {
            //echo $stmt->error."</br>";
            //echo "error";
        }
    }

    /**
     * Get the wo package
     * returns wo details
     */
    public function getWOPackageOption($wo_id) {

      if (!empty($wo_id)){
        $stmt = $this->conn->prepare("SELECT id,name FROM paket WHERE wo_id = ?");
        $stmt->bind_param("s", $wo_id);
      } else {
        $stmt = $this->conn->prepare("SELECT * FROM paket");
      }

      $result = $stmt->execute();
      //$stmt->close();

        // check for successful get
        if ($result) {

            /* Get the result */
            $result = $stmt->get_result();

            while ($data = $result->fetch_assoc()){
                echo "<option value='".$data['id']."'>".$data['name']."</option>";
            }
            //return $stmt->get_result()->fetch_assoc();
        } else {
            //echo $stmt->error."</br>";
            //echo "error";
        }
    }


    /**
     * Get the wo package
     * returns wo details
     */
    public function getWOPackageDetails() {

      $pstmt = $this->conn->prepare("SELECT wo.id as wid, wo.name as wname, paket.name as pname,
                                            paket.price as pprice, paket.capacity, paket.id as pid
                                     FROM wo INNER JOIN paket ON wo.id = paket.wo_id
                                     WHERE wo.id = paket.wo_id ");

      $presult = $pstmt->execute();
      //$stmt->close();

        // check for successful get
        if ($presult) {
            /* Get the result */
            $presult = $pstmt->get_result();
            //$stmt->close;
            require_once "auth.php";
            $auth = new auth();
			echo '<section id="thumbnails">';
            while ($pdata = $presult->fetch_assoc()){
                echo '<article>';
                  $gambare = "images/banner/banner_".$pdata['wid'].".jpg";
                  if (file_exists($gambare) == true)
                    echo '<a class="thumbnail" href="images/banner/banner_'.$pdata["wid"].'.jpg" data-position="left center"><img src="images/banner/banner_'.$pdata["wid"].'.jpg" alt="" /></a>';
                  else
                    echo '<a class="thumbnail" href="assets/img/thumbs/01.jpg" data-position="left center"><img src="assets/img/thumbs/01.jpg" alt="" /></a>';
                  
                  if ($auth->isClient() == true){
                    $id = $this->getClientId($_SESSION['user']);
                    echo '<h2><a href="index.php?page=packages&pckg='.$pdata["pid"].'">'.$pdata["pname"].'</a> [Rp'.$pdata["pprice"].'] - By: <a href="index.php?wo='.$pdata["wid"].'">'.$pdata["wname"].'</a></h2>';
                    if ($this->isClientAlreadyOrder($id)== false)
                      echo '<button style="color:#fff" data-toggle="modal" data-target="#mdl_confirm" data-title="Order paket '.$pdata["wname"].'" data-msg="Apakah anda akan memesan paket ini?" data-href="index.php?page=orders&o='.$pdata["pid"].'">Order</button>';
                  } else if ($auth->isGuest() == true) {
                    echo '<h2>'.$pdata["pname"].' [Rp'.$pdata["pprice"].'] - By: <a href="index.php?wo='.$pdata["wid"].'">'.$pdata["wname"].'</a></h2>';
                    echo '<a href="client.php" style="border-bottom:none;"><button style="color:#fff">Sign in to order</button> </a>';
                  } else if ($auth->isAdmin() == true){
                    echo '<h2>'.$pdata["pname"].' [Rp'.$pdata["pprice"].'] - By: <a href="index.php?wo='.$pdata["wid"].'">'.$pdata["wname"].'</a></h2>';

                  }

                    // enumerasi fasilitas
                    $fstmt = $this->conn->prepare("SELECT wo.id as wid, wo.name as wname, paket.name as pname,
                                                paket.price, paket.capacity, paket.id as pid,
                                                type, item, total
            									                  FROM wo
                                                INNER JOIN paket ON ? = paket.wo_id
                                                INNER JOIN fasilitas ON ? = fasilitas.paket_id
                                                WHERE wo.id = paket.wo_id and fasilitas.paket_id = paket.id");
                    $fstmt->bind_param("ss", $pdata["wid"], $pdata["pid"]);

                      if ($fstmt->execute()){
                          $fresult = $fstmt->get_result();
                          //$fstmt->close;
                          $cat = "Catering : ";
                          $rias = "Rias : ";
                          $hiburan = "Hiburan : ";
                          $dekorasi = "Dekorasi : ";
                          $hotel = "Hotel : ";
                          $dokumentasi = "Dokumentasi : ";
                          $transportasi = "Transportasi : ";
                          $gedung = "Gedung : ";
                          $undangan = "Undangan : ";
                          $souvenir = "Souvenir : ";
                          $jasalain = "Jasa lain : ";

                          while ($fdata = $fresult->fetch_assoc()){
                              //echo '<strong>'.$fdata["type"]."</strong>: ".$fdata["item"].' '.$fdata["total"].' ';
                              if ($fdata["type"] == "CATERING") $cat = $cat.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "RIAS") $rias = $rias.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "HIBURAN") $hiburan = $hiburan.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "DEKORASI") $dekorasi = $dekorasi.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "HOTEL") $hotel = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "DOKUMENTASI") $dokumentasi = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "TRANSPORTASI") $transportasi = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "GEDUNG") $gedung = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "UNDANGAN") $undangan = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "SOUVENIR") $souvenir = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "JASA-LAIN") $jasalain = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                          }
                      }
                  $facs = $cat."|".$rias."|".$hiburan."|".$dekorasi."|".$hotel."|".$dokumentasi."|".$transportasi."|".$gedung."|".$undangan."|".$souvenir."|".$jasalain;
                  $list = str_replace('|', '&#13;&#10;', $facs);

                  echo '<textarea disabled style="resize: none; margin-top:1em;">';
                    echo $list;
                  echo '</textarea>';
                echo '</article>';
            }
            echo '</section>';
            //return $stmt->get_result()->fetch_assoc();
        } else {
            //echo $stmt->error."</br>";
            //echo "error";
        }
    }

    /**
     * Get the wo package
     * returns wo details
     */
    public function getWOPackageDetailsByQuery($key) {

      //$stmt = $this->conn->prepare("SELECT * FROM wo where name LIKE '%$key%'");
      $pstmt = $this->conn->prepare("SELECT wo.id as wid, wo.name as wname, paket.name as pname,
                                            paket.price as pprice, paket.capacity, paket.id as pid
                                     FROM wo INNER JOIN paket ON wo.id = paket.wo_id
                                     WHERE wo.id = paket.wo_id and (paket.name LIKE '%$key%' or wo.name LIKE '%$key%')");

      $presult = $pstmt->execute();
      //$stmt->close();

        // check for successful get
        if ($presult) {
            /* Get the result */
            $presult = $pstmt->get_result();
            //$stmt->close;
            require_once "auth.php";
            $auth = new auth();
			echo '<section id="thumbnails">';
            while ($pdata = $presult->fetch_assoc()){
                echo '<article>';
                  echo '<a class="thumbnail" href="images/banner/banner_'.$pdata["wid"].'.jpg" data-position="left center"><img src="images/banner/banner_'.$pdata["wid"].'.jpg" alt="" /></a>';
                  if ($auth->isClient() == true){
                    $id = $this->getClientId($_SESSION['user']);
                    echo '<h2><a href="index.php?page=packages&pckg='.$pdata["pid"].'">'.$pdata["pname"].'</a> [Rp'.$pdata["pprice"].'] - By: <a href="index.php?wo='.$pdata["wid"].'">'.$pdata["wname"].'</a></h2>';
                    if ($this->isClientAlreadyOrder($id)== false)
                      echo '<button style="color:#fff" data-toggle="modal" data-target="#mdl_confirm" data-title="Order paket '.$pdata["wname"].'" data-msg="Apakah anda akan memesan paket ini?" data-href="index.php?page=orders&o='.$pdata["pid"].'">Order</button>';
                  } else if ($auth->isGuest() == true) {
                    echo '<h2>'.$pdata["pname"].' [Rp'.$pdata["pprice"].'] - By: <a href="index.php?wo='.$pdata["wid"].'">'.$pdata["wname"].'</a></h2>';
                    echo '<a href="client.php" style="border-bottom:none;"><button style="color:#fff">Sign in to order</button> </a>';
                  } else if ($auth->isAdmin() == true){
                    echo '<h2>'.$pdata["pname"].' [Rp'.$pdata["pprice"].'] - By: <a href="index.php?wo='.$pdata["wid"].'">'.$pdata["wname"].'</a></h2>';

                  }

                    // enumerasi fasilitas
                    $fstmt = $this->conn->prepare("SELECT wo.id as wid, wo.name as wname, paket.name as pname,
                                                paket.price, paket.capacity, paket.id as pid,
                                                type, item, total
            									                  FROM wo
                                                INNER JOIN paket ON ? = paket.wo_id
                                                INNER JOIN fasilitas ON ? = fasilitas.paket_id
                                                WHERE wo.id = paket.wo_id and fasilitas.paket_id = paket.id");
                    $fstmt->bind_param("ss", $pdata["wid"], $pdata["pid"]);

                      if ($fstmt->execute()){
                          $fresult = $fstmt->get_result();
                          //$fstmt->close;
                          $cat = "Catering : ";
                          $rias = "Rias : ";
                          $hiburan = "Hiburan : ";
                          $dekorasi = "Dekorasi : ";
                          $hotel = "Hotel : ";
                          $dokumentasi = "Dokumentasi : ";
                          $transportasi = "Transportasi : ";
                          $gedung = "Gedung : ";
                          $undangan = "Undangan : ";
                          $souvenir = "Souvenir : ";
                          $jasalain = "Jasa lain : ";

                          while ($fdata = $fresult->fetch_assoc()){
                              //echo '<strong>'.$fdata["type"]."</strong>: ".$fdata["item"].' '.$fdata["total"].' ';
                              if ($fdata["type"] == "CATERING") $cat = $cat.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "RIAS") $rias = $rias.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "HIBURAN") $hiburan = $hiburan.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "DEKORASI") $dekorasi = $dekorasi.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "HOTEL") $hotel = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "DOKUMENTASI") $dokumentasi = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "TRANSPORTASI") $transportasi = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "GEDUNG") $gedung = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "UNDANGAN") $undangan = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "SOUVENIR") $souvenir = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "JASA-LAIN") $jasalain = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                          }
                      }
                  $facs = $cat."|".$rias."|".$hiburan."|".$dekorasi."|".$hotel."|".$dokumentasi."|".$transportasi."|".$gedung."|".$undangan."|".$souvenir."|".$jasalain;
                  $list = str_replace('|', '&#13;&#10;', $facs);

                  echo '<textarea disabled style="resize: none; margin-top:1em;">';
                    echo $list;
                  echo '</textarea>';
                echo '</article>';
            }
            echo '</section>';
            //return $stmt->get_result()->fetch_assoc();
        } else {
            //echo $stmt->error."</br>";
            //echo "error";
        }
    }

    /**
     * Get the wo package
     * returns wo details
     */
    public function getWOPackageDetails2($wo_id) {

        $stmt = $this->conn->prepare("SELECT  wo.id as wid, wo.name as wname, paket.name as pname,
                                              paket.price as pprice, paket.capacity, paket.id as pid
                                      FROM wo INNER JOIN paket ON wo.id = paket.wo_id
                                      WHERE wo_id = ? and  wo.id = paket.wo_id");
        $stmt->bind_param("s", $wo_id);
        $result = $stmt->execute();
        //$stmt->close();

        // check for successful get
        if ($result) {
              /* Get the result */
              $result = $stmt->get_result();
              //$stmt->close;
              require_once "auth.php";
              $auth = new auth();
              while ($data = $result->fetch_assoc()){
                if ($auth->isClient() == true){
                  $id = $this->getClientId($_SESSION['user']);

    							echo '<article class="6u 12u$(xsmall) work-item">';
                    if ($this->isClientAlreadyOrder($id)== false){
  								    echo '<a class="image fit thumb" style="cursor:pointer" data-toggle="modal" data-target="#mdl_confirm" data-title="Order paket '.$data["wname"].'" data-msg="Apakah anda akan memesan paket ini?" data-href="index.php?page=orders&o='.$data["pid"].'">';
                        echo '<img src="images/banner/banner_'.$data["wid"].'.jpg" alt="" /></a>';
                    } else{
                      echo '<a class="image fit"><img src="images/banner/banner_'.$data["wid"].'.jpg" alt="" /></a>';
                    }
      							echo '<h3>'.$data["pname"].'</h3>';
      							echo '<p>Kapasitas :'.$data["capacity"].' - Rp '.$data["pprice"].'</p>';
      						echo '</article>';
                } else {
                  echo '<article class="6u 12u$(xsmall) work-item">';
                    echo '<a class="image fit"><img src="images/banner/banner_'.$data["wid"].'.jpg" alt="" /></a>';
                    echo '<h3>'.$data["pname"].'</h3>';
                    echo '<p>Kapasitas :'.$data["capacity"].' - Rp '.$data["pprice"].'</p>';
                  echo '</article>';
                }
              }
                //return $stmt->get_result()->fetch_assoc();
        } else {
            //echo $stmt->error."</br>";
            //echo "error";
        }
    }


    /**
     * Get the wo package
     * returns wo details
     */
    public function getWOOrderDetails($wo_id) {
        $stmt = $this->conn->prepare("SELECT wo.id as wid, paket.id as pid, klien.id as kid,
		                                         wo.name as wname, paket.name as pname, klien.name as kname,
                                             paket.price, paket.capacity
                                      FROM pesanan
                                      INNER JOIN wo ON wo.id = pesanan.wo_id
                                      INNER JOIN klien ON klien.id = pesanan.klien_id
                                      INNER JOIN paket ON paket.id = pesanan.paket_id
                                      WHERE pesanan.wo_id = ? and pesanan.klien_id = klien.id
                                      	and pesanan.paket_id = paket.id");
        $stmt->bind_param("s", $wo_id);
        $result = $stmt->execute();
        //$stmt->close();

        // check for successful get
        if ($result) {
              /* Get the result */
              $result = $stmt->get_result();
              //$stmt->close;
              require_once "auth.php";
              $auth = new auth();
              while ($data = $result->fetch_assoc()){

    							echo '<article class="6u 12u$(xsmall) work-item">';
  								  echo '<a class="image fit"><img src="images/banner/banner_'.$data["wid"].'.jpg" alt="" /></a>';
      							echo '<h3>'.$data["pname"].' Order By '.$data["kname"].'</h3>';
      							echo '<p>Kapasitas :'.$data["capacity"].' - Rp '.$data["price"].'</p>';
      						echo '</article>';

              }
                //return $stmt->get_result()->fetch_assoc();
        } else {
              //echo $stmt->error."</br>";
              //echo "error";
        }
    }



    /**
     * Get the wo package
     * returns wo details
     */
    public function getWOPackageDF($pid) {

      $pstmt = $this->conn->prepare("SELECT wo.id as wid, wo.name as wname, paket.name as pname,
                                            paket.price as pprice, paket.capacity, paket.id as pid
                                     FROM wo INNER JOIN paket ON wo.id = paket.wo_id
                                     WHERE wo.id = paket.wo_id and paket.id = ? ");
      $pstmt->bind_param("s", $pid);
      $presult = $pstmt->execute();
      //$stmt->close();

        // check for successful get
        if ($presult) {
            /* Get the result */
            $presult = $pstmt->get_result();
            //$stmt->close;
            require_once "auth.php";
            $auth = new auth();
            while ($pdata = $presult->fetch_assoc()){
              echo '<div class="capt">';
                if ($auth->isClient() == true){
                  echo '<h2><a href="index.php?page=packages&pckg='.$pdata["pid"].'">'.$pdata["pname"].'</a> [Rp'.$pdata["pprice"].'] - By: <a href="index.php?wo='.$pdata["wid"].'">'.$pdata["wname"].'</a></h2>';
                  echo '<button data-toggle="modal" data-target="#mdl_confirm" data-title="Order paket '.$pdata["wname"].'" data-msg="Apakah anda akan memesan paket ini?" data-href="index.php?page=orders&o='.$pdata["pid"].'">Order</button>';
                } else if ($auth->isGuest() == true) {
                  echo '<h2>'.$pdata["pname"].' [Rp'.$pdata["pprice"].'] - By: <a href="index.php?wo='.$pdata["wid"].'">'.$pdata["wname"].'</a></h2>';
                  echo '<a href="client.php" style="border-bottom:none;"><button>Sign in to order</button> </a>';
                }

                    // enumerasi fasilitas
                    $fstmt = $this->conn->prepare("SELECT wo.id as wid, wo.name as wname, paket.name as pname,
                                                paket.price, paket.capacity, paket.id as pid,
                                                type, item, total
            									                  FROM wo
                                                INNER JOIN paket ON ? = paket.wo_id
                                                INNER JOIN fasilitas ON ? = fasilitas.paket_id
                                                WHERE wo.id = paket.wo_id and fasilitas.paket_id = paket.id");
                    $fstmt->bind_param("ss", $pdata["wid"], $pdata["pid"]);

                    if ($fstmt->execute()){
                          $fresult = $fstmt->get_result();
                          //$fstmt->close;

                          $cat = "Catering : ";
                          $rias = "Rias : ";
                          $hiburan = "Hiburan : ";
                          $dekorasi = "Dekorasi : ";
                          $hotel = "Hotel : ";
                          $dokumentasi = "Dokumentasi : ";
                          $transportasi = "Transportasi : ";
                          $gedung = "Gedung : ";
                          $undangan = "Undangan : ";
                          $souvenir = "Souvenir : ";
                          $jasalain = "Jasa lain : ";

                          while ($fdata = $fresult->fetch_assoc()){
                              //echo '<strong>'.$fdata["type"]."</strong>: ".$fdata["item"].' '.$fdata["total"].' ';
                              if ($fdata["type"] == "CATERING") $cat = $cat.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "RIAS") $rias = $rias.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "HIBURAN") $hiburan = $hiburan.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "DEKORASI") $dekorasi = $dekorasi.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "HOTEL") $hotel = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "DOKUMENTASI") $dokumentasi = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "TRANSPORTASI") $transportasi = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "GEDUNG") $gedung = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "UNDANGAN") $undangan = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "SOUVENIR") $souvenir = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                              if ($fdata["type"] == "JASA-LAIN") $jasalain = $hotel.$fdata["item"].' ('.$fdata["total"].') ';
                          }
                      }
                  $facs = $cat."|".$rias."|".$hiburan."|".$dekorasi."|".$hotel."|".$dokumentasi."|".$transportasi."|".$gedung."|".$undangan."|".$souvenir."|".$jasalain;
                  $list = str_replace('|', '&#13;&#10;', $facs);
                  echo '<textarea disabled style="resize: none; margin-top:1em">';
                    echo $list;
                  echo '</textarea>';
                  echo '<div class="imag" style="background-position: left center; background-image: url(&quot;assets/img/fully/01.jpg&quot;);"></div';
                echo '</div>';
            }
            //return $stmt->get_result()->fetch_assoc();
        } else {
            //echo $stmt->error."</br>";
            //echo "error";
        }
    }

    /**
     * Get the wo package
     * returns wo details
     */
    public function getWOFacilityOnTable($wo_id) {

      if (!empty($wo_id)){
        $stmt = $this->conn->prepare("SELECT * FROM paket p, fasilitas f
                                      WHERE wo_id = ? and p.id = f.paket_id");
        $stmt->bind_param("s", $wo_id);
      } else {
        $stmt = $this->conn->prepare("SELECT * FROM fasilitas");
      }

      $result = $stmt->execute();
      //$stmt->close();

        // check for successful store
        if ($result) {

            /* Get the result */
            $result = $stmt->get_result();

            while ($data = $result->fetch_assoc()){
                echo "<tr>";
                  echo "<td>".$data['name']."</td>";
                  echo "<td>".$data['type']."</td>";
                  echo "<td>".$data['item']."</td>";
                  echo "<td>".$data['total']."</td>";
                  echo "<td style='text-align:center'>";
                    echo "<a style='padding: 0px 5px 0px 5px; cursor:pointer;' data-toggle='tooltip' title='Ubah'>";
                      echo "<img src='assets/css/images/write.png' id='btn-set' data-toggle='modal' data-title='Edit fasilitas' data-target='#mdl_fasilitasu' data-paket='".$data['paket_id']."' data-tipe='".$data['type']."' data-item='".$data['item']."' data-total='".$data['total']."'/></a>";
                    echo "<a style='padding: 0px 5px 0px 5px; cursor:pointer;' data-toggle='tooltip' title='Hapus'>";
                      echo "<img src='assets/css/images/clear.png' id='btn-del' data-toggle='modal' data-title='Delete facility' data-msg='delete this facility?' data-target='#mdl_confirm' data-href='index.php?page=delf&o1=".$wo_id."&o2=".$data['paket_id']."&o3=".$data['item']."'/></a>";
                  echo "</td>";
                echo "</tr>";
            }
            //return $stmt->get_result()->fetch_assoc();
        } else {
            //echo $stmt->error."</br>";
            //echo "error";
        }
    }


    /**
     * Get the wo package
     * returns wo details
     */
    public function getWOPackageByID($pid) {

        $pstmt = $this->conn->prepare("SELECT name FROM paket
                                              WHERE paket.id = ? ");
        $pstmt->bind_param("s", $pid);

        // check for successful get
        if ($pstmt->execute()) {
            /* Get the result */
            $data = $pstmt->get_result()->fetch_assoc();
            return $data['name'];
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }

    /**
     * Get the wo package
     * returns wo details
     */
    public function getWoIdByPackageId($pid) {

        $pstmt = $this->conn->prepare("SELECT wo.id as wid FROM paket, wo
                                       WHERE wo.id = paket.wo_id and paket.id = ? ");
        $pstmt->bind_param("s", $pid);

        // check for successful get
        if ($pstmt->execute()) {
            /* Get the result */
            $data = $pstmt->get_result()->fetch_assoc();
            return $data['wid'];
        } else {
            //echo $stmt->error."</br>";
            return "";
        }
    }


//------------ ORDERS HANDLE

    /**
     * Storing new fasilitas wo
     * returns wo details
     */
    public function isClientAlreadyOrder($id) {
        $stmt = $this->conn->prepare("SELECT klien_id FROM pesanan WHERE klien_id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()){
          $stmt->store_result();
          if ($stmt->num_rows > 0) return true;
          else return false;
        } else {
          //echo $stmt->error."</br>";
          return false;
        }
    }

    /**
     * Storing new fasilitas wo
     * returns wo details
     */
    public function isClientHaveOrder($id) {
        $stmt = $this->conn->prepare("SELECT klien_id FROM pesanan WHERE klien_id = ? and status != 'DENIED'");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()){
          $stmt->store_result();
          /* Get the result */
          return $stmt->num_rows;
        } else {
          //echo $stmt->error."</br>";
          return -1;
        }
    }

    /**
     * Storing new fasilitas wo
     * returns wo details
     */
    public function isOrderOrdered($id, $type, $item, $total) {
        $stmt = $this->conn->prepare("INSERT INTO fasilitas (paket_id, type, item, total) VALUES
                      (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id, $type, $item, $total);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT paket_id FROM fasilitas WHERE paket_id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return true;
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    /**
     * Storing new fasilitas wo
     * returns wo details
     */
    public function storeClientOrder($cid, $wid, $pid) {
        $idx = hash('crc32',$cid.$wid.$pid);
        $stmt = $this->conn->prepare("INSERT INTO pesanan (id, klien_id, wo_id, paket_id, status, user_payment, wo_payment, pay_name, date_order, date_complete)
                                      VALUES (?, ?, ?, ?, 'NO ACTION', 'BELUM BAYAR', 'PENDING', '', NOW(), 'NULL')");
        $stmt->bind_param("ssss", $idx, $cid, $wid, $pid);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT id FROM pesanan WHERE id = ?");
            $stmt->bind_param("s", $idx);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return true;
        } else {
            //echo $stmt->error."</br>";
            return false;
        }
    }

    public function getClientOrderOnPanel($id){
        $pstmt = $this->conn->prepare("SELECT * FROM pesanan
                                       WHERE klien_id = ?");
        $pstmt->bind_param("s", $id);

        // check for successful get
        if ($pstmt->execute()) {
          $result = $pstmt->get_result();

          while ($data = $result->fetch_assoc()){
            /* Get the result */
              if ($data["status"] == 'APPROVE'){
                echo '<div class="panel panel-primary">';
              } else if ($data["status"] == 'NO ACTION'){
                echo '<div class="panel panel-default">';
              } else if ($data["status"] == 'DENIED'){
                echo '<div class="panel panel-danger">';
              } else if ($data["status"] == 'PENDING'){
                echo '<div class="panel panel-info">';
              } else if ($data["status"] == 'COMPLETE'){
                echo '<div class="panel panel-success">';
              }

          }
        } else {
          //echo $pstmt->error."</br>";
        }
    }

    public function getClientOrderIdByCId($id){
        $pstmt = $this->conn->prepare("SELECT id FROM pesanan
                                       WHERE klien_id = ?");
        $pstmt->bind_param("s", $id);

        // check for successful get
        if ($pstmt->execute()) {
            /* Get the result */
            $data = $pstmt->get_result()->fetch_assoc();
            return $data['id'];
        } else {
            //echo $pstmt->error."</br>";
            return "";
        }
    }

    public function getClientWOIdByCId($id){
        $pstmt = $this->conn->prepare("SELECT wo_id FROM pesanan
                                       WHERE klien_id = ?");
        $pstmt->bind_param("s", $id);

        // check for successful get
        if ($pstmt->execute()) {
            /* Get the result */
            $data = $pstmt->get_result()->fetch_assoc();
            return $data['wo_id'];
        } else {
            //echo $pstmt->error."</br>";
            return "";
        }
    }

    public function getClientOrderWName($id){
        $pstmt = $this->conn->prepare("SELECT wo.name FROM pesanan, paket, wo
                                       WHERE klien_id = ? and paket.id = pesanan.paket_id and wo.id = pesanan.wo_id");
        $pstmt->bind_param("s", $id);

        // check for successful get
        if ($pstmt->execute()) {
            /* Get the result */
            $data = $pstmt->get_result()->fetch_assoc();
            return $data['name'];
        } else {
            //echo $pstmt->error."</br>";
            return "";
        }
    }

    public function getClientOrderPName($id){

        $pstmt = $this->conn->prepare("SELECT paket.name FROM pesanan, paket
                                       WHERE klien_id = ? and paket.id = pesanan.paket_id");
        $pstmt->bind_param("s", $id);

        // check for successful get
        if ($pstmt->execute()) {
            /* Get the result */
            $data = $pstmt->get_result()->fetch_assoc();
            return $data['name'];
        } else {
            //echo $pstmt->error."</br>";
            return "";
        }
    }

    public function getClientOrderPrice($id){
        $pstmt = $this->conn->prepare("SELECT paket.price FROM pesanan, paket
                                       WHERE klien_id = ? and paket.id = pesanan.paket_id");
        $pstmt->bind_param("s", $id);

        // check for successful get
        if ($pstmt->execute()) {
            /* Get the result */
            $data = $pstmt->get_result()->fetch_assoc();
            return $data['price'];
        } else {
            //echo $pstmt->error."</br>";
            return "";
        }
    }

    public function getClientOrderCapacity($id){
        $pstmt = $this->conn->prepare("SELECT paket.capacity FROM pesanan, paket
                                       WHERE klien_id = ? and paket.id = pesanan.paket_id");
        $pstmt->bind_param("s", $id);

        // check for successful get
        if ($pstmt->execute()) {
            /* Get the result */
            $data = $pstmt->get_result()->fetch_assoc();
            return $data['capacity'];
        } else {
            //echo $pstmt->error."</br>";
            return "";
        }
    }

    public function getClientOrderStatus($id){
        $pstmt = $this->conn->prepare("SELECT pesanan.status FROM pesanan, paket
                                       WHERE klien_id = ? and paket.id = pesanan.paket_id");
        $pstmt->bind_param("s", $id);

        // check for successful get
        if ($pstmt->execute()) {
            /* Get the result */
            $data = $pstmt->get_result()->fetch_assoc();
            return $data['status'];
        } else {
            //echo $pstmt->error."</br>";
            return "";
        }
    }

    public function getClientOrderPayment($id){
        $pstmt = $this->conn->prepare("SELECT pesanan.user_payment FROM pesanan, paket
                                       WHERE klien_id = ? and paket.id = pesanan.paket_id");
        $pstmt->bind_param("s", $id);

        // check for successful get
        if ($pstmt->execute()) {
            /* Get the result */
            $data = $pstmt->get_result()->fetch_assoc();
            return $data['user_payment'];
        } else {
            //echo $pstmt->error."</br>";
            return "";
        }
    }

    public function getClientOrderPayName($id){
        $pstmt = $this->conn->prepare("SELECT pesanan.pay_name FROM pesanan, paket
                                       WHERE klien_id = ? and paket.id = pesanan.paket_id");
        $pstmt->bind_param("s", $id);

        // check for successful get
        if ($pstmt->execute()) {
            /* Get the result */
            $data = $pstmt->get_result()->fetch_assoc();
            return $data['pay_name'];
        } else {
            //echo $pstmt->error."</br>";
            return "";
        }
    }

    public function getClientOrderDO($id){
        $pstmt = $this->conn->prepare("SELECT pesanan.date_order FROM pesanan, paket
                                       WHERE klien_id = ? and paket.id = pesanan.paket_id");
        $pstmt->bind_param("s", $id);

        // check for successful get
        if ($pstmt->execute()) {
            /* Get the result */
            $data = $pstmt->get_result()->fetch_assoc();
            return $data['date_order'];
        } else {
            //echo $pstmt->error."</br>";
            return "";
        }
    }

    public function updateOrderPayment($id, $pay_name){

      $stmt = $this->conn->prepare("UPDATE pesanan SET pay_name=?
                                    WHERE id=?");
      $stmt->bind_param("ss", $pay_name, $id);
      if ($stmt->execute()){
          return true;
      } else {
          //echo $stmt->error."</br>";
          return false;
      }

    }


//------------ JOBS HANDLE

    public function checkWOJobs($id){
      $pstmt = $this->conn->prepare("SELECT status FROM pesanan
                                     WHERE wo_id = ? and status='NO ACTION'");
      $pstmt->bind_param("s", $id);

      // check for successful get
      if ($pstmt->execute()) {
          $pstmt->store_result();
          /* Get the result */
          return $pstmt->num_rows;
      } else {
          //echo $pstmt->error."</br>";
          return -1;
      }
    }

    public function getWOJobsOnPanel($id){
      $pstmt = $this->conn->prepare("SELECT * FROM pesanan
                                     WHERE wo_id = ?");
      $pstmt->bind_param("s", $id);

      // check for successful get
      if ($pstmt->execute()) {
        $result = $pstmt->get_result();

        while ($data = $result->fetch_assoc()){

            if ($data["status"] == 'APPROVE'){
              echo '<div class="panel panel-primary">';
            } else if ($data["status"] == 'NO ACTION'){
              echo '<div class="panel panel-default">';
            } else if ($data["status"] == 'DENIED'){
              echo '<div class="panel panel-danger">';
            } else if ($data["status"] == 'PENDING'){
              echo '<div class="panel panel-info">';
            } else if ($data["status"] == 'COMPLETE'){
              echo '<div class="panel panel-success">';
            }

            echo '<div class="panel-heading">';
                echo 'Order: <strong id="strongz">'.$data["id"].' - '.$this->getClientNameByID($data["klien_id"]).'</strong>';
            echo '</div>';

            echo '<div class="panel-body">';
              echo '<div class="row">';
                echo '<div class="col-md-6">';

                  echo '<div class="table-responsive">';
                    echo '<table class="table">';
                      echo '<tbody>';
                        echo '<tr>';
                          echo '<th>Paket</th>';
                          echo '<td>'.$this->getWOPackageNameByID($data["paket_id"]).'</td>';
                        echo '</tr>';
                        echo '<tr>';
                          echo '<th>Status</th>';
                          echo '<td>';
                            echo '<select name="stat-pkt" id="stat-pkt-'.$data["id"].'" class="form-control">';
                              if ($data["status"] == 'APPROVE'){
                                echo '<option selected value="APPROVE">APPROVE</option>';
                                echo '<option value="NO ACTION">NO ACTION</option>';
                                echo '<option value="DENIED">DENIED</option>';
                                echo '<option value="PENDING">PENDING</option>';
                                echo '<option value="COMPLETE">COMPLETE</option>';
                              } else if ($data["status"] == 'NO ACTION'){
                                echo '<option value="APPROVE">APPROVE</option>';
                                echo '<option selected value="NO ACTION">NO ACTION</option>';
                                echo '<option value="DENIED">DENIED</option>';
                                echo '<option value="PENDING">PENDING</option>';
                                echo '<option value="COMPLETE">COMPLETE</option>';
                              } else if ($data["status"] == 'DENIED'){
                                echo '<option value="APPROVE">APPROVE</option>';
                                echo '<option value="NO ACTION">NO ACTION</option>';
                                echo '<option selected value="DENIED">DENIED</option>';
                                echo '<option value="PENDING">PENDING</option>';
                                echo '<option value="COMPLETE">COMPLETE</option>';
                              } else if ($data["status"] == 'PENDING'){
                                echo '<option value="APPROVE">APPROVE</option>';
                                echo '<option value="NO ACTION">NO ACTION</option>';
                                echo '<option value="DENIED">DENIED</option>';
                                echo '<option selected value="PENDING">PENDING</option>';
                                echo '<option value="COMPLETE">COMPLETE</option>';
                              } else if ($data["status"] == 'COMPLETE'){
                                echo '<option value="APPROVE">APPROVE</option>';
                                echo '<option value="NO ACTION">NO ACTION</option>';
                                echo '<option value="DENIED">DENIED</option>';
                                echo '<option value="PENDING">PENDING</option>';
                                echo '<option selected value="COMPLETE">COMPLETE</option>';
                              }
                            echo '</select>';
                          echo '</td>';
                        echo '</tr>';

                        echo '<tr>';
                          echo '<th>Pembayaran</th>';
                          echo '<td>'.$data["wo_payment"].'</td>';
                        echo '</tr>';
                        echo '<tr>';
                          echo '<th>Tanggal order</th>';
                          echo '<td>'.$data["date_order"].'</td>';
                        echo '</tr>';
                        echo '<tr>';
                          echo '<th>Tanggal selesai</th>';
                          echo '<td>lol</td>';
                        echo '</tr>';
                        echo '<tr>';
                          echo '<th>Bukti transfer</th>';
                          echo '<td><img class="pay-img" src="images/payment/'.$data["pay_name"].'" width="300px" height="150px" alt="'.$data["pay_name"].'"></td>';
                        echo '</tr>';
                        echo '<tr>';
                          echo '<td colspan="2">';
                          echo '<form action="update-data.php" method="POST">';
                            echo '<input type="text" name="id-paket" id="pupdate-oid" value="'.$data["id"].'" hidden=""/>';
                            echo '<input type="text" name="in-pkt-'.$data["id"].'" id="in-pkt-'.$data["id"].'" value="'.$data["status"].'" hidden/>';
                            echo '<button type="submit" name="submit-job" class="btn btn-info">Save</button>';
                          echo '</form>';
                          echo '</td>';
                        echo '</tr>';

                      echo '</tbody>';
                    echo '</table>';
                  echo '</div> <!-- table resposive -->';

                echo '</div>';

                echo '<div class="col-md-6">';

                  echo '<div class="chat-box">';
                    echo '<div class="msg-box">';

                      $this->pullMessage($data["wo_id"],$data["klien_id"]);

                    echo '</div>';
                    echo '<form class="form-inline" action="messages.php" method="post">';
                    	echo '<div class="form-group" style="width:100%">';
                        echo '<input type="text" name="id-psn" id="id-psn" value="'.$data["id"].'" hidden=""/>';
                        echo '<input type="text" name="id-wo" id="id-wo" value="'.$data["wo_id"].'" hidden=""/>';
                        echo '<input type="text" name="id-cl" id="id-cl" value="'.$data["klien_id"].'" hidden=""/>';
                        echo '<textarea name="message" id="message" class ="form-control"  rows="2" style="resize: none; width:100%; margin-top:5px;" required=""></textarea>';
                          echo '<button style="margin-top:5px;" class="btn btn-default" type="submit" name="submit-msg-jobs">';
                            echo 'POST';
                          echo '</button>';
                      echo '</div>';
                    echo '</form>';
                  echo '</div>';

                echo '</div>';

                echo '</div>';
              echo '</div> <!-- panel-body -->';
            echo '</div>';

        } // while

      } else {
          //echo $pstmt->error."</br>";
          //echo "";
      }
    }

    public function updateJobStatus($id, $stat){

      $stmt = $this->conn->prepare("UPDATE pesanan SET status=?
                                    WHERE id=?");
      $stmt->bind_param("ss", $stat, $id);
      if ($stmt->execute()){
          return true;
      } else {
          //echo $stmt->error."</br>";
          return false;
      }

    }

//------------ DELETE HANDLE
    public function deleteOrder($cid, $pid){
        if (!empty($cid) && !empty($pid)){
          $stmt = $this->conn->prepare("DELETE FROM pesanan WHERE id = ? and klien_id = ?");
          $stmt->bind_param("ss", $pid, $cid);
        } else {
          return false;
        }

          // check for successful get
          if ($stmt->execute()) {
              return true;
          } else {
              //echo $stmt->error."</br>";
              return false;
          }
    }

    public function deletePackage($wid, $pid, $pname){
        if (!empty($wid) && !empty($pid) && !empty($pname)){
          $stmt = $this->conn->prepare("DELETE FROM paket WHERE wo_id = ? and id = ? and name = ?");
          $stmt->bind_param("sss", $wid, $pid, $pname);
        } else {
          return false;
        }

          // check for successful get
          if ($stmt->execute()) {
              return true;
          } else {
              //echo $stmt->error."</br>";
              return false;
          }
    }

    public function deleteFacility($fid, $fitem){
        if (!empty($fid) && !empty($fitem)){
          $stmt = $this->conn->prepare("DELETE FROM fasilitas WHERE paket_id = ? and item = ?");
          $stmt->bind_param("ss", $fid, $fitem);
        } else {
          return false;
        }

          // check for successful get
          if ($stmt->execute()) {
              return true;
          } else {
              //echo $stmt->error."</br>";
              return false;
          }
    }

//------------ MESSAGES HANDLE
    public function pushMessage($mail_from,$name_from,$mail_to,$name_to,$message){

      $stmt = $this->conn->prepare("INSERT INTO messages (id, user_from, name_from, user_to, name_to, tstamp, message)
                                    VALUES ('',?,?,?,?,NOW(),?)");
      $stmt->bind_param("sssss", $mail_from, $name_from, $mail_to, $name_to, $message);
      $result = $stmt->execute();

      $stmt->close();

      // check for successful store
      if ($result) {
          //echo "result1 :".$result;
          $stmt = $this->conn->prepare("SELECT * FROM messages WHERE message = ?");
          $stmt->bind_param("s", $message);
          $res = $stmt->execute();
          //echo "res2 :".$res;
          //$user = $stmt->get_result()->fetch_assoc();
          $stmt->close();

          if ($res){
            return true;
          } else {
            //echo $stmt->error."</br>";
            return false;
          }
      } else {
          //echo $stmt->error."</br>";
          return false;
      }
    }

    public function pullMessage($user1,$user2){

      $wid = $this->getWOEmailByID($user1);
      $cid = $this->getClientEmailByID($user2);

      $stmt = $this->conn->prepare("SELECT * FROM messages
                                    WHERE (user_from=? and user_to=?) or
                                          (user_to=? and user_from=?)
                                    ORDER BY tstamp ASC");

      $stmt->bind_param("ssss",$wid,$cid,$wid,$cid);
      $result = $stmt->execute();

      // check for successful store
      if ($result) {
        $result = $stmt->get_result();

        while ($data = $result->fetch_assoc()){

            echo '<div class="msg">';
              echo '<span class="msg-user">'.$data["name_from"].' :</span>';
              echo $data["message"];
            echo '</div>';

        }

      } else {
          //echo $stmt->error."</br>";
          //return false;
      }
    }

    // pull chat untuk Admin - Client
    public function pullChatAC($user1,$user2){
      // asumsi user1 adalah pemanggil pull
      $aid = $user1;
      $aname = "Administrator";
      $cid = $this->getClientEmailByID($user2);
      $cname = $this->getClientNameByID($user2);

      $stmt = $this->conn->prepare("SELECT * FROM messages
                                    WHERE (user_from=? and user_to=? and name_from=? and name_to=?) or
                                          (user_to=? and user_from=? and name_to=? and name_from=?)
                                    ORDER BY tstamp ASC");

      $stmt->bind_param("ssssssss",$aid,$cid,$aname,$cname,$aid,$cid,$aname,$cname);
      $result = $stmt->execute();

      // check for successful store
      if ($result) {
        $result = $stmt->get_result();

        while ($data = $result->fetch_assoc()){

          if ($aid==$data['user_from']){
              echo '<div class="msg_b">'.$data["message"].'</div>';
          } else if ($cid==$data['user_from']){
              echo '<div class="msg_a">'.$data["message"].'</div>';
          }
        }

      } else {
          //echo $stmt->error."</br>";
          //return false;
      }
    }

    // pull chat untuk Admin - Guest
    public function pullChatAG($user1,$user2){
      // asumsi user1 adalah pemanggil pull
      $aid = $user1;
      $user1 = "Administrator";

      $stmt = $this->conn->prepare("SELECT * FROM messages
                                    WHERE (user_from=? and user_to=?) or
                                          (user_to=? and user_from=?)
                                    ORDER BY tstamp ASC");

      $stmt->bind_param("ssss",$aid,$user2,$aid,$user2);
      $result = $stmt->execute();

      // check for successful store
      if ($result) {
        $result = $stmt->get_result();

        while ($data = $result->fetch_assoc()){

          if ($aid==$data['user_from']){
              echo '<div class="msg_b">'.$data["message"].'</div>';
          } else if ($user2==$data['user_from']){
              echo '<div class="msg_a">'.$data["message"].'</div>';
          }
        }

      } else {
          //echo $stmt->error."</br>";
          //return false;
      }
    }

    // pull chat untuk Admin - WO
    public function pullChatAW($user1,$user2){
      // asumsi user1 adalah pemanggil pull
      $aid = $user1;
      $aname = 'Administrator';
      $wid = $this->getWOEmailByID($user2);
      $wname = $this->getWONameByID($user2);

      $stmt = $this->conn->prepare("SELECT * FROM messages
                                    WHERE (user_from=? and user_to=? and name_from=? and name_to=?) or
                                          (user_to=? and user_from=? and name_to=? and name_from=?)
                                    ORDER BY tstamp ASC");

      $stmt->bind_param("ssssssss",$aid,$wid,$aname,$wname,$aid,$wid,$aname,$wname);
      $result = $stmt->execute();

      // check for successful store
      if ($result) {
        $result = $stmt->get_result();

        while ($data = $result->fetch_assoc()){

          if ($aid==$data['user_from']){
              echo '<div class="msg_b">'.$data["message"].'</div>';
          } else if ($wid==$data['user_from']){
              echo '<div class="msg_a">'.$data["message"].'</div>';
          }
        }

      } else {
          //echo $stmt->error."</br>";
          //return false;
      }
    }

    // pull chat untuk Client - Admin
    public function pullChatCA($user1,$user2){
      // asumsi user1 adalah pemanggil pull
      $cid = $this->getClientEmailByID($user1);
      $cname = $this->getClientNameByID($user1);
      $aid = $user2;
      $aname = 'Administrator';
      //$wid = $this->getWOEmailByID($user2);

      $stmt = $this->conn->prepare("SELECT * FROM messages
                                    WHERE (user_from=? and user_to=? and name_from=? and name_to=?) or
                                          (user_to=? and user_from=? and name_to=? and name_from=?)
                                    ORDER BY tstamp ASC");

      $stmt->bind_param("ssssssss",$cid,$aid,$cname,$aname,$cid,$aid,$cname,$aname);
      $result = $stmt->execute();

      // check for successful store
      if ($result) {
        $result = $stmt->get_result();

        while ($data = $result->fetch_assoc()){

          if ($cid==$data['user_from']){
              echo '<div class="msg_b">'.$data["message"].'</div>';
          } else if ($aid==$data['user_from']){
              echo '<div class="msg_a">'.$data["message"].'</div>';
          }
        }

      } else {
          //echo $stmt->error."</br>";
          //return false;
      }
    }

    // pull chat untuk Guest - Admin
    public function pullChatGA($user1,$user2){
      // asumsi user1 adalah pemanggil pull
      //$cid = $this->getClientEmailByID($user1);
      //$wid = $this->getWOEmailByID($user2);


      $stmt = $this->conn->prepare("SELECT * FROM messages
                                    WHERE (user_from=? and user_to=?) or
                                          (user_to=? and user_from=?)
                                    ORDER BY tstamp ASC");

      $stmt->bind_param("ssss",$user2,$user1,$user2,$user1);
      $result = $stmt->execute();

      // check for successful store
      if ($result) {
        $result = $stmt->get_result();

        while ($data = $result->fetch_assoc()){

          if ($user1==$data['user_from']){
              echo '<div class="msg_b">'.$data["message"].'</div>';
          } else if ($user2==$data['user_from']){
              echo '<div class="msg_a">'.$data["message"].'</div>';
          }
        }

      } else {
          //echo $stmt->error."</br>";
          //return false;
      }
    }

    // pull chat untuk WO - Admin
    public function pullChatWA($user1,$user2){
      // asumsi user1 adalah pemanggil pull
      $wid = $this->getWOEmailByID($user1);
      $wname = $this->getWONameByID($user1);
      $aid = $user2;
      $aname = 'Administrator';

      $stmt = $this->conn->prepare("SELECT * FROM messages
                                    WHERE (user_from=? and user_to=? and name_from=? and name_to=?) or
                                          (user_to=? and user_from=? and name_to=? and name_from=?)
                                    ORDER BY tstamp ASC");

      $stmt->bind_param("ssssssss",$wid,$aid,$wname,$aname,$wid,$aid,$wname,$aname);

      $result = $stmt->execute();

      // check for successful store
      if ($result) {
        $result = $stmt->get_result();

        while ($data = $result->fetch_assoc()){

          if ($wid==$data['user_from']){
              echo '<div class="msg_b">'.$data["message"].'</div>';
          } else if ($aid==$data['user_from']){
              echo '<div class="msg_a">'.$data["message"].'</div>';
          }
        }

      } else {
          //echo $stmt->error."</br>";
          //return false;
      }
    }

    // pull chat untuk WO - Client
    public function pullChatWC($user1,$user2){
      // asumsi user1 adalah pemanggil pull
      $wid = $this->getWOEmailByID($user1);
      $wname = $this->getWONameByID($user1);
      $cid = $this->getClientEmailByID($user2);
      $cname = $this->getClientNameByID($user2);

      $stmt = $this->conn->prepare("SELECT * FROM messages
                                    WHERE (user_from=? and user_to=? and name_from=? and name_to=?) or
                                          (user_to=? and user_from=? and name_to=? and name_from=?)
                                    ORDER BY tstamp ASC");

      $stmt->bind_param("ssssssss",$wid,$cid,$wname,$cname,$wid,$cid,$wname,$cname);
      $result = $stmt->execute();

      // check for successful store
      if ($result) {
        $result = $stmt->get_result();

        while ($data = $result->fetch_assoc()){

          if ($wid==$data['user_from']){
              echo '<div class="msg_b">'.$data["message"].'</div>';
          } else if ($cid==$data['user_from']){
              echo '<div class="msg_a">'.$data["message"].'</div>';
          }
        }

      } else {
          //echo $stmt->error."</br>";
          //return false;
      }
    }

    // pull chat untuk Client - WO
    public function pullChatCW($user1,$user2){
      // asumsi user1 adalah pemanggil pull
      $cid = $this->getClientEmailByID($user1);
      $cname = $this->getClientNameByID($user1);
      $wid = $this->getWOEmailByID($user2);
      $wname = $this->getWONameByID($user2);

      $stmt = $this->conn->prepare("SELECT * FROM messages
                                    WHERE (user_from=? and user_to=? and name_from=? and name_to=?) or
                                          (user_to=? and user_from=? and name_to=? and name_from=?)
                                    ORDER BY tstamp ASC");

      $stmt->bind_param("ssssssss",$cid,$wid,$cname,$wname,$cid,$wid,$cname,$wname);
      $result = $stmt->execute();

      // check for successful store
      if ($result) {
        $result = $stmt->get_result();

        while ($data = $result->fetch_assoc()){

          if ($cid==$data['user_from']){
              echo '<div class="msg_b">'.$data["message"].'</div>';
          } else if ($wid==$data['user_from']){
              echo '<div class="msg_a">'.$data["message"].'</div>';
          }
        }

      } else {
          //echo $stmt->error."</br>";
          //return false;
      }
    }


}
?>
