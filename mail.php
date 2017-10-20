<?php
    //ini_set("include_path", '/home/sibershi/php:' . ini_get("include_path") );
    //require_once "Mail.php";

    require 'modules/PHPMailer/PHPMailerAutoload.php';
    $mail = new PHPMailer;

    require 'database.php';
    
class Mailer {
    
    
    
    // constructor
    //function __construct() {
        
    //}                   

    
    // destructor
    //function __destruct() {
        //mysqli::close($this->conn);
    //}
    
    public function sendMailToClient($address,$subject,$message){
        
        $db = new Database();
        $name = $db->getClientName($address);
        //$name = 'UNKNOWN';
        
        $from = "Administrator <info@wo.sibershield.com>";
        $to = $name." <".$address.">";
        
        /*
        $host = "mail.sibershield.com";
        $username = "info@wo.sibershield.com";
        $password = "indonesiamerdeka1!";
        
        
        $headers = array ('From' => $from,
            'To' => $to,
            'Subject' => $subject);
            $smtp = Mail::factory('smtp',
            array ('host' => $host,
                   'auth' => true,
                   'username' => $username,
                   'password' => $password));
 
        $mail = $smtp->send($to, $headers, $message);
 
        if (PEAR::isError($mail)) {
            return false;
            //echo("<p>" . $mail->getMessage() . "</p>");
        } else {
            return true;
            //echo("<p>Message successfully sent!</p>");
        }
        */
        
        
        $mail = new PHPMailer;
        
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.sibershield.com';//'wo.sibershield.com';                 // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'info@wo.sibershield.com';          // SMTP username
        $mail->Password = 'indonesiamerdeka1!';               // SMTP password
        $mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; //25;                  // TCP port to connect to
        
        //$mail->setFrom($);
        
        // Set PHPMailer to use the sendmail transport
        $mail->isSendmail();
        //$mail->Host = 'localhost';
        //Set who the message is to be sent from
        $mail->setFrom('info@wo.sibershield.com', 'Administrator');
        //Set an alternative reply-to address
        //$mail->addReplyTo('replyto@example.com', 'First Last');
        //Set who the message is to be sent to
        $mail->addAddress($address, $name);
        //Set the subject line
        $mail->Subject = $subject;
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        //Replace the plain text body with one created manually
        $mail->Body = $message;
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');

        //send the message, check for errors
        if (!$mail->send()) {
            //$_SESSION['mailer'] = "Mailer Error: " . $mail->ErrorInfo;
            //echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            //$_SESSION['mailer'] = "Message sent!";
            //echo "Message sent!";
        }
        
    }

/*
if ($_POST['submit-rrpass']){
  $to = $_POST['submit-email'];
  $subject = "[WO Syar'i] - Reset Password";

  $message = "
    <html>
      <head>
        <title>[WO Syar'i] - Reset Password</title>
      </head>
      <body>
        <p>Hello, Please click link bellow to reset your password</p>
      </body>
    </html>
  ";

  // Always set content-type when sending HTML email
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

  mail($to,$subject,$message,$headers);

}
*/
}

?>