
<?php
if(file_exists("../srvLib/PHPMailer-master/class.smtp.php")) 
    include_once("../srvLib/PHPMailer-master/class.smtp.php");
else
    include_once("srvLib/PHPMailer-master/class.smtp.php");
if(file_exists("../srvLib/PHPMailer-master/class.phpmailer.php")) 
    include_once("../srvLib/PHPMailer-master/class.phpmailer.php");
else
    include_once("srvLib/PHPMailer-master/class.phpmailer.php");

function sendMail($mailto, $contentTitle, $contentBody) {
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;

    $webmaster_email = "ekSystem2346@gmail.com";

    $mail->WordWrap = 50;

    $mail->IsHTML(true);

    $mail->Username = "ekSystem2346@gmail.com";
    $mail->Password = "ek34di593c";

    $mail->FromName = "store System";
    $mail->From = $webmaster_email;

    $mail->Subject = $contentTitle;
    $mail->Body = $contentBody;
    $mail->AltBody = "信件內容";
    $mail->CharSet = "utf-8";
    $mail->Host = "ssl://smtp.gmail.com:465";
    $mail->Encoding="base64";
    $mail->AddAddress($mailto, "System");

    if(!$mail->Send()){
        throw new Exception ("send mail error : " . $mail->ErrorInfo);
        //如果有錯誤會印出原因
    }
    else{ 
        return "send success";
    }
}
?>
