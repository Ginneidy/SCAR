<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMail/Exception.php';
require './PHPMail/PHPMailer.php';
require './PHPMail/SMTP.php';
$mail = new PHPMailer(true);
if (!isset($_POST['submit'])) {
    echo "<p>Debes llenar el formulario</p>";
    exit;
}
if (isset($_POST['submit'])) {
    $to = 'rasud@udistrital.edu.co';
    $firstname = $_POST["fname"];
    $email = $_POST["email"];
    $text = $_POST["message"];
    $phone = $_POST["phone"];
    if (empty($firstname) || empty($email) || empty($text) || empty($phone)) {
        echo "<script>alert('Todos los datos son obligatorios')</script>";
        exit;
    }
    function IsInjected($str)
    {
        $injections = array(
            '(\n+)',
            '(\r+)',
            '(\t+)',
            '(%0A+)',
            '(%0D+)',
            '(%08+)',
            '(%09+)'
        );
        $inject = join('|', $injections);
        $inject = "/$inject/i";
        if (preg_match($inject, $str)) {
            return true;
        } else {
            return false;
        }
    }
    if (IsInjected($email)) {
        echo "<script>alert('email invalido')</script>";
        exit;
    }
    try {
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        //Server settings
        $mail->SMTPDebug = 0;                       //Ver errores
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';       // SMTP de gmail
        $mail->SMTPAuth   = true;                   // Enable SMTP authentication
        $mail->Username   = 'ramaieeeud@udistrital.edu.co';                     // Correo del que envia, debe tener configurada la privacidad
        $mail->Password   = 'ramaestudiantil2020';                     // Contraseña del correo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;                    // TCP puerto de gmail

        //Recipients
        $mail->setFrom(''); // El que envia el correo
        $mail->addAddress(''); //El que lo recibe 

        // Content
        $mail->isHTML(true);                                  // Si tiene html en caso de venir de un fomrulario
        $mail->Subject = 'Incripcion a congreso'; //Asunto del correo
        $mail->Body    = '<table style="width:100%">
        <tr>
            <td>' . $firstname . '  ' . $laststname . '</td>
        </tr>
        <tr><td>Email: ' . $email . '</td></tr>
        <tr><td>phone: ' . $phone . '</td></tr>
        <tr><td>Text: ' . $text . '</td></tr>
        
    </table>';
        //cuerpo
        $mail->send();
        echo "<script>alert('Inscripción realizada exitosamente')</script>";
        echo "<script>setTimeout(\"location.href='index.html'\",1000)</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}