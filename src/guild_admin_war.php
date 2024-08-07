<?php
include("lib.php");
define("PAGENAME", "Administra��o do Cl�");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

$guildquery = $db->execute("select * from `guilds` where `id`=?", array($player->guild));
if ($guildquery->recordcount() == 0) {
	header("Location: home.php");
} else {
	$guild = $guildquery->fetchrow();
}

$enyguildquery = $db->execute("select * from `guilds` where `id`=?", array($_GET['id']));
if ($enyguildquery->recordcount() == 0) {
	header("Location: guild_admin_enemy.php");
} else {
	$enyguild = $enyguildquery->fetchrow();
}

include("templates/private_header.php");

if (($player->username != $guild['leader']) and ($player->username != $guild['vice'])) {
    echo "<p />Voc� n�o pode acessar esta p�gina.<p />";
    echo "<a href=\"home.php\">Principal</a><p />";
} else {

	$checkwarquery = $db->execute("select * from `pwar` where ((`guild_id`=?) or (`enemy_id`=?)) and `status`='p'", array($guild['id'], $guild['id']));
	if ($checkwarquery->recordcount() > 0) {
		echo "J� existe um chamado de guerra contra o cl� " . $enyguild['name'] . ".";
		echo "<br/><a href=\"guild_admin_enemy.php\">Voltar</a>.";
	} elseif ($guild['members'] < 3){
		echo "Seu cl� n�o possui membros suficientes para iniciar uma guerra. (min. 3 membros)";
		echo "<br/><a href=\"guild_admin_enemy.php\">Voltar</a>.";
	} elseif ($enyguild['members'] < 3){
		echo "O cl� " . $enyguild['name'] . " n�o possui membros suficientes para iniciar uma guerra. (min. 3 membros)";
		echo "<br/><a href=\"guild_admin_enemy.php\">Voltar</a>.";
	} elseif ($_POST['submit']){
		if ((!$_POST['wnumber']) or (!$_POST['gold'])){
			echo "Por favor preencha todos os campos.";
			echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
		} elseif ((!is_numeric($_POST['gold'])) or ($_POST['gold'] < 1)){
			echo "Insira uma quantia de ouro v�lida.";
			echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
		} elseif ($_POST['gold'] > $guild['gold']){
			echo "Seu cl� n�o possui tanto ouro para apostar.";
			echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
		} elseif ($_POST['gold'] < 10000){
			echo "A aposta m�nima � de 10000 moedas de ouro.";
			echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
		} elseif ($_POST['wnumber'] > $guild['members']){
			echo "Seu cl� n�o possui membros suficientes para uma guerra deste tamanho.";
			echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
		} elseif ($_POST['wnumber'] > $enyguild['members']){
			echo "O cl� " . $enyguild['name'] . " n�o possui membros suficientes para iniciar uma guerra deste tamanho.";
			echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
		}else{
			if (!$_POST['startwar']){
			echo "<center>Selecione os " . $_POST['wnumber'] . " membros do seu cl� que ir�o lutar na guerra.</center><center><font size=\"1px\">(eles n�o precisam estar online no momento da guerra para lutarem)</font></center>";

			$guildmembers = $db->execute("select * from `players` where `guild`=? order by `level` desc", array($guild['id']));

			echo "<form method=\"post\" action=\"guild_admin_war.php?id=" . $_GET['id'] . "\">\n";

			echo "<input type=\"hidden\" name=\"wnumber\" value=\"" . $_POST['wnumber'] . "\">";
			echo "<input type=\"hidden\" name=\"gold\" value=\"" . $_POST['gold'] . "\">";
			echo "<input type=\"hidden\" name=\"submit\" value=\"Proclamar Guerra\">";

			echo "<table width=\"100%\" border=\"0\">\n";
			echo "<tr>\n";
			echo "<td width=\"5%\"></td>\n";
			echo "<td width=\"30%\"><b>Usu�rio</b></td>\n";
			echo "<td width=\"10%\"><b>N�vel</b></td>\n";
			echo "<td width=\"30%\"><b>Voca��o</b></td>\n";
			echo "<td width=\"25%\"><b>Pontua��o</b></td>\n";
			echo "</tr>\n";

			while($member = $guildmembers->fetchrow())
			{
				$bool = ($bool==1)?2:1;
				echo "<tr class=\"row" . $bool . "\">\n";
				echo "<td width=\"5%\"><input type=\"checkbox\" name=\"id[]\" value=\"" . $member['id'] . "\" /></td>\n";
				echo "<td width=\"30%\"><b><a href=\"profile.php?id=" . $member['username'] . "\">" . $member['username'] . "</a></b></td>";
				echo "<td width=\"10%\">" . $member['level'] . "</td>";
				echo "<td width=\"30%\">";

				if ($member['voc'] == 'archer' and $member['promoted'] == 'f'){
					echo "Ca�ador";
				} else if ($member['voc'] == 'knight' and $member['promoted'] == 'f'){
					echo "Espadachim";
				} else if ($member['voc'] == 'mage' and $member['promoted'] == 'f'){
					echo "Bruxo";
				} else if (($member['voc'] == 'archer') and ($member['promoted'] == 't' or $member['promoted'] == 's' or $member['promoted'] == 'r')){
					echo "Arqueiro";
				} else if (($member['voc'] == 'knight') and ($member['promoted'] == 't' or $member['promoted'] == 's' or $member['promoted'] == 'r')){
					echo "Guerreiro";
				} else if (($member['voc'] == 'mage') and ($member['promoted'] == 't' or $member['promoted'] == 's' or $member['promoted'] == 'r')){
					echo "Mago";
				} else if ($member['voc'] == 'archer' and $member['promoted'] == 'p'){
					echo "Arqueiro Royal";
				} else if ($member['voc'] == 'knight' and $member['promoted'] == 'p'){
					echo "Cavaleiro";
				} else if ($member['voc'] == 'mage' and $member['promoted'] == 'p'){
					echo "Arquimago";
				}

				echo "</td>";
				echo "<td width=\"25%\">" . ceil(($member['kills']*6) + ($member['monsterkilled']/3) + ($member['groupmonsterkilled']/12) - ($member['deaths']*35)) . "</td></tr>";
			}

			echo "</table>";
			echo "<br/><input type=\"submit\" name=\"startwar\" value=\"Proclamar Guerra\"> <a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";

			}else{
				if (!$_POST['id']) {

				echo "Selecione os membros do cl� que devem participar da guerra.";
				echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
				}

				$totalselected = 0;
				foreach($_POST['id'] as $memb)
				{
					$totalselected = $totalselected + 1;
				}

				if ((!$_POST['wnumber']) or (!$_POST['gold'])){
					echo "Por favor preencha todos os campos.";
					echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
				} elseif ((!is_numeric($_POST['gold'])) or ($_POST['gold'] < 1)){
					echo "Insira uma quantia de ouro v�lida.";
					echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
				} elseif ($_POST['gold'] > $guild['gold']){
					echo "Seu cl� n�o possui tanto ouro para apostar.";
					echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
				} elseif ($_POST['gold'] < 10000){
					echo "A aposta m�nima � de 10000 moedas de ouro.";
					echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
				} elseif ($_POST['wnumber'] > $guild['members']){
					echo "Seu cl� n�o possui membros suficientes para uma guerra deste tamanho.";
					echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
				} elseif ($_POST['wnumber'] > $enyguild['members']){
					echo "O cl� " . $enyguild['name'] . " n�o possui membros suficientes para iniciar uma guerra deste tamanho.";
					echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
				} elseif ($_POST['wnumber'] != $totalselected){
					echo "Voc� precisa selecionar " . $_POST['wnumber'] . " membros do cl� para a guerra.";
					echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
				}else{
					$db->execute("update `guilds` set `gold`=`gold`-?, `blocked`=`blocked`+? where `id`=?", array($_POST['gold'], $_POST['gold'], $guild['id']));

					$insert['guild_id'] = $guild['id'];
					$insert['enemy_id'] = $enyguild['id'];
					$insert['bet'] = $_POST['gold'];
					foreach($_POST['id'] as $memb)
					{
						$insertnumber = $insertnumber + 1;
						if ($insertnumber < $totalselected){
						$addmemb .= "$memb, ";
						}else{
						$addmemb .= $memb;
						}

    						$logmsg = "Seu cl� enviou um pedido de guerra ao cl� <b>" . $enyguild['name'] . "</b>, e voc� foi um dos escolhidos para lutar.<br/>Se o cl� <b>" . $enyguild['name'] . "</b> aceitar o convite voc� ser� informado.";
						addlog($memb, $logmsg, $db);
					}
					$insert['players_guild'] = $addmemb;
					$db->autoexecute('pwar', $insert, 'INSERT');

					$lider = $db->GetOne("select `id` from `players` where `username`=?", array($enyguild['leader']));
    					$logmsg = "O cl� <b>" . $guild['name'] . "</b> est� enviando um pedido de guerra estilo <b>". $_POST['wnumber'] . "x". $_POST['wnumber'] . "</b>.<br/>O valor da aposta � de <b>" . $_POST['gold'] . " moedas de ouro</b>. <a href=\"guild_war_request.php?id=" . $db->Insert_ID() . "\">Clique aqui</a> para aceitar o convite.";
					addlog($lider, $logmsg, $db);
					if ($enyguild['vice'] != NULL){
						$vice = $db->GetOne("select `id` from `players` where `username`=?", array($enyguild['vice']));
						addlog($vice, $logmsg, $db);
					}

					echo "<i>Voc� enviou um pedido de guerra para o cl�: " . $enyguild['name'] . ".</i><br/><br/><font size=\"1px\">A guerra ir� come�ar algumas horas depois que o cl� inimigo aceitar o convite.<br/>As " . $_POST['gold'] . " moedas de ouro apostadas n�o poder�o ser retiradas do tesouro do seu cl� enquanto a guerra n�o ocorrer ou o pedido n�o for cancelado.</font>";
					echo "<br/><a href=\"guild_admin_war.php?id=" . $_GET['id'] . "\">Voltar</a>.";
				}

			}
		}
	}else{
	echo "<i>Voc� est� prestes a proclamar guerra com o cl�: " . $enyguild['name'] . "</i><br/>";
	echo "<font size=\"1px\">Selecione o tamanho da batalha e em seguida a quantia de ouro a ser apostada.</font><br/><br/>";

	echo "<form method=\"POST\" action=\"guild_admin_war.php?id=" . $_GET['id'] . "\">";
	echo "<b>Tamanho da batalha:</b> ";
	echo "<select name=\"wnumber\"><option value=''>Selecione</option>";
		if (($guild['members'] >= 3) and ($enyguild['members'] >= 3)){
			echo "<option value=\"3\">3x3</option>";
		}
		if (($guild['members'] >= 5) and ($enyguild['members'] >= 5)){
			echo "<option value=\"5\">5x5</option>";
		}
		if (($guild['members'] >= 7) and ($enyguild['members'] >= 7)){
			echo "<option value=\"7\">7x7</option>";
		}
		if (($guild['members'] >= 10) and ($enyguild['members'] >= 10)){
		echo "<option value=\"10\">10x10</option>";
		}
		if (($guild['members'] >= 15) and ($enyguild['members'] >= 15)){
		echo "<option value=\"15\">15x15</option>";
		}
	echo "</select>";

	echo "<br/><b>Aposta:</b> <input type=\"text\" name=\"gold\" size=\"20\"/> moedas de ouro.";
	echo "<br/><br/><input type=\"submit\" name=\"submit\" value=\"Proclamar Guerra\"> <a href=\"guild_admin_enemy.php\">Voltar</a>.";
	echo "</form>";
	}
}

include("templates/private_footer.php");
?>