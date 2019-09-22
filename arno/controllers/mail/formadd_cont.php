  <? session_start();
  	require("class.phpmailer.php");
	$mail = new PHPMailer();
	
			date_default_timezone_set("Asia/Bangkok");
			$d1=date("d");
			$d2=date("m");
			$d3=date("Y");
			$d4=date("H");
			$d5=date("i");
			$d6=date("s");
			$date22="$d1-$d2-$d3 $d4:$d5:$d6";

			
			$mailDate=$date22;
			$detail11="test";
			

/******** setmail ********************************************/
$body = $detail11;
$mail->CharSet = "utf-8";
$mail->IsSMTP();
$mail->SMTPDebug = 0;
$mail->SMTPAuth = true;
$mail->Host = "mail.unogrand.com"; // SMTP server
$mail->Port = 587; 
$mail->Username = "support@unogrand.com"; // account SMTP
$mail->Password = "Uno123456"; //  SMTP

$mail->SetFrom("support@unogrand.com", "Unogrand.com");
$mail->AddReplyTo("support@unogrand.com","Unogrand.com");
$mail->Subject = $mailTitle;

$mail->MsgHTML($body);

$mail->AddAddress("mywoldso@gmail.com", "Unogrand Support"); //
//$mail->AddAddress($set1, $name); // 
if(!$mail->Send()) {echo "Mailer Error: " . $mail->ErrorInfo;} 
else {
	echo "<script>alert(\" Contact Form Send Complete \"); </script>";	
	echo "<script language='JavaScript' type='text/javascript'>window.parent.cleardata();</script>";
}
/********* setmail *******************************************/													
		
?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">