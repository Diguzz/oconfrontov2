<?php
$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=4");
if (($player->reino == '1') or ($player->vip > time())) {
	$mana = ($selectmana - 5);
} else {
	$mana = $selectmana;
}

$log = explode(", ", $_SESSION['battlelog'][0]);

if ($player->mana < $mana){
      	if ($log[1] != "Voc� tentou lan�ar um feiti�o mas est� sem mana sufuciente.") {
		array_unshift($_SESSION['battlelog'], "5, Voc� tentou lan�ar um feiti�o mas est� sem mana sufuciente.");
	}
	$otroatak = 5;
}else{
	if ($player->level < 50){
		$curar = rand(30, 100);
	}else{
		$curar = rand($player->level, ($player->level * 2));
	}
	if (($player->hp + $curar) > $player->maxhp){
		$db->execute("update `players` set `hp`=`maxhp` where `id`=?", array($player->id));
		array_unshift($_SESSION['battlelog'], "3, Voc� fez um feiti�o e recuperou toda sua vida.");
	}else{
		$db->execute("update `players` set `hp`=`hp`+? where `id`=?", array($curar, $player->id));
		array_unshift($_SESSION['battlelog'], "3, Voc� fez um feiti�o e recuperou " . $curar . " pontos de vida.");
	}

$db->execute("update `players` set `mana`=`mana`-? where `id`=?", array($mana, $player->id));
$db->execute("update `bixos` set `type`=?, `vez`='e' where `player_id`=?", array(97, $player->id));
}
?>