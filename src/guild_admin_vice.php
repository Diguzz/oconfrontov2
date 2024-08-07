<?php
include("lib.php");
define("PAGENAME", "Administra��o do Cl�");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

$error = 0;

$guildquery = $db->execute("select * from `guilds` where `id`=?", array($player->guild));
if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include("templates/private_header.php");

//Guild Leader Admin check
if (($player->username != $guild['leader']) and ($player->username != $guild['vice'])) {
    echo "Voc� n�o pode acessar esta p�gina.<br/>";
    echo "<a href=\"home.php\">Principal</a>.";
} else {

if ($_GET['remove']) {
	if (($guild['vice'] == NULL) or ($guild['vice'] == '')){
		$msg .= "Seu cl� n�o possui um vice lider.";
	}else{
	$db->execute("update `guilds` set `vice`=NULL where `id`=?", array($guild['id']));

		if ($player->username == $guild['leader']){
			$msg .= "Voc� removeu os privil�gios de vice-lider de " . $guild['vice'] . ".";
		}else{
    			echo "Voc� abandonou seu cargo de vice-lideran�a no cl�: " . $guild['name'] ."<br/>";
    			echo "<a href=\"home.php\">Principal</a>.";
			include("templates/private_footer.php");
			exit;
		}
	}
}


if (isset($_POST['username']) && ($_POST['submit'])) {

	$username = $_POST['username'];
	$query = $db->execute("select `id`, `username`, `guild` from `players` where `username`=? and `serv`=?", array($username, $guild['serv']));

    if ($query->recordcount() == 0) {
    	$errmsg .= "Este usu�rio n�o existe!<p />";
    	$error = 1;
   	} else if ($username == $guild['leader']) {
   		$errmsg .= "Este usu�rio � o lider do cl�!";
   		$error = 1;
    } else if ($username == $guild['vice']) {
   		$errmsg .= "Este usu�rio j� � o vice-lider do cl�!";
   		$error = 1;
    } else {
   		$member = $query->fetchrow();
	   		if ($member['guild'] != $guild['id']) {
    			$errmsg .= "O usu�rio $username n�o faz parte do cl�: " . $member['guild'] ."!<p />";
    			$error = 1;
    		} else {
			if (($guild['vice'] == NULL) or ($guild['vice'] == '')){
    			$msg .= "Voc� nomeou $username como vice-lider do cl�.";
			}else{
    			$msg .= "Voc� nomeou $username como vice-lider do cl�.<br/>O antigo vice-lider, " . $guild['vice'] . "  agora � um membro comum.";
			}

    			$query = $db->execute("update `guilds` set `vice`=? where `id`=?", array($username, $guild['id']));
    			$logmsg = "Voc� foi nomeado vice-lider do cl�: ". $guild['name'] .".";
				addlog($member['id'], $logmsg, $db);
    		}
    	}
	}


$guildquery = $db->execute("select * from `guilds` where `id`=?", array($player->guild));
if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

if (($guild['vice'] == NULL) or ($guild['vice'] == '')){
$viceatual1 = "Ningu�m.";
}else{
$viceatual1 = $guild['vice'];
$viceatual2 = "<br/><a href=\"guild_admin_vice.php?remove=" . $guild['vice'] . "\">Remover Vice-Lideran�a de " . $guild['vice'] . "</a>.";
}

	if ($player->username == $guild['leader']){
		echo "<fieldset>";
		echo "<legend><b>" . $guild['name'] . " :: Nomear Vice-Lider</b></legend>";
		echo "<form method=\"POST\" action=\"guild_admin_vice.php\">";
		echo "<table width=\"100%\" border=\"0\"><tr>";
			echo "<td width=\"60%\">";
			echo "<b>Usu�rio:</b>";
				$query = $db->execute("select `id`, `username` from `players` where `guild`=?", array($guild['id']));
				echo "<select name=\"username\"><option value=''>Selecione</option>";
				while($result = $query->fetchrow()){
					echo "<option value=\"$result[username]\">$result[username]</option>";
				}
				echo "</select>";
			echo "<input type=\"submit\" name=\"submit\" value=\"Nomear Vice-Lider\">";
			echo "</td>";
			echo "<td width=\"40%\" align=\"right\"><b>Vice-Lider atual:</b> " . $viceatual1 . " " . $viceatual2 . "</td>";
		echo "</tr></table>";
		echo "</form>";
			echo "<br/><b>ATEN��O:</b> Um vice-lider tem todas as fun��es do administrador do cl�, porem n�o pode desfazer o mesmo e nem nomear novos vice lideres.";
			echo "<p><center>" . $msg . "<font color=\"red\">" . $errmsg . "</font></center></p>";
		echo "</fieldset>";
		echo "<a href=\"guild_admin.php\">Voltar</a>.";
	}elseif ($player->username == $guild['vice']){
		echo "<fieldset>";
		echo "<legend><b>" . $guild['name'] . " :: Vice-Lider</b></legend>";
		echo "<br/><center><input type=\"button\" value=\"Abandonar cargo de Vice-Lideran�a.\" onclick=\"window.location.href='guild_admin_vice.php?remove=" . $guild['vice'] . "'\"></center><br/>";
		echo "</fieldset>";
		echo "<a href=\"guild_admin.php\">Voltar</a>.";
	}else{
   		echo "Voc� n�o pode acessar esta p�gina.<br/>";
    		echo "<a href=\"home.php\">Principal</a>.";
	}

}
include("templates/private_footer.php");
?>