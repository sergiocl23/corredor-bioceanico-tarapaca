<?php 
require_once 'Mailer.php';

class CreateEmail{
    
    const   GREEN               = '#27ae60';
    const   RED                 = '#c0392b';
    const   BLUE                = '#3498db';
    const   ORANGE              = '#e67e22';
    const   PURPLE              = '#884ea0';
    const   GREY                = '#7f8c8d';
    const   AQUA                = '#2980b9';
    const   ASPHALT             = '#566573';
    
    private $mailer;
    
    public function __construct(){
        $this->mailer = new Mailer();
    }
    
    private function sendEmail(){
        $this->mailer->sendEmail();
    }
    
    public function contactoCorredorBioceanico($destinatarios, $remitente){

        // var_dump($integrante);
        // var_dump($reunion);

        $parrafo1       = "Por medio del presente, informamos que la siguiente persona se ha puesto en contacto con nosotros:";
        $nombre         = "<strong>Nombre:</strong> {$remitente['nombre']}";
        $email_contacto = "<strong>E-mail:</strong> {$remitente['email']}";
        $telefono       = "<strong>Teléfono:</strong> {$remitente['telefono']}";
        $asunto         = "<strong>Asunto:</strong> {$remitente['asunto']}";
        $mensaje        = "<div style='padding: 10px; border: 1px solid #004887; border-radius: 4px'>{$remitente['mensaje']}</div>";
        
        foreach($destinatarios as $destinatario){
            $this->mailer->setReceiver($destinatario['nombre'], $destinatario['email']);
        }
        // $this->mailer->setReceiver($destinatario['nombre'], $destinatario['email']);
        
        $this->mailer->setSubject('Contacto Corredor Bioceánico Tarapacá')
        ->setTitle('Contacto Corredor Bioceánico Tarapacá')
        ->setBodyParagraph($parrafo1, 'justify')
        ->setBodyParagraph($nombre, 'left')
        ->setBodyParagraph($email_contacto, 'left')
        ->setBodyParagraph($telefono, 'left')
        ->setBodyParagraph($asunto, 'left')
        ->setBodyParagraph($mensaje, 'justify')
        ->toggleSendedTo()
        ->setSubfooter('<strong>Universidad Arturo Prat</strong> <br> Avenida Arturo Prat 2120, Iquique', 'center', '11px');
        
        // Enviar el correo
        $this->sendEmail();
        
        return $this->mailer->getEmailPreview();
    }

    public function respaldoCorredorBioceanico($remitente){

        // var_dump($integrante);
        // var_dump($reunion);

        $parrafo1       = "Por medio del presente, informamos que hemos recibido tu mensaje. Nos comunicaremos contigo a la brevedad.";
        $parrafo2       = "<strong>Detalles de tu mensaje</strong>";
        $asunto         = "<u>Asunto</u>: {$remitente['asunto']}";
        $mensaje        = "<div style='padding: 10px; border: 1px solid #004887; border-radius: 4px'>{$remitente['mensaje']}</div>";
        
        $this->mailer->setReceiver($remitente['nombre'], $remitente['email']);
        
        $this->mailer->setSubject('Contacto Corredor Bioceánico Tarapacá')
        ->setTitle('Contacto Corredor Bioceánico Tarapacá')
        ->setBodyParagraph($parrafo1, 'justify')
        ->setBodyParagraph($parrafo2, 'left')
        ->setBodyParagraph($asunto, 'left')
        ->setBodyParagraph($mensaje, 'justify')
        ->toggleSendedTo()
        ->setSubfooter('<strong>Universidad Arturo Prat</strong> <br> Avenida Arturo Prat 2120, Iquique', 'center', '11px');
        
        // Enviar el correo
        $this->sendEmail();
        
        return $this->mailer->getEmailPreview();
    }

    public function test($integrante, $reunion){
        $fecha      = date('d/m/Y');
        $lugar      = utf8_encode($reunion['lugar']);
        $parrafo1   = "Por medio del presente, le informamos que el día <strong>{$reunion['fecha']}</strong> desde las <strong>{$reunion['hora_inicio']}</strong> hrs. hasta
        las <strong>{$reunion['hora_fin']}</strong> en <strong>{$lugar}</strong>, se realizará la reunion sobre la etapa de
        <strong>Diagnostico de Necesidades</strong> del rediseño curricular <strong>{$reunion['id_solicitud']}</strong>";
        $parrafo2   = "Esperamos poder contar con su asistencia.";
        
        // foreach($integrantes as $intgrante){
        //     $this->mailer->setReceiver($intgrante['nombre'], $intgrante['email']);
        // }
        $this->mailer->setReceiver($integrante['nombre'], $integrante['email']);
        
        $this->mailer->setSubject('Rediseño Curricular')
        ->setTitle('Rediseño Curricular')
        ->setBodyParagraph($parrafo1, 'justify')
        ->setBodyParagraph($parrafo2, 'left')
        ->toggleSendedTo()
        ->setSubfooter('<strong>Universidad Arturo Prat</strong> <br> Avenida Arturo Prat 2120, Iquique', 'center', '11px');
        
        /* Enviar el correo */
        $this->sendEmail();
        
        return $this->mailer->getEmailPreview();
    }
    
    public function notificaTutoria($integrante, $reunion){

        $parrafo1   = "Por medio del presente, le informamos que con fecha <strong>{$reunion['fecha']}</strong>
                        siendo las <strong>{$reunion['hora']}</strong> hrs. Se ha registrado como Tutor en nuestra
                        Plataforma y ha modificado sus datos de contacto. Rogamos pueda verificar esta información en el siguiente enlace:
                        Para verificar haz click {$reunion['link']}.";
        $parrafo2   = "Recuerda que deberás imprimir este {$reunion['documento']} y entregarlo con la firma del Director/jefe de carrera
                        en las oficinas de la Unidad de Apoyo Estudiantil (UNIA).";
        
        $parrafo3   = "Saludos cordiales";
        
        // foreach($integrantes as $intgrante){
        //     $this->mailer->setReceiver($intgrante['nombre'], $intgrante['email']);
        // }
        $this->mailer->setReceiver($integrante['nombre'], $integrante['email']);
        
        $this->mailer->setSubject('[TUTORIAS UNAP] Registro')
        ->setTitle('Comprobación de Correo')
        ->setBodyParagraph($parrafo1, 'justify')
        ->setBodyParagraph($parrafo2, 'justify')
        ->setBodyParagraph($parrafo3, 'left')
        ->toggleSendedTo()
        ->setSubfooter('<strong>Universidad Arturo Prat</strong> <br> Avenida Arturo Prat 2120, Iquique', 'center', '11px');
        
        /* Enviar el correo */
        $this->sendEmail();
        
        return $this->mailer->getEmailPreview();
    }
    
    public function notificaTutoriaSinCambios($integrante, $reunion){
        
        $parrafo1   = "Por medio del presente, le informamos que con fecha <strong>{$reunion['fecha']}</strong>
        siendo las <strong>{$reunion['hora']}</strong> hrs. Se ha registrado como Tutor en nuestra
        Plataforma. Pronto te contáctaremos-";
        $parrafo2   = "Recuerda que deberás imprimir este {$reunion['documento']} y entregarlo con la firma del Director/jefe de carrera
        en las oficinas de la Unidad de Apoyo Estudiantil (UNIA).";
        
        $parrafo3   = "Saludos cordiales";
        
        // foreach($integrantes as $intgrante){
        //     $this->mailer->setReceiver($intgrante['nombre'], $intgrante['email']);
        // }
        $this->mailer->setReceiver($integrante['nombre'], $integrante['email']);
        
        $this->mailer->setSubject('[TUTORIAS UNAP] Registro')
        ->setTitle('Comprobación de Correo')
        ->setBodyParagraph($parrafo1, 'justify')
        ->setBodyParagraph($parrafo2, 'justify')
        ->setBodyParagraph($parrafo3, 'left')
        ->toggleSendedTo()
        ->setSubfooter('<strong>Universidad Arturo Prat</strong> <br> Avenida Arturo Prat 2120, Iquique', 'center', '11px');
        
        /* Enviar el correo */
        $this->sendEmail();
        
        return $this->mailer->getEmailPreview();
    }
    
}

// $email = new CreateEmail();
// $integrantes = [['nombre'=> 'Sergio Cerda Lozano', 'email' =>'sergcerd@unap.cl'], ['nombre'=> 'Gabriel Ardiles Osorio', 'email' =>'sergcerd@unap.cl']];
// $reunion = ['fecha' => '25/04/2019', 'lugar' => 'tusca', 'hora_inicio' => '15:00', 'hora_fin' => '17:00', 'id_solicitud' => '356'];
// echo $email->notificarReunionInformativa($integrantes, $reunion);