<?php


// note: ticket number start from 15500

// Import PHPMailer classes at the top
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Load Composer's autoloader
require '../vendor/autoload.php';



$contactName = $_POST["contact-name"];
$contactMail = $_POST["contact-email"];
$contactSubject = $_POST["contact-sub"];
$contactMessage = $_POST["contact-message"];


//ticket id generator
$ticketidfile = file_get_contents("ticnum.txt");
$ticketid = trim($ticketidfile);
$newticketid = $ticketid + 1;
file_put_contents("ticnum.txt", str_replace($ticketid, $newticketid, $ticketidfile));

// Instantiation
$mail = new PHPMailer(true);

// Server settings
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->SMTPSecure= 'tls';
$mail->Port = 587;
$mail->Username = 'abhishekpathakilk02@gmail.com';
$mail->Password = 'ztffyumnzugxqaet';

// Sender &amp; Recipient
$mail->From = 'support@grambaba.com';
$mail->FromName = 'GRAMBABA SUPPORT';
// $mail->addAddress('golulovesme@gmail.com'); // to me
$mail->addAddress($contactMail); // to user


// Content
$mail->isHTML(true);
$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';
$mail->Subject = 'Support Ticket #'.$ticketid;
$body = nl2br("<h2>Thank your for using our services.</h2><p>Hi {$contactName}, \n We have received your query. We will reply as soon as possible. \n\n Your Query: \n {$contactMessage} \n\n Regards,\n ABhishek Pathak </p>");
$mail->Body = $body;
if($mail->send()){
  // echo 'Success Message';
  echo json_encode(array('status' => 'success', 'message' => 'Sent!'));
  exit;
}else{
  // echo 'Error Message';
  echo json_encode(array('status' => 'error', 'message' => 'Unable to mail'));
  exit;
}

?>