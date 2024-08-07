<?php
	include("lib.php");
	define("PAGENAME", "Enviar Imagens");
	$msg = "";
    
if ($setting->allow_upload != t)
{
	$player = check_user($secret_key, $db);
include("templates/private_header.php");
echo "O envio de imagens est� desativado no momento. <a href=\"home.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}

    
if (($_POST['upload']) and ($_FILES["foto"]))
{
$erro = $config = array();

// Prepara a vari�vel do arquivo
$arquivo = isset($_FILES["foto"]) ? $_FILES["foto"] : FALSE;

// Tamanho m�ximo do arquivo (em bytes)
$config["tamanho"] = 1048576;
// Largura m�xima (pixels)
$config["largura"] = 1400;
// Altura m�xima (pixels)
$config["altura"] = 1024;

// Formul�rio postado... executa as a��es
if($arquivo)
{
// Verifica se o mime-type do arquivo � de imagem
if ((!@GetImageSize($arquivo["tmp_name"])) or (!preg_match("/^image\/(gif|bmp|png|jpg|jpeg)$/i", $arquivo["type"])))
{
$erro[] = "<span style=\"color: white; border: solid 1px ; background: red;\">Arquivo em formato inv�lido!</span><br/>- A imagem deve ser jpg, jpeg, png, bmp ou gif.";
}
else
{
// Verifica tamanho do arquivo
if($arquivo["size"] > $config["tamanho"])
{
$erro[] = "<span style=\"color: white; border: solid 1px ; background: red;\">Arquivo em tamanho muito grande!</span><br>- A imagem deve ser de no m�ximo 1 MB.";
}

// Para verificar as dimens�es da imagem
$tamanhos = getimagesize($arquivo["tmp_name"]);

// Verifica largura
if($tamanhos[0] > $config["largura"])
{
$erro[] = "Largura da imagem n�o deve ultrapassar " . $config["largura"] . " pixels";
}

// Verifica altura
if($tamanhos[1] > $config["altura"])
{
$erro[] = "Altura da imagem n�o deve ultrapassar " . $config["altura"] . " pixels";
}
}

// Imprime as mensagens de erro
if(sizeof($erro))
{
foreach($erro as $err)
{
        $msg .= " - " . $err . "<BR>";
}
}

// Verifica��o de dados OK, nenhum erro ocorrido, executa ent�o o upload...
else
{
// Pega extens�o do arquivo
preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext);


// Gera um nome �nico para a imagem
$imagem_nome = md5(uniqid(time())) . "." . $ext[1];

// Caminho de onde a imagem ficar�
$imagem_dir = "imgs/" . $imagem_nome;

// Faz o upload da imagem
move_uploaded_file($arquivo["tmp_name"], $imagem_dir);

    if ($_GET['avatar']) {
        $player = check_user($secret_key, $db);
        $db->execute("update `players` set `avatar`=? where `id`=?", array($imagem_dir, $player->id));
        header("Location: avatar.php?success=true");
        exit;
    } elseif ($_GET['pay']) {
        header("Location: payment.php?submit=true&manda=true&img=" . $imagem_dir . "&conta=" . $_GET['conta'] . "&id=" . $_GET['id'] . "&comprovante=true&submit=true");
        exit;
    }

$msg .= "<span style=\"color: white; border: solid 1px; background: green;\">Sua imagem foi enviada com sucesso!</span><br/>";
$msg .= "<b>Endere�o:</b> <font size=\"1\">http://ocrpg.com/imgs/".$imagem_nome." <a href=\"http://ocrpg.com/imgs/".$imagem_nome."\" target=\"blank\"><b>Visualizar</b></a><font>";
    
}
}
}

    if ($_GET['avatar']) {
        header("Location: avatar.php?msg=error");
        exit;
    } elseif ($_GET['pay']) {
        header("Location: payment.php?submit=true&manda=true&send=false&conta=" . $_GET['conta'] . "&id=" . $_GET['id'] . "&comprovante=true&submit=true");
        exit;
    } else {
        	$player = check_user($secret_key, $db);
        include("templates/private_header.php");
    
    echo "<fieldset>";
    echo "<legend><b>Enviar Imagens</b></legend>";
        echo "<form action=\"sendfiles.php\" method=\"post\" enctype=\"multipart/form-data\">";
        echo "<input type=\"file\" name=\"foto\" size=\"30\"><input type=\"submit\" name=\"upload\" value=\"Enviar\">";
        echo "</form>";
        echo "</fieldset>";
        echo "<font size=\"1\">Aqui voc� pode enviar imagens para usar como avatar, no f�rum, no perfil, etc.</font><br/><br/>";

    echo $msg;
	include("templates/private_footer.php");
    }
?>