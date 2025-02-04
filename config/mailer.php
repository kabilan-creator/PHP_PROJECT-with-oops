<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require __DIR__."../../vendor/autoload.php";
class MailHandler {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);

        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->SMTPAuth = true;
            $this->mail->Host = "smtp.gmail.com";
            $this->mail->Port = 587;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // Sender credentials
            $this->mail->Username = "kabilang638@gmail.com";
            $this->mail->Password = "jrzk trjf djkp ehby";

            // Enable HTML formatting
            $this->mail->isHTML(true);

        } catch (Exception $e) {
            throw new Exception("Failed to initialize PHPMailer: " . $e->getMessage());
        }
    
    }
    public function getMailerInstance() {
        return $this->mail;
    }

}