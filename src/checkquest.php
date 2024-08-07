<?php
if ($player->level > 79 and $player->promoted == "f")
{
	if ($player->voc == 'archer'){
		$voc = "Arqueiro";
	} elseif ($player->voc == 'knight'){
		$voc = "Guerreiro";
	} elseif ($player->voc == 'mage'){
		$voc = "Mago";
	}

	echo showAlert("<font size=\"1px\"><b>Voc� j� passou no n�vel 79, e agora voc� pode virar um " . $voc  . ". <a href=\"promote.php\">Clique aqui</a>.</b></font>", "red");
}

$checaquestring = $db->execute("select `id` from `quests` where `quest_id`=2 and `quest_status`=90 and `player_id`=?", array($player->id));
if ($player->level > 99 and $checaquestring->recordcount() == 0)
{
	echo showAlert("<font size=\"1px\"><b>Voc� j� passou no n�vel 99, e tem uma miss�o � fazer. <a href=\"quest1.php\">Clique aqui</a>.</b></font>", "red");
}

$checaquestring = $db->execute("select `id` from `quests` where `quest_id`=7 and `quest_status`=90 and `player_id`=?", array($player->id));
if ($player->level > 129 and $checaquestring->recordcount() == 0)
{
	echo showAlert("<font size=\"1px\"><b>Voc� j� passou no n�vel 129, e tem uma miss�o � fazer. <a href=\"quest4.php\">Clique aqui</a>.</b></font>", "red");
}


$chdrgdrg = $db->execute("select `id` from `quests` where `quest_id`=9 and `quest_status`=90 and `player_id`=?", array($player->id));
if ($player->level > 144 and $player->level < 156 and $chdrgdrg->recordcount() == 0)
{
	echo showAlert("<font size=\"1px\"><b>Voc� est� entre o n�vel 145 e 155, e tem uma miss�o � fazer. <a href=\"quest5.php\">Clique aqui</a>.</b></font>", "red");
}

/* $chdsadasdasg = $db->execute("select `id` from `quests` where `quest_id`=11 and `quest_status`=90 and `player_id`=?", array($player->id));
if ($player->level > 159 and $chdsadasdasg->recordcount() == 0)
{
	echo showAlert("<font size=\"1px\"><b>Voc� j� passou do n�vel 159, e pode comprar itens especiais no ferreiro. <a href=\"shop.php\">Clique aqui</a>.</b></font>", "red");
} */

if ($player->level > 239 and $player->promoted != "p")
{
	if ($player->voc == 'archer'){
		$voc = "Arqueiro Royal";
	} elseif ($player->voc == 'knight'){
		$voc = "Cavaleiro";
	} elseif ($player->voc == 'mage'){
		$voc = "Arquimago";
	}

	echo showAlert("<font size=\"1px\"><b>Voc� j� passou no n�vel 239, e agora voc� pode virar um " . $voc . ". <a href=\"promo1.php\">Clique aqui</a>.</b></font>", "red");
}


$treinaquest1 = $db->execute("select `id` from `quests` where `quest_id`=14 and (`quest_status`=90 or `quest_status`=89) and `player_id`=?", array($player->id));
if ($player->level > 299 and $treinaquest1->recordcount() == 0)
{
	echo showAlert("<font size=\"1px\"><b>Voc� j� passou do n�vel 300, e tem uma miss�o � fazer. <a href=\"quest6.php\">Clique aqui</a>.</b></font>", "red");
}


$treinaquest2 = $db->execute("select `id` from `quests` where `quest_id`=15 and `quest_status`=90 and `player_id`=?", array($player->id));
if (($player->level > 299) and ($treinaquest1->recordcount() != 0) and ($treinaquest2->recordcount() == 0))
{
	echo showAlert("<font size=\"1px\"><b>Voc� j� passou do n�vel 300, e tem uma miss�o � fazer. <a href=\"quest7.php\">Clique aqui</a>.</b></font>", "red");
}


$treinaquest3 = $db->execute("select `id` from `quests` where `quest_id`=17 and `quest_status`=90 and `player_id`=?", array($player->id));
if (($player->level > 299) and ($treinaquest2->recordcount() != 0) and ($treinaquest3->recordcount() == 0))
{
	echo showAlert("<font size=\"1px\"><b>Voc� j� passou do n�vel 300, e tem uma miss�o � fazer. <a href=\"quest8.php\">Clique aqui</a>.</b></font>", "red");
}


$treinaquest4 = $db->execute("select `id` from `quests` where `quest_id`=18 and `quest_status`=90 and `player_id`=?", array($player->id));
if (($player->level > 299) and ($treinaquest3->recordcount() != 0) and ($treinaquest4->recordcount() == 0))
{
	echo showAlert("<font size=\"1px\"><b>Voc� j� passou do n�vel 300, e tem uma miss�o � fazer. <a href=\"quest9.php\">Clique aqui</a>.</b></font>", "red");
}
?>