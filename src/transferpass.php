<?php

include("lib.php");
define("PAGENAME", "Tranferir Ouro");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkwork.php");

$pass1 = strtolower($_POST['pass']);
$pass2 = strtolower($_POST['pass2']);

if (($_POST['pass']) && ($_POST['pass2'])) {

    if ($player->transpass != f) {
        include("templates/private_header.php");
        echo "<fieldset><legend><b>Seguran�a</b></legend>";
        echo "Voc� j� possui uma senha de transfer�ncia.";
        echo "</fieldset>";
	echo"<br/><a href=\"home.php\">Voltar</a>.</br>";
        include("templates/private_footer.php");
        exit;
    } else if ($pass1 != $pass2) {
        include("templates/private_header.php");
        echo "<fieldset><legend><b>Seguran�a</b></legend>";
        echo "Digite as duas senhas corretamente.";
        echo "</fieldset>";
	echo"<br/><a href=\"home.php\">Voltar</a>.</br>";
        include("templates/private_footer.php");
        exit;
    } else if (strlen($pass1) > 30) {
        include("templates/private_header.php");
        echo "<fieldset><legend><b>Seguran�a</b></legend>";
        echo "Sua senha de transfer�ncia n�o pode ter mais de 30 caracteres.";
        echo "</fieldset>";
	echo"<br/><a href=\"home.php\">Voltar</a>.</br>";
        include("templates/private_footer.php");
        exit;
    } else if (strlen($pass1) < 4) {
        include("templates/private_header.php");
        echo "<fieldset><legend><b>Seguran�a</b></legend>";
        echo "Sua senha de transfer�ncia n�o pode ter menos de 4 caracteres.";
        echo "</fieldset>";
	echo"<br/><a href=\"home.php\">Voltar</a>.</br>";
        include("templates/private_footer.php");
        exit;
    } else if (encodePassword($pass1) == $player->password) {
        include("templates/private_header.php");
        echo "<fieldset><legend><b>Seguran�a</b></legend>";
        echo "Sua senha de transfer�ncia n�o pode ser igual a senha da sua conta.";
        echo "</fieldset>";
	echo"<br/><a href=\"home.php\">Voltar</a>.</br>";
        include("templates/private_footer.php");
        exit;
    } else {
            $query = $db->execute("update `players` set `transpass`=? where `id`=?", array($pass1, $player->id));
            include("templates/private_header.php");
		echo "<fieldset><legend><b>Seguran�a</b></legend>";
		echo "Sua senha de transf�rencia foi criada com sucesso.";
		echo "</fieldset>";
		echo"<br/><a href=\"home.php\">Voltar</a>.</br>";
            include("templates/private_footer.php");
            exit;
    }

}else{
        include("templates/private_header.php");
        echo "<fieldset><legend><b>Seguran�a</b></legend>";
        echo "Voc� precisa preencher todos os campos.";
        echo "</fieldset>";
	echo"<br/><a href=\"home.php\">Voltar</a>.</br>";
        include("templates/private_footer.php");
}
?>