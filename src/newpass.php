<?php
include("lib.php");
define("PAGENAME", "Recuperar senha");


$email=$_GET['email'];
$string=$_GET['string'];
$email=trim($email); //trims whitespace
$email=strip_tags($email); //strips out possible HTML
$string=trim($string);
$string=strip_tags($string);

srand((double)microtime()*1023487);  //sets random seed
$newstring = md5(rand(0,999999999));


		$real = $db->execute("select `id`, `validkey`, `conta` from `accounts` where `email`=? and `validkey`=?", array($email, $string));
		if ($real->recordcount() == 0)
		{
			include("templates/header.php");
			echo "Endere�o inv�lido ou antigo. <a href=\"index.php\">Voltar</a>.";
			include("templates/footer.php");
			exit;
		}
			
		if (!$_GET['email'] or !$_GET['string'] or !$_GET['email'] & !$_GET['string'])
		{
			include("templates/header.php");
			echo "Endere�o inv�lido ou antigo. <a href=\"index.php\">Voltar</a>.";
			include("templates/footer.php");
			exit;
                }
		else
		{	
			include("templates/header.php");

			$newpassword = rand(10000000, 99999999);
			$newpasswordcoded = encodePassword($newpassword);

			$query = $db->execute("update `accounts` set `password`=?, `validkey`=? where `email`=? and `validkey`=?", array($newpasswordcoded, $newstring, $email, $string));

			$memberto = $real->fetchrow();

			$insert['player_id'] = $memberto['id'];
			$insert['msg'] = "Voc� recuperou a senha de sua conta pelo seu email.";
			$insert['time'] = time();
			$query = $db->autoexecute('account_log', $insert, 'INSERT');

			$subject = "Sua nova senha - O Confronto";

			$message .= "<html>\n"; 
	$message .= "<body style=\"background-color:#FFFDE0; color:#222\">\n"; 

	$message .= "<div style=\"padding:10px;margin-bottom:4px;background-color:#CEA663\">\n";
	$message .= "<a href=\"http://oconfronto.com/\" target=\"_blank\"><img alt=\"O Confronto\" height=\"30\" src=\"http://oconfronto.com/images/logo.gif\" style=\"display:block;border:0\" width=\"175\"></a>\n";
	$message .= "</div>\n";

	$message .= "<div style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;font-size:13px;margin:14px\">\n";
	$message .= "<br style=\"clear:both\">\n";
	$message .= "<p style=\"margin-top:0\">\n";
	$message .= "Voc� solicitou uma nova senha para a conta <b>" . $memberto['conta'] . "</b>.<br/>\n";
	$message .= "Sua nova senha � <b>" . $newpassword . "</b>, anote-a em um local seguro.\n";
	$message .= "</p>\n";

	$message .= "<p style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;margin-top:5px;font-size:11px;color:#666666\">\n";
	$message .= "Se voc� esquecer sua senha novamente lembre-se que poder� solicitar uma nova senha <a href=\"http://oconfronto.com/forgot.php\" target=\"_blank\">clicando aqui</a>.</p>\n";

	$message .= "<p style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;font-size:13px;line-height:18px;border-bottom:1px solid rgb(238, 238, 238);padding-bottom:10px\"></p>\n";

	$message .= "<p style=\"font-family:'Helvetica Neue', Arial, Helvetica, sans-serif;margin-top:5px;font-size:10px;color:#888888;text-align:center;\">\n";
	$message .= "Copyright � 2008-2011 OC Productions<br/>\n";
	$message .= "Todos os direitos reservados.\n";
	$message .= "</p>\n";

	$message .= "</div>\n";
	$message .= "</body>\n"; 
	$message .= "</html>\n"; 

        $headers .= "MIME-Version: 1.0\n" ; 
        $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"\n"; 
        $headers .= "X-Priority: 1 (Higuest)\n"; 
        $headers .= "X-MSMail-Priority: High\n"; 
        $headers .= "Importance: High\n"; 
	$headers .= "From: no-reply@oconfronto.com";

      			mail( $email, $subject, $message, $headers );

			echo "Sua nova senha foi gerada com sucesso, ela �: <b>" . $newpassword . "</b>.<br/>";
			echo "Para que voc� n�o se esque�a novamente, sua nova senha foi enviada para seu email. <a href=\"index.php\">Voltar</a>.";
			include("templates/footer.php");
			exit;	
		}
	

?>