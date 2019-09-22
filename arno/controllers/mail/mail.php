<?PHP
require("class.phpmailer.php");
$mail = new PHPMailer();

$body = "Einzahlung für/Versement pour/Versamento per";

$mail->CharSet = "iso-8859-1";
$mail->IsSMTP();
$mail->SMTPDebug = 0;
$mail->SMTPAuth = true;
$mail->Host = "smtp.dinas-olivenoel.ch"; // SMTP server
$mail->Port = 587; // พอร์ท
$mail->Username = "info@dinas-olivenoel.ch"; // account SMTP
$mail->Password = "Dinas123456@"; // รหัสผ่าน SMTP

$mail->SetFrom("info@dinas-olivenoel.ch", "Dinas-olivenoel");
$mail->AddReplyTo("info@dinas-olivenoel.ch", "Dinas-olivenoel");
$mail->Subject = "TEST MAIL.";

$mail->MsgHTML($body);

//$mail->AddAddress("info@dinas-olivernoel.ch", "recipient1"); // ผู้รับคนที่หนึ่ง
$mail->AddAddress("mywoldso@gmail.com", "recipient1"); // ผู้รับคนที่สอง

if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
?>