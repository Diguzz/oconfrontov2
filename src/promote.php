<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include("lib.php");
define("PAGENAME", "Miss�es");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkhp.php");
include("checkwork.php");

		if ($player->voc == 'archer')
		{
		$futuravocacao = "Arqueiro";
		}
		else if ($player->voc == 'knight')
		{
		$futuravocacao = "Guerreiro";
		}
		else if ($player->voc == 'mage')
		{
		$futuravocacao = "Mago";
		}

if ($player->promoted == t)
{
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Treinador</b></legend>\n";
	echo "<i>Voc� j� possui uma voca��o superior!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo '</fieldset>';
	include("templates/private_footer.php");
	exit;
}

if ($player->level < 80)
{
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Treinador</b></legend>\n";
	echo "<i>Seu nivel � muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include("templates/private_footer.php");
	exit;
}

switch($_GET['act'])
{
	case "pay":
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Voc� est� disposto a me pagar 80000 de ouro para come�ar as miss�es?</i><br>\n";
		echo "<a href=\"promote.php?act=confirmpay\">Sim eu estou</a> | <a href=\"home.php\">Deixar para depois</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
	break;

	case "confirmpay":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 1));
	if ($verificacao->recordcount() == 0)
		{
		if ($player->gold - 80000 < 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Voc� n�o possui esta quantia de ouro!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		$query = $db->execute("update `players` set `gold`=? where `id`=?", array($player->gold - 80000, $player->id));
		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 1;
		$insert['quest_status'] = 1;
		$query = $db->autoexecute('quests', $insert, 'INSERT');
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Pronto, agora podemos continuar com as miss�es.</i><br>\n";
		echo "<a href=\"promote.php\">Continuar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}
		}else{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "Voc� j� nos pagou esta taixa!</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}
	break;

	case "continue1":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 1));
	$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		if ($statux['quest_status'] != 1){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		
		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", array($player->id, 107));
		if ($selectfirstitem->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Voc� n�o possui um Wind Orb.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(2, $player->id, 1));
		$query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", array(107, $player->id, 1));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Obrigado, agora podemos passar para a segunda miss�o.</i><br>\n";
		echo "<a href=\"promote.php\">Continuar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}
	break;

	case "continue2":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 1));
	$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		if ($statux['quest_status'] != 2){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		
		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", array($player->id, 108));
		if ($selectfirstitem->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Voc� n�o possui um Earth Orb.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(3, $player->id, 1));
		$query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", array(108, $player->id, 1));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Obrigado, agora podemos passar para a terceira miss�o.</i><br>\n";
		echo "<a href=\"promote.php\">Continuar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}
	break;

	case "continue3":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 1));
	$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		if ($statux['quest_status'] != 3){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		
		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", array($player->id, 110));
		if ($selectfirstitem->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Voc� n�o possui um Water Orb.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(4, $player->id, 1));
		$query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", array(110, $player->id, 1));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Obrigado, agora podemos passar para a ultima miss�o.</i><br>\n";
		echo "<a href=\"promote.php\">Continuar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}
	break;

	case "continue4":
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 1));
	$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		if ($statux['quest_status'] != 4){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

		
		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", array($player->id, 109));
		if ($selectfirstitem->recordcount() == 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Voc� n�o possui um Fire Orb.</i><br/><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}else{
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", array(90, $player->id, 1));
		$query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", array(109, $player->id, 1));
		$query = $db->execute("update `players` set `promoted`=? where `id`=?", array(t, $player->id));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Pronto! Voc� me provou que � um �timo guerreiro, e como eu tinha lhe prometido, <b>estou te promovendo para $futuravocacao!</b></i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		}
	break;

}
?>
<?php
	$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", array($player->id, 1));
	$quest = $verificacao->fetchrow();

	if ($verificacao->recordcount() == 0)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Vejo que voc� deseja se tornar um <b>$futuravocacao</b>.</i>\n";
		echo " <i>Com uma voca��o superior seu ataque e sua defesa aumentam, e voc� pode usar itens para voca��es superiores!</i><br/><br/>";
		echo "<i>Se voc� completar algumas pequenas miss�es e me pagar uma quantia de <b>80000 moedas de ouro</b>, voc� se transformar� em um $futuravocacao!</i><br/><br/>\n";
		echo "<a href=\"promote.php?act=pay\">Aceito as miss�es</a> | <a href=\"home.php\">Deixar para depois</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 1)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Seu primeiro desafio � conseguir um <b>Wind Orb</b>. Voc� pode obtelo matando Decapitadores ou comprando no mercado.</i><br/><br/>\n";
		echo "<a href=\"promote.php?act=continue1\">Continuar miss�o</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 2)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Seu segundo desafio � conseguir um <b>Earth Orb</b>. Voc� pode obtelo matando Guerreiros Zumbi ou comprando no mercado.</i><br/><br/>\n";
		echo "<a href=\"promote.php?act=continue2\">Continuar miss�o</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 3)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Seu terceiro desafio � conseguir um <b>Water Orb</b>. Voc� pode obtelo matando Taurens ou comprando no mercado.</i><br/><br/>\n";
		echo "<a href=\"promote.php?act=continue3\">Continuar miss�o</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($quest['quest_status'] == 4)
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Seu ultimo desafio � conseguir um <b>Fire Orb</b>. Voc� pode obtelo matando Menderiels ou comprando no mercado.</i><br/><br/>\n";
		echo "<a href=\"promote.php?act=continue4\">Finalizar miss�o</a> | <a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}

	if ($player->promoted == 't')
		{
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i>Voc� j� possui uma voca��o superior!</i><br/>\n";
		echo "<a href=\"home.php\">Voltar</a>.";
	        echo "</fieldset>";
		include("templates/private_footer.php");
		exit;
		}
?>