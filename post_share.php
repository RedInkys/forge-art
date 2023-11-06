<?php require_once("inc/init.php");?>
<?php

$resultat = $pdo->query("SELECT * FROM forum WHERE id_reference =".$_GET["post_id"]."");
$post = $resultat->fetch(PDO::FETCH_ASSOC);

$resultat2 = $pdo->query("SELECT * FROM membre WHERE id_membre = $post[id_membre]");
$membre = $resultat2->fetch(PDO::FETCH_ASSOC);
// Recupere le createur grace a la cle etrangere dedans la table 
if(isset($_SESSION) && $_SESSION)
{

    $format = 'Y-m-d';
    $birthday = $_SESSION['membre']["birthday"];
    
    $dateDeNaissance = DateTime::createFromFormat($format, $birthday,  new DateTimeZone('Europe/Paris'));
    
    if($dateDeNaissance !== false) 
    {
        $today = new DateTime(null, new DateTimeZone('Europe/Paris')); //Pour avoir le fuseau horaire de Paris
        $age = $dateDeNaissance->diff($today)->y;
    }
}

debug($post['mature_content'],1);
?>




<?php require_once("inc/head.php");?>
<section class="grid grid-cols-1 gap-8 ">
    <div class="flex justify-center items-center h-[32rem]">
        <?php if(!internauteConnecte() ): ?>
            <img src="<?= $post["photo_forum"] ?>" alt="<?= $membre['pseudo'] ?>" class="h-full object-cover max-w-full rounded-lg border-solid border-2 border-black blur-sm">
            <p class="font-medium text-gray-400">Vous n'etes pas <a href="<?= URL ?>connexion.php" class="bg-[#FFD6FF] rounded-md p-1 hover:text-white hover:bg-[#C8B6FF]">connecter</a>.</p>
        <?php elseif($post['mature_content'] == "Yes" && $age < 18): ?>
            <img src="<?= $post["photo_forum"] ?>" alt="<?= $membre['pseudo'] ?>" class="h-full object-cover max-w-full rounded-lg border-solid border-2 border-black blur-sm">
            <p class="font-medium text-gray-400">Vous n'avez pas l'age requis pour voir cette image !</p>
        <?php else: ?>
            <img src="<?= $post["photo_forum"] ?>" alt="<?= $membre['pseudo'] ?>" class="h-full object-cover max-w-full rounded-lg border-solid border-2 border-black">
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-2 w-auto rounded-lg border-solid border-2 border-[#C8B6FF] bg-[#FFD6FF] pt-2">
        <div class="ml-4">
            <a href="<?= URL ?>profil_share.php?id_membre=<?= $membre['id_membre'] ?>" class="flex flex-cols gap-4 items-center">
                <?php if($membre['photo_membre']): ?>
                    <img src="<?= $membre["photo_membre"] ?>" alt="<?= $membre['pseudo'] ?> post" class="h-8 w-8 rounded-full flex-none object-cover">
                <?php else: ?>
                    <img src="./image/LOGO.png" alt="<?= $membre['pseudo'] ?> post" class="h-8 w-8 rounded-full flex-none object-cover">
                <?php endif; ?>

                <a href="<?= URL ?>profil_share.php?id_membre=<?= $membre['id_membre'] ?>" class="font-medium text-gray-400"><?= $membre['pseudo'] ?></a>
            </a>
        </div>
            <p class="underline underline-offset-2 mr-4 items-center flex"><?= $post["titre"] ?></p>
            <p class="col-start-1 col-end-3 py-4 ml-4 mr-4">Description : <?= $post["description"] ?></p>
            <p class="ml-4 mb-2">Date de création : <?= $post['date_creation'] ?></p>
    </div>


</section>





<?php require_once("inc/foot.php");?>
