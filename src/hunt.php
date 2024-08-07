<?php
include("lib.php");
define("PAGENAME", "Trabalhar");
$player = check_user($secret_key, $db);

include("checkbattle.php");
include("checkhp.php");

$totaltime = 0;
$counthours = $db->execute("select `hunttime` from `hunt` where `start`>? and `player_id`=? and `status`!='a'", array(time() + 604800, $player->id));
while($hours = $counthours->fetchrow())
{
	$totaltime += $totaltime + $hours['hunttime'];
}


	if ($_GET['act'] == cancel){	
		include("templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Ca�a</b></legend>";
		echo "Tem certeza que deseja parar de ca�ar agora? Se abandona-la, n�o ganhar� nada. ";
		echo "<a href=\"hunt.php?act=remove\">Desejo abandonar a ca�a</a>.";
		echo "</fieldset><br/><a href=\"home.php\">Principal</a>.";
		include("templates/private_footer.php");
		exit;
	}

	elseif ($_GET['act'] == remove){
		$query = $db->execute("update `hunt` set `status`='a' where `player_id`=? and `status`='t'", array($player->id));
		include("templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Ca�a</b></legend>";
		echo "Voc� abandonou sua ca�a.";
		echo "</fieldset><br/><a href=\"home.php\">Principal</a>.";
		include("templates/private_footer.php");
		exit;
	}


include("checkwork.php");


if (($_POST['cacatime']) && ($_POST['cacastart']))  {

if ((!is_numeric($_POST['cacatime'])) or ($_POST['cacatime'] > 12)) {
	include("templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Ca�ar</b></legend>";
	echo "Voc� precisa preencher todos os campos.";
	echo "</fieldset><br /><a href=\"hunt.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

if ((($player->reino != '2') and ($player->vip < time())) and ((($player->level < 40) and ($_POST['cacatime'] > 2)) or (($player->level < 60) and ($_POST['cacatime'] > 2.5)) or (($player->level < 80) and ($_POST['cacatime'] > 3)) or (($player->level < 120) and ($_POST['cacatime'] > 3.5)) or (($player->level < 140) and ($_POST['cacatime'] > 4)))){
	include("templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Ca�ar</b></legend>";
	echo "Voc� n�o pode ca�ar por tanto tempo.";
	echo "</fieldset><br /><a href=\"hunt.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

if ((($player->reino == '2') or ($player->vip > time())) and ((($player->level < 40) and ($_POST['cacatime'] > 2.5)) or (($player->level < 60) and ($_POST['cacatime'] > 3)) or (($player->level < 80) and ($_POST['cacatime'] > 3.5)) or (($player->level < 120) and ($_POST['cacatime'] > 4)) or (($player->level < 140) and ($_POST['cacatime'] > 5)))){
	include("templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Ca�ar</b></legend>";
	echo "Voc� n�o pode ca�ar por tanto tempo.";
	echo "</fieldset><br /><a href=\"hunt.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

	if (($player->tour == t) and ($setting->tournament != 'f')) {
		include("templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Ca�ar</b></legend>";
		echo "Voc� n�o pode ca�ar enquanto participa ou est� inscrito em um torneio.";
		echo "</fieldset><br /><a href=\"hunt.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	}

	if (($totaltime + $_POST['cacatime']) > 36) {
		include("templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Ca�ar</b></legend>";
		echo "Voc� anda trabalhando demais. O m�ximo permido por semana � de 36 horas.<br/>Voc� ainda pode ca�ar por " . (36 - $totaltime) . "h esta semana.";
		echo "</fieldset><br /><a href=\"hunt.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	}

		$checkmonster = $db->execute("select `id`, `username`, `mtexp` from `monsters` where `level`<=? and `evento`!='n' and `evento`!='t' order by `level` desc limit 1", array($player->level));
		if ($checkmonster->recordcount() == 0) {
			include("templates/private_header.php");
			echo "<fieldset>";
			echo "<legend><b>Ca�ar</b></legend>";
			echo "Um erro desconhecido ocorreu!";
			echo "</fieldset><br /><a href=\"hunt.php\">Voltar</a>.";
			include("templates/private_footer.php");
			exit;
		}else{
			$monster = $checkmonster->fetchrow();
		}

		if (($monster['level'] >= $player->level) and ($monster['level'] != 1)){
			include("templates/private_header.php");
			echo "<fieldset>";
			echo "<legend><b>Ca�ar</b></legend>";
			echo "Voc� s� pode ca�ar monstros de n�vel mais baixo que o seu.";
			echo "</fieldset><br /><a href=\"hunt.php\">Voltar</a>.";
			include("templates/private_footer.php");
			exit;
		}



			$insert['player_id'] = $player->id;
			$insert['start'] = time();
			$insert['hunttype'] = $monster['id'];
			$insert['hunttime'] = $_POST['cacatime'];
			$query = $db->autoexecute('hunt', $insert, 'INSERT');

		include("templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Ca�ar</b></legend>";
		echo "Voc� est� ca�ando: <b>" . $monster['username'] . "</b>.<br/>M�dia de <b>" . (($monster['mtexp']) * 20) . "</b> pontos de experi�ncia hora.<br/>Voc� ir� ca�ar por <b>" . $_POST['cacatime'] . " hora(s)</b>.";
		echo "</fieldset><br /><a href=\"home.php\">Principal</a>.";
		include("templates/private_footer.php");
		exit;
}


include("templates/private_header.php");

	if ($player->reino == '2') {
		echo showAlert("<i>Voc� pode ca�ar por uma hora a mais, pelo fato de ser um membro do reino Eroda.</i>");
	} elseif ($player->vip > time()) {
		echo showAlert("<i>Voc� pode ca�ar por uma hora a mais, pelo fato de ser um membro vip.</i>");
	}


		echo "<form method=\"POST\" action=\"hunt.php\">";
				echo "<fieldset>";
				echo "<legend><b>Ca�ar</b></legend>";
				echo "<table width=\"100%\" border=\"0\">";
				echo "<tr>";
				echo "<td width=\"15%\"><b>Monstro:</b></td>";

				$query = $db->execute("select `id`, `username` from `monsters` where `level`<=? and `evento`!='n' and `evento`!='t' order by `level` desc limit 1", array($player->level));
				echo "<td>";
					$result = $query->fetchrow();
					echo $result[username];
				echo ".</td>";

			echo "</tr><tr>";

			echo "<td width=\"15%\"><b>Tempo:</b></td>";
			echo "<td><select name=\"cacatime\">";
				echo "<option value=\"0.5\" selected=\"selected\">30 minutos</option>";
				echo "<option value=\"1\">1 hora</option>";
				echo "<option value=\"1.5\">1 hora e 30 minutos</option>";
			if (($player->level >= 40) or ($player->reino == '2') or ($player->vip > time())){
				echo "<option value=\"2\">2 horas</option>";
			}
			if (($player->level >= 60) or (($player->level >= 40) and (($player->reino == '2') or ($player->vip > time())))){
				echo "<option value=\"2.5\">2 horas e 30 minutos</option>";
			}
			if (($player->level >= 80) or (($player->level >= 60) and (($player->reino == '2') or ($player->vip > time())))){
				echo "<option value=\"3\">3 horas</option>";
			}
			if (($player->level >= 120) or (($player->level >= 80) and (($player->reino == '2') or ($player->vip > time())))){
				echo "<option value=\"3.5\">3 horas e 30 minutos</option>";
			}
			if (($player->level >= 140) or (($player->level >= 120) and (($player->reino == '2') or ($player->vip > time())))){
				echo "<option value=\"4\">4 horas</option>";
			}
			if (($player->level >= 140) and (($player->reino == '2') or ($player->vip > time()))){
				echo "<option value=\"5\">5 horas</option>";
			}

			echo "</select>";

			if ($player->level < 40){
				if (($player->reino == '2') or ($player->vip > time())) {
					echo " <font size=\"1\">Apartir do n�vel 40 voc� poder� ca�ar por 2h e 30 min.</font>";
				} else {
					echo " <font size=\"1\">Apartir do n�vel 40 voc� poder� ca�ar por 2h.</font>";
				}
			} elseif ($player->level < 60){
				if (($player->reino == '2') or ($player->vip > time())) {
					echo " <font size=\"1\">Apartir do n�vel 60 voc� poder� ca�ar por 3h.</font>";
				} else {
					echo " <font size=\"1\">Apartir do n�vel 60 voc� poder� ca�ar por 2h e 30 min.</font>";
				}
			} elseif ($player->level < 80){
				if (($player->reino == '2') or ($player->vip > time())) {
					echo " <font size=\"1\">Apartir do n�vel 80 voc� poder� ca�ar por 3h e 30 min.</font>";
				} else {
					echo " <font size=\"1\">Apartir do n�vel 80 voc� poder� ca�ar por 3h.</font>";
				}
			} elseif ($player->level < 120){
				if (($player->reino == '2') or ($player->vip > time())) {
					echo " <font size=\"1\">Apartir do n�vel 120 voc� poder� ca�ar por 4h.</font>";
				} else {
					echo " <font size=\"1\">Apartir do n�vel 120 voc� poder� ca�ar por 3h e 30 min.</font>";
				}
			} elseif ($player->level < 140){
				if (($player->reino == '2') or ($player->vip > time())) {
					echo " <font size=\"1\">Apartir do n�vel 140 voc� poder� ca�ar por 4h e 30 min.</font>";
				} else {
					echo " <font size=\"1\">Apartir do n�vel 140 voc� poder� ca�ar por 4h.</font>";
				}
			}

			echo "</td></tr></table>";
		echo "</fieldset>";

		echo "<table width=\"100%\" border=\"0\">";
		echo "<tr><td width=\"30%\"><input type=\"submit\" name=\"cacastart\" value=\"Come�ar a ca�a\" /></td><td width=\"70%\" align=\"right\">";
		echo "<font size=\"1\">Voc� ainda pode ca�ar por " . (36 - $totaltime) . " horas esta semana.</font>";
		echo "</td></tr></table></form>";
		echo "<br />";


echo "<table width=\"100%\">";
echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>�ltimas Ca�as</b></td></tr>";
$query1 = $db->execute("select * from `hunt` where `player_id`=? and `status`!='t' order by `start` desc limit 10", array($player->id));
if ($query1->recordcount() > 0)
{
	while ($log1 = $query1->fetchrow())
	{
		$valortempo = time() - $log1['start'];
		if ($valortempo < 60){
		$valortempo2 = $valortempo;
		$auxiliar2 = "segundo(s) atr�s.";
		}else if($valortempo < 3600){
		$valortempo2 = floor($valortempo / 60);
		$auxiliar2 = "minuto(s) atr�s.";
		}else if($valortempo < 86400){
		$valortempo2 = floor($valortempo / 3600);
		$auxiliar2 = "hora(s) atr�s.";
		}else if($valortempo > 86400){
		$valortempo2 = floor($valortempo / 86400);
		$auxiliar2 = "dia(s) atr�s.";
		}

		$huntmonstername = $db->GetOne("select `username` from `monsters` where `id`=?", array($log1['hunttype']));
		$huntmonsterlevel = $db->GetOne("select `level` from `monsters` where `id`=?", array($log1['hunttype']));
		$huntmonstermtexp = $db->GetOne("select `mtexp` from `monsters` where `id`=?", array($log1['hunttype']));


			$expwin1 = $huntmonsterlevel * 6;
			$expwin2 = (($player->level - $huntmonsterlevel) > 0)?$expwin1 - (($player->level - $huntmonsterlevel) * 3):$expwin1 + (($player->level - $huntmonsterlevel) * 3);
			$expwin2 = ($expwin2 <= 0)?1:$expwin2;
			$expwin3 = round(0.5 * $expwin2);
			$expwin = rand($expwin3, $expwin2);
			$goldwin = round(0.9 * $expwin);
			if ($setting->eventoouro > time()){
				$goldwin = round($goldwin * 2);
			}

			$huntgold = ceil(($goldwin * 7) * $log1['hunttime']);

		echo "<tr>";
		if ($log1['status'] == 'a'){
		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\"><font size=\"1\">Voc� come�ou a ca�ar " . $huntmonstername . " mas abandonou sua ca�a.</font></div></td>";
		}else{
		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\"><font size=\"1\">Voc� ca�ou " . $huntmonstername . " por " . $log1['hunttime'] . " horas e ganhou " . ((($huntmonstermtexp) * 20) * $log1['hunttime']) . " pontos de esperi�ncia e " . $huntgold . " moedas de ouro.</font></div></td>";
		}
		echo "</tr>";
	}
}
else
{
	echo "<tr>";
	echo "<td class=\"off\"><font size=\"1\">Nenhum registro encontrado!</font></td>";
	echo "</tr>";
}
echo "</table>";

include("templates/private_footer.php");
?>
