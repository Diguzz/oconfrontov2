<?php

/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include("lib.php");
define("PAGENAME", "Administra��o do Cl�");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

$error = 0;

//Populates $guild variable
$guildquery = $db->execute("select * from `guilds` where `id`=?", array($player->guild));

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include("templates/private_header.php");

//Guild Leader Admin check
if (($player->username != $guild['leader']) and ($player->username != $guild['vice'])) {
    echo "Voc� n�o pode acessar esta p�gina.";
    echo "<br/><a href=\"home.php\">Voltar</a>.";
} else {

if (isset($_POST['username']) && ($_POST['submit'])) {
    
	$queryuser = $db->execute("select `id`, `username`, `guild` from `players` where `username`=?", array($_POST['username']));

    if ($queryuser->recordcount() == 0) {
    	$errmsg .= "Este usu�rio n�o existe!<p />";
    	$error = 1;
   	} else if ($_POST['username'] == $guild['leader']) {
   		$errmsg .= "Voc� n�o pode expulsar o lider do cl�!<p />";
   		$error = 1;
   	} else if ($_POST['username'] == $guild['vice']) {
   		$errmsg .= "Voc� n�o pode expulsar o vice-lider do cl�!<p />";
   		$error = 1;
	} else {
   		$member = $queryuser->fetchrow();
	   		if ($member['guild'] != $guild['id']) {
    			$errmsg .= "O usu�rio " . $member['username'] ." n�o faz parte do cl� " . $guild['name'] ."!<p />";
    			$error = 1;
    			} else {
    			$query = $db->execute("update `guilds` set `members`=? where `id`=?", array($guild['members'] - 1, $guild['id']));
    			$query1 = $db->execute("update `players` set `guild`=? where `username`=?", array(NULL, $member['username']));
    			$logmsg = "Voc� foi expulso do cl�: ". $guild['name'] .".";
				addlog($member['id'], $logmsg, $db);
    			$msg .= "Voc� expulsou " . $member['username'] . " do cl�.<p />";
    		}
    	}
	}

?>

<fieldset>
<legend><b><?=$guild['name']?> :: Expulsar Membro</b></legend>
<form method="POST" action="guild_admin_kick.php">
<b>Usu�rio:</b> <?php $query = $db->execute("select `id`, `username` from `players` where `guild`=?", array($guild['id']));
echo "<select name=\"username\"><option value=''>Selecione</option>";
while($result = $query->fetchrow()){
echo "<option value=\"$result[username]\">$result[username]</option>";
}
echo "</select>"; ?> <input type="submit" name="submit" value="Expulsar">
</form>
</fieldset>
<a href="guild_admin.php">Voltar</a>.

<center><p /><font color=green><?=$msg?></font><p /></center>
<center><p /><font color=red><?=$errmsg?></font><p /></center>

<?php
}
include("templates/private_footer.php");
?>