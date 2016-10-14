<?php
if(file_exists("../srvLib/PHPMailer-master/class.smtp.php")) 
    include_once("../srvLib/PHPMailer-master/class.smtp.php");
else
    include_once("srvLib/PHPMailer-master/class.smtp.php");
if(file_exists("../srvLib/PHPMailer-master/class.phpmailer.php")) 
    include_once("../srvLib/PHPMailer-master/class.phpmailer.php");
else
    include_once("srvLib/PHPMailer-master/class.phpmailer.php");

class GenMail {
    private $mail;
    private $from = "system@test0010.axcell28.idv.tw";
    private $fromName = "system";

    public function __construct($host, $port) {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = false;
        $mail->WordWrap = 50;
        $mail->IsHTML(true);
        $mail->FromName = $this->fromName;
        $mail->From = $this->from;
        $mail->CharSet = "utf-8";
        $mail->Host = "$host:$port";

        $this->mail = $mail;
    }

    public function send($mailto, $subject, $mailBody) {
        $mail = $this->mail;

        if(!isset($mailto['address']))
            throw new Exception("mail address not input");
        if(!isset($mailto['name']))
            throw new Exception("mail to name not input");

        $mail->Subject = $subject;
        $mail->Body = $mailBody;
        $mail->AltBody = "信件內容by test0010";
        $mail->AddAddress($mailto['address'], $mailto['name']);
        if(!$mail->Send()){
            throw new Exception("寄信發生錯誤：" . $mail->ErrorInfo);
            //如果有錯誤會印出原因
        }
        else{ 
            //echo "寄信成功";
        }
    }
}
function sendGenMail($mailto, $subject, $mailBody) {


    //$mail->SMTPDebug = 2;

    $webmaster_email = "system@test0010.axcell28.idv.tw";

    //$mailto = "naviter0027test@gmail.com";
    //echo $mailto;

    $mail->WordWrap = 50;

    $mail->IsHTML(true);

    $mail->FromName = "system";
    $mail->From = $webmaster_email;

    $mail->Subject = $subject;
    $mail->Body = $mailBody;
    $mail->AltBody = "by test0010";
    $mail->CharSet = "utf-8";
    $mail->Host = "test0010.axcell28.idv.tw:25";
    //$mail->Encoding="base64";
    $mail->AddAddress($mailto, "root");

    if(!$mail->Send())
        throw new Exception("寄信發生錯誤：" . $mail->ErrorInfo);
    else 
        return true;
}
?>
