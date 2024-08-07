<?php
include("lib.php");
define("PAGENAME", "Miss�es");
$player = check_user($secret_key, $db);
include("checkbattle.php");


if ($player->level < 130)
{
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Gadudj</b></legend>\n";
	echo "<i>Seu nivel � muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include("templates/private_footer.php");
	exit;
}

	$verificaring = $db->execute("select * from `items` where `player_id`=? and `item_id`=163", array($player->id));
	if ($verificaring->recordcount() == 0)
	{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� n�o possui o jeweled ring nesces�rio para fazer esta miss�o.</i>";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
	}


switch($_GET['act'])
{

	case "who":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "<i>Eu fa�o modifica��es nos itens das pessoas, deixando-os melhores.</i><br><br>\n";
		echo "<a href=\"quest4.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	exit;
	break;

	case "help":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "<i>Bom, vejo que voc� est� usando um jeweled ring. Ele � um anel muito poderoso mas eu posso deixa-lo ainda melhor.<br>Se voc� me pagar uma quantia de 250000, modificarei seu anel e ele poder� aumentar at� 15% sua agilidade e for�a. O que acha?</i><br><br>\n";
		echo "<a href=\"quest4.php?act=acept\">Aceito</a> | <a href=\"quest4.php?act=decline\">Recuso</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	exit;
	break;

	case "decline":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "<i>Bom, a escolha � sua.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	exit;
	break;

	case "acept":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "<i>Tem certeza que deseja pagar 250000 pela modifica��o do seu jeweled ring?</i><br><br>\n";
		echo "<a href=\"quest4.php?act=confirmpay\">Sim, tenho certeza</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	exit;
	break;

	case "confirmpay":
	$verificaring = $db->execute("select * from `items` where `player_id`=? and `item_id`=163", array($player->id));
	if ($verificaring->recordcount() == 0)
	{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� n�o possui o jeweled ring nesces�rio para fazer esta miss�o.</i>";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
	}
	$verificacao = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=7", array($player->id));
	if ($verificacao->recordcount() == 0)
		{
		if ($player->gold - 250000 < 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "<i>Voc� n�o possui esta quantia de ouro!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		$query = $db->execute("update `players` set `gold`=? where `id`=?", array($player->gold - 250000, $player->id));
		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 7;
		$insert['quest_status'] = time() + 36000;
		$query = $db->autoexecute('quests', $insert, 'INSERT');
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "<i>Obrigado. Irei optimizar seu anel.  Me encontre aqui em 10h.<br/>Lembre-se que voc� est� sem o seu an�l agora, ent�o n�o tire os seus itens que precisam do jeweled ring.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}
		}else{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "Voc� j� me pagou!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
	}
	break;

	case "search":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Depois de algum tempo procurando voc� avistou Gadudj.<br/>Voc� tem certeza que deseja atacalo? ele possui nivel 112!</i><br><br>\n";
		echo "<a href=\"gadudj.php\">Atacar Gadudj</a> | <a href=\"quest4.php?act=assassin\">Contratar assassino</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	exit;
	break;

	case "assassin":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� encontrou um guerreiro capaz de matar Gadudj. Ele est� cobrando 80000 pelo servi�o. Voc� aceita a oferta?</i><br><br>\n";
		echo "<a href=\"quest4.php?act=buy\">Aceito</a> | <a href=\"quest4.php?act=refuse\">Recuso</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	exit;
	break;

	case "refuse":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Ent�o voc� ter� que enfrentar Gadudj.</i><br><br>\n";
		echo "<a href=\"gadudj.php\">Atacar Gadudj</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	exit;
	break;

	case "buy":

	$vepaoiseee = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=7 and `quest_status`=2", array($player->id));
	if ($vepaoiseee->recordcount() == 0)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Aviso</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu. Contate o administrador.</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	$vepaooosswwe = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=7 and `quest_status`=3", array($player->id));
	if ($vepaooosswwe->recordcount() == 0)
		{
		if ($player->gold - 80000 < 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "<i>Voc� n�o possui esta quantia de ouro!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		$query = $db->execute("update `players` set `gold`=? where `id`=?", array($player->gold - 80000, $player->id));
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(90, $player->id, 7));

		$verificaringeq = $db->execute("select * from `items` where `player_id`=? and `item_id`=163 and `status`='equipped'", array($player->id));
		if ($verificaringeq->recordcount() > 0)
		{
			$db->execute("update `players` set `hp`=`hp`-500, `maxhp`=`maxhp`-500 where `id`=?", array($player->id));
		}

		$query = $db->execute("delete from `items` where `player_id`=? and `item_id`=?", array($player->id, 163));

			$insert['player_id'] = $player->id;
			$insert['item_id'] = 168;
			$db->autoexecute('items', $insert, 'INSERT');
			$ringid = $db->Insert_ID();
			$db->execute("update `items` set `for`=`for`+?, `vit`=`vit`+?, `agi`=`agi`+?, `res`=`res`+? where `id`=?", array(30, 30, 30, 30, $ringid));

		include("templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>O assassino que voc� contratou matou Gadudj e recuperou seu Jeweled Ring.<br>Sua miss�o acabou.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}
		}else{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "Voc� j� pagou ao assassino!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
	}
	break;

}
?>
<?php
	$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 7));
	$quest1 = $verificacao1->fetchrow();

	if ($verificacao1->recordcount() == 0)
		{
	$verificaring = $db->execute("select * from `items` where `player_id`=? and `item_id`=163", array($player->id));
	if ($verificaring->recordcount() == 0)
	{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� n�o possui o jeweled ring nesces�rio para fazer esta miss�o.</i>";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
	}

		include("templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "<i>Ol� " . $player->username . ". Como posso te ajudar?</i><br/><br>\n";
		echo "<a href=\"quest4.php?act=who\">Quem � voc�?</a> | <a href=\"quest4.php?act=help\">Optimizar itens</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] > 100)
		{
			if ($quest1['quest_status'] < time())
			{
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(2, $player->id, 7));
			include("templates/private_header.php");
			echo "<fieldset><legend><b>Miss�o</b></legend>\n";
			echo "<i>Parece que Gadudj fugiu com seu jeweled ring! Voc� ter� que procurar e mata-lo para recuperar seu anel.</i><br><br>";
			echo "<a href=\"quest4.php?act=search\">Procurar por Gadudj</a> | <a href=\"home.php\">Voltar</a>.";
	     	 	echo "</fieldset>";
			include("templates/private_footer.php");
			exit;
			}

		include("templates/private_header.php");
		$time = ($quest1['quest_status'] - time());
		$time_remaining = ceil($time / 60);
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� tem que esperar por Gadudj.</i><br>";
		echo "<i>Faltam $time_remaining minuto(s) para ele chegar.</i><br><br>\n";
		echo "<a href=\"home.php\">P�gina Principal</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 2)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Parece que Gadudj fugiu com seu jeweled ring! Voc� ter� que procurar e mata-lo para recuperar seu anel.</i><br><br>";
		echo "<a href=\"quest4.php?act=search\">Procurar por Gadudj</a> | <a href=\"home.php\">Voltar</a>.";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest1['quest_status'] == 90)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Miss�o</b></legend>\n";
		echo "<i>Voc� j� terminou esta miss�o.</i>";
	     	echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}


?>