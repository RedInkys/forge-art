<?php require_once("../inc/init.php");?>

<?php 

if(internauteConnecte() && $_SESSION['membre']['role'] !== 'admin'){
    header('location:../accueil.php');
}


if(isset($_GET['action'])&& $_GET['action'] == "modification")
{
    $resultat = $pdo->query("SELECT * FROM membre WHERE pseudo = '$_GET[pseudo]'");
    $profil = $resultat->fetch(PDO::FETCH_ASSOC);
    
    if($_POST)
    {
        $pdo->exec("UPDATE membre SET role = '$_POST[role]' WHERE pseudo='$_GET[pseudo]'");  
        header("location:".URL."admin/allInfo.php");
    }
}

$photo = (isset($profil_modification['photo_membre'])) ? $profil_modification['photo_membre']:"";
function hashe($item)
{
    return password_hash($item, PASSWORD_DEFAULT);
}

?>










<?php include_once("../inc/head.php") ?>





<form action="" method="post" class="form-inscription form" enctype="multipart/form-data"> <!-- enctype sert a recuperer les photos -->

<label for="role">Role :</label>
<select name="role" id="role">
    <option <?php if($profil['role'] == 'Creator') echo 'Selected'; ?>>Creator</option>
    <option <?php if($profil['role'] == 'admin') echo 'Selected'; ?>>admin</option>
</select>
<input type="hidden" name="role_actuelle" class="input-form" value="<?= $profil['role'] ?>">


<input class="p-2 bg-[#C8B6FF] text-white rounded-md hover:bg-[#FFD6FF]" type="submit" value="Envoyer">
</form>













<?php include_once("../inc/foot.php") ?>
