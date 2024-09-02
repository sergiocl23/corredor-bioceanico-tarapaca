<?php  
// require_once('/home/generalidades/www/lib/Swift/lib/swift_required.php'); //Versi�n antigua
require_once('SwiftMailer/swift_required.php');

/** 
 * Created by Fernando D�az N��ez
 * Unidad de Inform�tica y Comunicaciones
 * 20/03/2018
 */

class Mailer{
    const   MAIL_WIDTH          = '600';
    
    private $dear               = 'Estimado(a)';
    private $sender_email       = 'no-reply@unap.cl';
    private $sender_name        = 'Corredor Bioceánico Tarapacá';
    private $receivers          = array();  
    private $headers            = array();
    private $body               = array();
    private $footers            = array();
    private $subfooters         = array();
    private $isHeaderActive     = true;
    private $isGreetingsActive  = true;
    private $isSendedToActive   = false;
    private $subject;
    private $image;
    private $title;
    private $greetings;
    private $color;
    
    /**
     * Inicializaci�n de elementos por defecto
     */
    public function __construct(){
        
        //Default header
        $this->headers[] = array( 
            'content'   => 'Para asegurar la entrega de nuestros e-mail, por favor agregue <strong>no-reply@unap.cl</strong> a su libreta de direcciones de correo.', 
            'align'     => 'center',
            'fontSize'  => '9px'
        );
        
        //Default image
        $this->image = array(
            // 'url'       => 'http://portal-dev.unap.cl/docs/logos/logos/png_logounap.png',
            'url'       => 'https://www.corredor-bioceanico-tarapaca.cl/img/logo_full.png',
            'width'     => 520,
            'height'    => 122,
            'align'     => 'center'
        );
        
        //Default greetings
        $this->greetings = array( 'align' => 'left', 'fontSize' => '16px' );
    }
    
    /**
     * @param string $sender Setea el correo de la persona que env�a el mensaje
     * @param string $sender_name Setea el nombre de la persona que env�a el mensaje
     */
    public function setSender($sender_email, $sender_name){
        $this->sender_email = $sender_email;
        $this->sender_name  = $sender_name;
        return $this;
    }
    
    /**
     * @param array $receiver Setea un array de strings con los correos de los destinatarios
     * @param string $receiver_name Setea el nombre de la persona que recibe el mensaje
     */
    public function setReceiver($name, $email){
        $this->receivers[] = array( 'name' => $name, 'email' => $email );
        return $this;
    }
    
    /**
     * Setea el asunto del correo
     * @param string $subject Asunto del correo
     */
    public function setSubject($subject){
        $this->subject = $subject;
        return $this;
    }
    
    /**
     * @param string $image Setea la URL de la imagen del correo
     * @param int $width Setea el ancho de la imagen
     * @param int $height Setea el alto de la imagen
     */
    public function setImage($url, $width, $height, $align = 'center'){
        $this->image = array(
            'url'       => $url,
            'width'     => $width,
            'height'    => $height,
            'align'     => $align
        );
        return $this;
    }
    
    /**
     * 
     * @param string $content Setea el texto del t�tulo principal del correo
     * @param string $align Setea el alineamiento del t�tulo. Por defecto se alinea a la izquierda 
     * @param string $fontSize Setea el tama�o de la fuente. Por defecto son 24px
     */
    public function setTitle($content, $align = 'left', $fontSize = '24px'){
        $this->title = array( 'content' => $content, 'align' => $align, 'fontSize' => $fontSize );
        return $this;
    }
    
    public function setColor($color){
        $this->color = $color;
        return $this;
    }
    
    private function setColorContent(){
        ob_start();
        ?>
        <div style="display:inline-block;
					background-color:<?= $this->color ?>;
					width:32px;
					height:32px;
					vertical-align: bottom;
					margin-right:8px;"
		></div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * @param string $dear Setea la anteposici�n al nombre del receptor del correo. Por defecto se utiliza 'Estimado(a)'
     */
    public function setDear($dear){
        $this->dear = $dear;
        return $this;
    }
    
    /**
     * Cambia el estado de la cabecera, para mostrarla u ocultarla
     */
    public function toggleGreetings(){
        $this->isGreetingsActive = !$this->isGreetingsActive;
        return $this;
    }
    
    /**
     * @param string $align Setea el alineamiento del saludo. Por defecto se alinea a la izquierda 
     * @param string $fontSize Setea el tama�o de la fuente. Por defecto son 20px
     */
    public function setGreetings($align='left', $fontSize='16px'){
        $this->greetings = array( 'align' => $align, 'fontSize' => $fontSize );
        return $this;
    }
    
    /**
     * Cambia el estado de la cabecera, para mostrarla u ocultarla
     */
    public function toggleHeader(){
        $this->isHeaderActive = !$this->isHeaderActive;
        return $this;
    }
    
    /**
     * Borra todo el contenido del array header
     */
    public function clearHeader(){
        $this->headers = array();
        return $this;
    }
    
    /**
     * Cada vez que se llama a esta funci�n, almacena en el array 'headers' un p�rrafo que luego ser� recorrido para armar el header completo
     * @param string $content Setea la cabecera del correo
     * @param string $align Setea el alineamiento del p�rrafo. Por defecto se alinea al centro 
     * @param string $fontSize Setea el tama�o de la fuente. Por defecto son 9px
     */
    public function setHeader($content, $align = 'center', $fontSize = '9px'){
        $this->headers[] = array( 'content' => $content, 'align' => $align, 'fontSize' => $fontSize );
        return $this;
    }
    
    /**
     * Cada vez que se llama a esta funci�n, almacena en el array 'body' un p�rrafo que luego ser� recorrido para armar el body completo
     * @param string $content Setea un string con un p�rrafo del contenido
     * @param string $align Setea el alineamiento del p�rrafo. Por defecto se alinea a la izquierda
     * @param string $fontSize Setea el tama�o de la fuente. Por defecto son 14px
     */
    public function setBodyParagraph($content, $align = 'left', $fontSize = '16px'){
        $this->body[] = array( 'content' => $content, 'align' => $align, 'fontSize' => $fontSize );
        return $this;
    }
    
    /**
     * Cada vez que se llama a esta funci�n, almacena en el array 'footers' un p�rrafo que luego ser� recorrido para armar el footer completo
     * @param string $content Setea un string con un p�rrafo del contenido
     * @param string $align Setea el alineamiento del p�rrafo. Por defecto se alinea al centro
     * @param string $fontSize Setea el tama�o de la fuente. Por defecto son 12px
     */
    public function setFooter($content, $align = 'center', $fontSize = '12px'){
        $this->footers[] = array( 'content' => $content, 'align' => $align, 'fontSize' => $fontSize );
        return $this;
    }
    
    /**
     * Cada vez que se llama a esta funci�n, almacena en el array 'subfooters' un p�rrafo que luego ser� recorrido para armar el subfooter completo
     * @param string $content Setea un string con un p�rrafo del contenido
     * @param string $align Setea el alineamiento del p�rrafo. Por defecto se alinea al centro
     * @param string $fontSize Setea el tama�o de la fuente. Por defecto son 11px
     */
    public function setSubfooter($content, $align = 'center', $fontSize = '11px'){
        $this->subfooters[] = array( 'content' => $content, 'align' => $align, 'fontSize' => $fontSize );
        return $this;
    }
    
    /**
     * Cambia el estado de isSendedToActive, el cual muestra u oculta en el subfooter a qui�n va dirigido el correo
     */
    public function toggleSendedTo(){
        $this->isSendedToActive = !$this->isSendedToActive;
        return $this;
    }
    
    /**
     * @return string
     */
    private function getHeaderContent(){
        ob_start();
        foreach($this->headers as $header):
            $content    = $header['content'];
            $align      = $header['align'];
            $fontSize   = $header['fontSize'];
        ?>
            <tr> 
    			<td bgcolor="#fbfcfc" 
    				style="font-size: <?= $fontSize ?>; text-align: <?= $align ?>; color:#78909c; padding: 10px 10px 20px 10px; border-left:1px solid #d5dbdb; border-right: 1px solid #d5dbdb; border-top: 1px solid #d5dbdb; font-family: Arial, sans-serif;"
                >
    				<span><?= $content ?></span>
    			</td>
    		</tr>
        <?php 
        endforeach;
        return ob_get_clean();
    }
    
    /**
     * @return string
     */
    private function getImageContent(){
        $url    = $this->image['url'];
        $width  = $this->image['width'];
        $height = $this->image['height'];
        $align  = $this->image['align'];
        
        ob_start();
        ?>
        <tr>
			<td align="<?= $align ?>" 
				bgcolor="#fbfcfc" 
				style=" padding: 20px 0 20px 0; border-left:1px solid #d5dbdb; border-right:1px solid #d5dbdb; font-family: Arial, sans-serif;"
			>
				<img src="<?= $url ?>"  
					 width="<?= $width ?>" 
					 height="<?= $height ?>" 
					 style="display: block;" />
			</td>
		</tr>
        <?php 
        return ob_get_clean();
    }
    
    /**
     * @param int $receiver_index �ndice para obtener el nombre del destinatario desde el array 'receivers'
     * @return string
     */
    private function setGreetingContent($receiver_index){
        $align      = $this->greetings['align'];
        $fontSize   = $this->greetings['fontSize'];
        ob_start();
        ?>
        <tr>
    		<td align="<?= $align ?>" style="font-size: <?= $fontSize ?>; padding: 10px 0 10px 0; color: #004887; line-height: 20px; font-family: Arial, sans-serif;">
    			<?= $this->dear ?> <strong><?= $this->receivers[$receiver_index]['name'] ?></strong>
    		</td>
    	</tr>
        <?php 
        return ob_get_clean();
    }
    
    /**
     * @param unknown $receiver_index �ndice para ser usado en el m�todo setGreetingContent()
     * @return string
     */
    private function getBodyContent($receiver_index){
        //T�tle data
        $title      = $this->title['content'];
        $align      = $this->title['align'];
        $fontSize   = $this->title['fontSize'];
        
        
        ob_start();
        ?>
        <tr>
			<td bgcolor="#fbfcfc" style="padding: 15px 50px 40px 50px; border-left:1px solid #d5dbdb; border-right:1px solid #d5dbdb;">
				<table 
					style="" 
					bgcolor="#fbfcfc" 
					border="0" 
					cellpadding="0" 
					cellspacing="0" 
					width="100%"
				>
				
					<tr>
						<td style="color: #004887; font-size: <?= $fontSize ?>; font-family: Arial, sans-serif;" align="<?= $align ?>">
							<?= ($this->color) ? $this->setColorContent() : ''; ?>
							<b><?= $title ?></b>
						</td>
					</tr>
					<?php 
        				if($this->isGreetingsActive){
        				    echo $this->setGreetingContent($receiver_index);
        				}
    				?>
					
					<?php foreach($this->body as $body): ?>
        					<tr>
        						<td style="text-align:<?= $body['align'] ?>; font-size:<?= $body['fontSize'] ?>; padding: 10px 0 0px 0; color: #004887; line-height: 20px; font-family: Arial, sans-serif;">
        							<?= $body['content'] ?>
        						</td>
        					</tr>
					<?php endforeach; ?>
				</table>
			</td>
		</tr>
        <?php
        return ob_get_clean();
    }
    
    /**
     * @return string
     */
    private function setFooterContent(){
        ob_start();
        foreach($this->footers as $footer):
            $content    = $footer['content'];
            $align      = $footer['align'];
            $fontSize   = $footer['fontSize'];
        ?>
            <tr>
    			<td bgcolor="#2980b9" style="font-size: <?= $fontSize ?>; text-align:<?= $align ?>; color:#ffffff; padding: 10px 20px 10px 20px; border-left:1px solid #2980b9; border-right:1px solid #2980b9; font-family: Arial, sans-serif;">
    				<strong><?= $content ?></strong>
    			</td>
    		</tr>
        <?php 
        endforeach;
        return ob_get_clean();
    }
    
    /**
     * @return string
     */
    private function setSubfooterContent(){
        ob_start();
        foreach($this->subfooters as $index => $subfooter):
            $content    = $subfooter['content'];
            $align      = $subfooter['align'];
            $fontSize   = $subfooter['fontSize'];
            $padding    = ($index == 0 && !$this->isSendedToActive) ? '10px 20px 10px 20px' : '0px 20px 10px 20px';
        ?>
            <tr> 
				<td bgcolor="#004887" style="font-size: <?= $fontSize ?>; text-align:<?= $align ?>; padding:<?= $padding ?>; color:#ffffff; border-left:1px solid #2980b9; border-right:1px solid #2980b9; font-family: Arial, sans-serif;">
					<?= $content ?>
				</td>
			</tr>
        <?php 
        endforeach;
        return ob_get_clean();
    }
    
    /**
     * @param int $index �ndice para obtener el email del destinatario desde el array 'receivers'
     * @return string
     */
    private function setSendedToContent($index){
        ob_start();
        $align      = 'center';
        $fontSize   = '11px';
        $padding    = '10px 20px 10px 20px';
        ?>
            <tr> 
				<td bgcolor="#004887" style="font-size: <?= $fontSize ?>; text-align:<?= $align ?>; padding:<?= $padding ?>; color:#ffffff; border-left:1px solid #2980b9; border-right:1px solid #2980b9; font-family: Arial, sans-serif;">
					<strong>Este correo ha sido enviado a <?= $this->receivers[$index]['email'] ?></strong>
				</td>
			</tr>
        <?php 
        return ob_get_clean();
    }
    
    /**
     * Funci�n que se ejecuta una vez por cada receiver en el array 'receivers'. Arma el HTML del correo
     * @param int $receiver_index �ndice del receiver actual
     * @return string
     */
    private function getMailContent($receiver_index){
        ob_start();
        ?>
        <div style="margin: 0; padding: 0; background-color:#eeeeee;">
        	<table border="0" cellpadding="0" cellspacing="0" width="100%">
        		<tr>
        			<td style="padding: 30px 0 30px 0;">
        			<table align="center" border="0" cellpadding="0" cellspacing="0" width="<?= self::MAIL_WIDTH ?>" style="border-collapse: collapse;">
        				
        				<?php 
            				if($this->isHeaderActive){
            				    echo $this->getHeaderContent();
            				}
        				?>
        				
        				<?= $this->getImageContent() ?>
        				
        				<?= $this->getBodyContent($receiver_index) ?>
        				
        				<?= $this->setFooterContent() ?>
        				
        				<?php 
        				if($this->isSendedToActive)
        				    echo $this->setSendedToContent($receiver_index); 
        				?>
        				
        				<?= $this->setSubfooterContent() ?>
        				
        			</table>
        			</td>
        		</tr>
        	</table>
        </div>
        <?php 
        return ob_get_clean();
    } 
    
    /**
     * Devuelve un ejemplo de email creado con esta librer�a
     * @return string
     */
    public function getExampleEmail(){
        $this->setReceiver('Estudiante', 'estudiante@unap.cl');
        
        //$this->setImage('http://www.parkvetgroup.com/wp-content/uploads/2016/01/squarepet400px-cat-300x300.jpg', 200, 200, 'center');
        
        $this->setTitle('Título de ejemplo');
        $this->setGreetings();
        
        $this->setBodyParagraph('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quis autem laboriosam unde illum doloribus!');
        $this->setBodyParagraph('Lorem ipsum dolor sit amet, <strong>consectetur adipisicing</strong> elit.');
        $this->setBodyParagraph('<strong>Saepe suscipit vero recusandae veritatis quod aut esse libero ut nobis ipsum.</strong>', 'center', '14px');
        
        $this->setFooter('Lorem ipsum dolor sit amet, consectetur adipisicing elit');
        $this->setSubfooter('Lorem ipsum dolor sit amet, consectetur adipisicing elit <br> Lorem ipsum dolor sit amet');
        
        $html = $this->getMailContent(0);
        return $html;
    }
    
    /**
     * Devuelve una vista previa del correo creado, sin enviarlo
     * @return string
     */
    public function getEmailPreview(){
        $html = $this->getMailContent(0);
        return $html;
    }
    
    /**
     * Crea la instancia del correo para un receiver
     */
    private function createEmail($index){
        //Create the message
        $message    = Swift_Message::newInstance()
                        ->setSubject( $this->subject )
                        ->setFrom( array( $this->sender_email => $this->sender_name ) )
                        ->setTo( $this->receivers[$index]['email'] )
                        ->setBody( $this->getMailContent($index), 'text/html' );
                        
        $transport  = Swift_SmtpTransport::newInstance('smtp2.unap.cl', 25)
                        ->setUsername('')
                        ->setPassword('');
        
        $mailer     = Swift_Mailer::newInstance($transport);
        
        return $mailer->send($message);
    }
    
    public function sendEmail(){
        foreach($this->receivers as $index => $receiver){
            $this->createEmail($index);
        }
    }
}