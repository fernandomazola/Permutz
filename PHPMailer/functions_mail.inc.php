<?php 

require ('PHPMailerAutoload.php');

function enviaNovaSenhaUsuario($dadosUsuario, $strNovaSenha) {

	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'mail.renefb.info';  					  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'no-reply@permutz.renefb.info';      // SMTP username
	$mail->Password = 'At@uscPMT';                        // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    // TCP port to connect to

	$mail->charSet = "UTF-8";

	$mail->setFrom('no-reply@permutz.renefb.info', 'Permutz');
	$mail->addAddress($dadosUsuario['email_tjsp']);     // Add a recipient
	if (filter_var($dadosUsuario['email_pessoal'], FILTER_VALIDATE_EMAIL)) {
		$mail->addAddress($dadosUsuario['email_pessoal']);               // Name is optional
	}
	$mail->addReplyTo('no-reply@permutz.renefb.info', 'Permutz');
	$mail->addBCC('monitoramento@permutz.renefb.info');

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = 'Sua nova senha do Permutz';

	$mail->Body     = '<p>Olá, '.$dadosUsuario['nome'].'!</p>';
	$mail->Body    .= '<p>Sua senha provisória para acesso ao Permutz é <strong>'.$strNovaSenha.'</strong>.</p>';
	$mail->Body    .= '<p>Clique <a href="'.URL_BASE.'perfil-alteracao-form.php">aqui</a> para acessar seu perfil com a senha provisária e cadastrar uma outra de sua preferência.</p>';
	
	$mail->AltBody     = 'Olá, '.$usuarioDados['nome'].'! ';
	$mail->AltBody    .= 'Sua senha provisória para acesso ao Permutz é '.$strNovaSenha.'. ';
	$mail->AltBody    .= 'Copie o link '.URL_BASE.'perfil-alteracao-form.php e cole-o em seu navegador para acessar seu perfil com a senha provisária e cadastrar uma outra de sua preferência.';

	$envio = null;
	if(!$mail->send()) {
	    $envio = false;
	} else {
	    $envio = true;
	}
	return $envio;
}