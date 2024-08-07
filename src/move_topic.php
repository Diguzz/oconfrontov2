<?php

include("lib.php");
define("PAGENAME", "F�rum");
$player = check_user($secret_key, $db);

include("templates/private_header.php");

if (!$_GET['topic'])
{
	echo "Um erro desconhecido ocorreu! <a href=\"main_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}
	$procuramensagem = $db->execute("select `topic`, `user_id` from `forum_question` where `id`=?", array($_GET['topic']));
	if ($procuramensagem->recordcount() == 0)
	{
		echo "Um erro desconhecido ocorreu! <a href=\"main_forum.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	}else{
    		$nome = $procuramensagem->fetchrow();
	}

	if (($player->gm_rank < 3) and ($player->id != $nome['user_id'])) {
		echo "Voc� n�o tem permis�es para mover este t�pico! <a href=\"view_topic.php?id=" . $_GET['topic'] . "\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	}


if(isset($_POST['submit']))
{
	$verifica = $db->GetOne("select `imperador` from `reinos` where `id`=?", array($player->reino));

	if (!$_POST['category'])
	{
		echo "Voc� precisa preencher todos os campos! <a href=\"move_topic.php?topic=" . $_GET['topic'] . "\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	}

	elseif (($_POST['category'] != 'reino') and ($_POST['category'] != 'sugestoes') and ($_POST['category'] != 'gangues') and ($_POST['category'] != 'trade') and ($_POST['category'] != 'duvidas') and ($_POST['category'] != 'outros') and ($_POST['category'] != 'fan') and ($_POST['category'] != 'off') and ($player->gm_rank < 9)) {
		$error = "Voc� n�o possui autoriza��o para mover t�picos para essa categoria.";
		include("templates/private_footer.php");
		exit;
	}

	elseif (($_POST['category'] == 'reino') and ($player->id != $verifica) and ($player->gm_rank < 9)) {
		$error = "Voc� n�o possui autoriza��o para mover t�picos para essa categoria.";
		include("templates/private_footer.php");
		exit;
	}


if ($_POST['category'] == 'gangues') {
$categoria = "Cl�s";
}elseif ($_POST['category'] == 'trade') {
$categoria = "Compro/Vendo";
}elseif ($_POST['category'] == 'noticias') {
$categoria = "Not�cias";
}elseif ($_POST['category'] == 'sugestoes') {
$categoria = "Sugest�es";
}elseif ($_POST['category'] == 'duvidas') {
$categoria = "D�vidas";
}elseif ($_POST['category'] == 'fan') {
$categoria = "Fanwork";
}elseif ($_POST['category'] == 'off') {
$categoria = "Off-Topic";
}else{
$categoria = ucfirst($_POST['category']);
}

	if ($player->gm_rank > 2) {
		$log = "O t�pico " . $nome['topic'] . " foi movido para a sess�o " . $categoria . " pelo moderador <b>" . $player->username . "</b>";
		forumlog($log, $db);
	}


$real = $db->execute("update `forum_question` set `category`=? where `id`=?", array($_POST['category'], $_GET['topic']));
	echo "Postagem movida com sucesso! <a href=\"view_topic.php?id=" . $_GET['topic'] . "\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

?>

<table width="500" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<form method="POST" action="move_topic.php?topic=<?=$_GET['topic']?>">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td colspan="3" bgcolor="#E6E6E6"><strong>Mover T�pico</strong> </td>
</tr>
<tr>
<td>Para onde deseja mover o t�pico: <b><?=$nome['topic']?></b> ?<br/>
<select name="category">
<option value="none" selected="selected">Selecione</option>
<?php
$verifica = $db->GetOne("select `imperador` from `reinos` where `id`=?", array($player->reino));
if ($player->gm_rank > 9) {
	echo "<option value=\"noticias\">Not�cias</option>";
}

if (($verifica == $player->id) or ($player->gm_rank > 9)) {
	echo "<option value=\"reino\">Reino</option>";
}
?>
<option value="sugestoes">Sugest�es</option>
<option value="gangues">Cl�s</option>
<option value="trade">Compro/Vendo</option>
<option value="duvidas">Duvidas</option>
<option value="fan">Fanwork</option>
<option value="outros">Outros</option>
<option value="off">Off-Topic</option></td>
</tr>
<tr>
<td><input type="submit" name="submit" value="Mover" /></td>
</tr>
</table>
</td>
</form>
</tr>
</table>
<?php
include("templates/private_footer.php");
?>