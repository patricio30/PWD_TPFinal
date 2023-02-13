<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);
        try {
            //Para enviar mail desde localhost
            $mail->SMTPOptions = array('ssl' => array('verify_peer' => false,'verify_peer_name' => false,'allow_self_signed' => true));
            $mail->SMTPDebug = 0;                   
            $mail->isSMTP();                                            
            $mail->Host = 'smtp.gmail.com';                    
            $mail->SMTPAuth = true;                                   
            $mail->Username = 'patohrg@gmail.com';                   
            $mail->Password = 'uibrzglpkpqqvnlf'; //Contraseña de aplicacion en gmail                          
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('patohrg@gmail.com', 'Patricio RG');
            $mail->addAddress('belen.villagran1993@gmail.com');
            $mail->isHTML(true);                                
            $mail->Subject = 'Notificacion';
            $mail->Body = "Mensaje de prueba";
            $mail->send();
            $salida = true;
            echo "Mail enviado";
        }
        catch (Exception $e) {
         $salida = false;
         echo "Mail NO enviado {$mail->ErrorInfo}";
        }