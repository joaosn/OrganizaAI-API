<?php

namespace src\controllers;

use \core\Controller as Cltr;
use \core\Database as DB;
use Exception as GlobalException;
use \src\Config as CF;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController extends Cltr
{
    public function sendEmail($destinatario, $assunto, $mensagem,$cc=null, $Pathanexo = null)    
    {
        try {
           
            $mail = new PHPMailer(true);
            // Configuração do servidor
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = CF::SMTP_HOST; // Altere para o seu servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = CF::EMAIL_API; // Seu e-mail SMTP
            $mail->Password = CF::SENHA_EMAIL_API; // Sua senha SMTP
            $mail->SMTPSecure = 'ssl';
            $mail->Port = CF::SMTP_PORT;

            // Destinatários
            $mail->setFrom(CF::EMAIL_API, 'Clickexpress');
            $mail->addAddress($destinatario); // Adicione um destinatário
            if($cc){
                $mail->addCC($cc); // Adicione um destinatário
            }
            // Conteúdo do e-mail
            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body    = $mensagem;

            // Anexos
            if ($Pathanexo) {
                $mail->addAttachment($Pathanexo);
            }

            $mail->send();
            return true;
        } catch (Exception $e) {
            return "{$mail->ErrorInfo}";
        }
    }
}
