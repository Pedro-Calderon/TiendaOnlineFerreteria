<?php
use PHPMailer\PHPMailer\{PHPMailer,SMTP,Exception};

require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

 $mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'papaganzo1234j@gmail.com';                     //SMTP username
    $mail->Password   = 'riuuvtrccypphcmx';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;             // 587  `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('papaganzo1234j@gmail.com', 'The miners armory');
    $mail->addAddress('papaganzo1234j@gmail.com', 'PCM');


    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Detalles de su compra';

    $cuerpo='<h4>Gracias por su compra</h4>';
    $cuerpo .='<p>El ID de su compra es <b> '.$id_transaccion.'</b></p>';

    $mail->Body    = $cuerpo;
    $mail->AltBody = 'Se han enviado los detalles de su compra. Gracias.';
    $mail->setLanguage('es','../phpmailer/language/phpmailer.lang-es.php');

    $mail->send();
} catch (Exception $e) {
    echo "Error al enviar el correo electronico de la compra: {$mail->ErrorInfo}";
    
}