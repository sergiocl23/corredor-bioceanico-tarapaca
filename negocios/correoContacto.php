<?php

include_once(__DIR__."/mailer/CreateEmail.php");

if(!empty($_GET["accion"]) || !empty($_POST["accion"])){
    $accion = empty($_GET["accion"])?$_POST["accion"]:$_GET["accion"];
    
    switch($accion){
    
        case 'enviar_correo_contacto':
            $contacto_nombre = trim($_POST["var1"]);
            $contacto_email = trim($_POST["var2"]);
            $contacto_telefono = trim($_POST["var3"]);
            $contacto_asunto = trim($_POST["var4"]);
            $contacto_mensaje = trim($_POST["var5"]);

            //validaciones
            $errores_formulario = [];

            if($contacto_nombre==''){
                $errores_formulario[]  = 'No se ha ingresado Nombre'; 
            }
            elseif (preg_match('/[^A-Za-z -]+[^A-zÁ-ú]/', $contacto_nombre)){
                $errores_formulario[]  = 'El nombre contiene caracteres no permitidos'; 
            }
            if($contacto_email==''){
                $errores_formulario[]  = 'No se ha ingresado Email'; 
            }
            else{
                if($contacto_email!='' && !filter_var($contacto_email, FILTER_VALIDATE_EMAIL)){
                    $errores_formulario[]  = 'Email incorrecto'; 
                }
            }
            if($contacto_telefono==''){
                $errores_formulario[]  = 'No se ha ingresado Teléfono'; 
            }
            elseif ($contacto_telefono!='' && !preg_match('/^\+?\d+$/', $contacto_telefono)) {
                $errores_formulario[]  = 'El teléfono contiene caracteres no permitidos'; 
            }
            if($contacto_asunto==''){
                $errores_formulario[]  = 'No se ha ingresado Asunto'; 
            }
            if($contacto_mensaje==''){
                $errores_formulario[]  = 'No se ha ingresado Mensaje'; 
            }

            if(!empty($errores_formulario)){
                ob_start();
                ?>
                <ul class="mb-0 text-start" style="padding-left: 20px;">
                    <?php
                    foreach($errores_formulario as $error){
                    ?>
                    <li><?=$error?></li>
                    <?php
                    }
                    ?>
                </ul>
                <?php
                $html = ob_get_clean();
                $respuesta = array(
                    'status'            => 'error_formulario',
                    'message'           => $html
                );
                echo json_encode($respuesta);
                return;
            }

            
            $destinatarios = [
                /*
                [
                    'nombre' => 'Sergio Cerda Lozano',
                    'email' => 'scerdal@unap.cl'
                ],
                [
                    'nombre' => 'Luis Pinto Guzmán',
                    'email' => 'lpintoguzman@gmail.com'
                ],
                [
                    'nombre' => 'Cristian Ortiz Vásquez',
                    'email' => 'cristianortiz@unap.cl'
                ],
                [
                    'nombre' => 'Elizabeth Donoso Gainza',
                    'email' => 'eldonoso@unap.cl'
                ],
                [
                    'nombre' => 'Gabriel Icarte Ahumada',
                    'email' => 'gicarte@unap.cl'
                ],
                [
                    'nombre' => 'Camila Núñez Concha',
                    'email' => 'caminunez@fia2030.unap.cl'
                ],
                */
                [
                    'nombre' => 'Equipo Corredor Bioceánico Tarapacá',
                    'email' => 'contactocb@corredor-bioceanico-tarapaca.cl'
                ],
            ];

            $remitente = [
                'nombre' => $contacto_nombre,
                'email' => $contacto_email,
                'telefono' => $contacto_telefono,
                'asunto' => $contacto_asunto,
                'mensaje' => $contacto_mensaje,
            ];

            $mailer = new CreateEmail();
            $mailer->contactoCorredorBioceanico($destinatarios, $remitente);

            $mailerRespaldo = new CreateEmail();
            $mailerRespaldo->respaldoCorredorBioceanico($remitente);

            $response = [
                'status' => 'success',
                'message' => 'Su mensaje ha sido enviado exitosamente.<br>Nos pondremos en contacto con usted a la brevedad.'
            ];

            echo json_encode($response);   

        break;
    
    }
}

?>