<?php
include("lib.php");
define("PAGENAME", "Reino");
$player = check_user($secret_key, $db);
$msg = null;

$query = $db->execute("select * from `reinos` where `id`=?", array($player->reino));
$reino = $query->fetchrow();

if ($reino['imperador'] == $player->id) {
	if ($_POST['submit']){
		if (($_POST['time'] == 15) or ($_POST['time'] == 30) or ($_POST['time'] == 60)){
			$count = $db->execute("select `id` from `players` where `reino`=?", array($player->reino));
			if ($_POST['time'] == 15) {
				$preco = ceil(100 * $count->recordcount());
			} elseif ($_POST['time'] == 30) {
				$preco = ceil(145 * $count->recordcount());
			} elseif ($_POST['time'] == 60) {
				$preco = ceil(175 * $count->recordcount());
			}

			if ($preco > $reino['ouro']) {
				include("templates/private_header.php");
				echo "Seu reino n�o possui ouro suficiente para esta mudan�a. <a href=\"reino.php\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
			}

			$db->execute("update `reinos` set `gates`=?, `ouro`=`ouro`-? where `id`=?", array((time() + (60 * $_POST['time'])), $preco, $player->reino));

			$query = $db->execute("select `id` from `players` where `id`!=? and `reino`=?", array($player->id, $player->reino));
			while($member = $query->fetchrow()) {
				$logmsg = "Os port�es do reino foram abertos por " . $_POST['time'] . " minutos.";
				addlog($member['id'], $logmsg, $db);
			}

			$insert['reino'] = $player->reino;
			$insert['log'] = "Os port�es do reino foram abertos por " . $_POST['time'] . " minutos ap�s o imperador pagar uma taxa de " . $preco . " moedas de ouro.";
			$insert['time'] = time();
			$db->autoexecute('log_reino', $insert, 'INSERT');

			$query = $db->execute("select * from `reinos` where `id`=?", array($player->reino));
			$reino = $query->fetchrow();
			
			$msg = "Os port�es do reino foram abertos por " . $_POST['time'] . " minutos.";
		}
	}

	include("templates/private_header.php");
	if ($msg != null)
	{
		echo showAlert($msg, "green");
	} else {
		echo showAlert($reino['ouro'] . " moedas de ouro nos cofres do reino.");
	}
	
	echo "<table width=\"100%\" align=\"center\">";
	echo "<tr><td width=\"35%\">";

		echo "<table width=\"100%\" style=\"text-align: center;\">";
			echo "<tr><td class=\"brown\" width=\"100%\"><center><b>Pre�os</b></center></td></tr>";
			echo "<tr><td class=\"off\">";

				$count = $db->execute("select `id` from `players` where `reino`=?", array($player->reino));
				echo "<table width=\"100%\">";
				echo "<tr><td width=\"25%\">15 min</td><td>custam</td><td>" . ceil(1750 * $count->recordcount()) . "</td></tr>";
				echo "<tr><td width=\"25%\">30 min</td><td>custam</td><td>" . ceil(2700 * $count->recordcount()) . "</td></tr>";
				echo "<tr><td width=\"25%\">60 min</td><td>custam</td><td>" . ceil(3200 * $count->recordcount()) . "</td></tr>";
				echo "</table>";

				echo "<font size=\"1px\">Os port�es s� podem ser abertos a cada 3 dias.</font>";

			echo "</td></tr>";
		echo "</table>";

	echo "</td>";
	echo "<td width=\"65%\">";

		echo "<table width=\"100%\" style=\"text-align: center;\">";
			echo "<tr><td class=\"brown\" width=\"100%\"><center><b>Abrir port�es</b></center></td></tr>";
			echo "<tr><td class=\"salmon\">";

				echo "<font size=\"1px\">Ao abrir os port�es <b>novos monstros</b> ficar�o dispon�veis para combate.<br/>Estes monstros carregam <b>mais ouro e experi�ncia</b> que o usual.</font>";

				echo "<p>";
					if ($reino['gates'] > time()){
						echo "<b>Os port�es do reino est�o abertos!</b>";
					} elseif (($reino['gates'] + 255600) < time()){
						echo "<form method=\"POST\" action=\"reino_gates.php\">";
						echo "<b>Abrir port�es por:</b> ";

						echo "<select name=\"time\">";
							echo "<option value=\"0\" selected=\"selected\">0 minutos</option>";
							echo "<option value=\"15\">15 minutos</option>";
							echo "<option value=\"30\">30 minutos</option>";
							echo "<option value=\"60\">60 minutos</option>";
						echo "</select>";

						echo "<input type=\"submit\" name=\"submit\" value=\"Abrir\">";
						echo "</form>";
					} else {
						echo "<b>Voc� j� abriu os port�es nos �ltimos 3 dias!</b>";
					}
				echo "</p>";

			echo "</td></tr>";
		echo "</table>";

	echo "</td></tr>";
	echo "</table>";
	echo "<a href=\"reino.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
} else {
    header("Location: home.php");
}
?>