<?php require_once('inc/init.php'); ?>


<?php
if(!internauteConnecte())
{
    //header() est un fonction predefinie qui permete de renvoyer verss une page
    header('location:connexion.php');
    exit();
}

$error='';

if($_POST)
{

    
    $photo_bdd = '';
    
    if($_FILES['photo_membre']['name'])
    {
      $nom_photo = $_POST['pseudo'] ."_". uniqid(). $_FILES['photo_membre']['name'];
      $photo_bdd .= URL . "photo_membre/$nom_photo";
      $photo_dossier = RACINE_SITE . "photo_membre/$nom_photo";
      copy($_FILES['photo_membre']['tmp_name'],$photo_dossier);
      $_SESSION['membre']['photo_membre'] = $photo_bdd;
    }
    elseif($_POST['photo_actuelle'])
    {
      $photo_bdd .= $_POST['photo_actuelle'];
    }
    
    if($_FILES['photo_membre']['size'] > 10000000 )
    {
      $error .= "<p>Votre photo doit faire moins de 10mb !</p>";
    }
    
    if(!($_FILES["photo_membre"]["type"] == "image/png") 
      && !($_FILES["photo_membre"]["type"] == "image/jpeg") // La convention est "jpeg" pour les fichiers JPEG
      && !($_FILES["photo_membre"]["type"] == "image/jpg")) 
    {
      $error .= "You may only upload png, jpeg, or gif.";
    } 
    

    if(!$error)
    {
      if(isset($_GET['action']) && $_GET['action'] === "modification")
      {
        $pdo->exec("UPDATE membre SET prenom='$_POST[prenom]', nom='$_POST[nom]', city='$_POST[ville]', country='$_POST[pays]', photo_membre='$photo_bdd' WHERE pseudo = '$_GET[pseudo]' ");
      };
    }
        
        header('location: profil.php');
        exit();

        
}

    if(isset($_GET['action']) && $_GET['action'] === "modification")
    {
        $resultat = $pdo->query("SELECT * FROM membre WHERE pseudo = '$_GET[pseudo]'");
        $profil_modification = $resultat->fetch(PDO::FETCH_ASSOC);      
    }
$ville = (isset($profil_modification['city'])) ? $profil_modification['city']:"";
$pays = (isset($profil_modification['country'])) ? $profil_modification['country']:"";
$photo = (isset($profil_modification['photo_membre'])) ? $profil_modification['photo_membre']:"";
$prenom = (isset($profil_modification['prenom'])) ? $profil_modification['prenom']:"";
$nom = (isset($profil_modification['nom'])) ? $profil_modification['nom']:"";

$resultat = $pdo->query("SELECT * FROM membre WHERE id_membre =" .$_SESSION['membre']['id_membre']." ");
$membre = $resultat->fetch(PDO::FETCH_ASSOC);

$resultatPosts = $pdo->query("SELECT * FROM forum WHERE id_membre =". $_SESSION["membre"]["id_membre"]."");
$posts = $resultatPosts->fetchAll(PDO::FETCH_ASSOC);
// debug($posts,2);


?>


<?php require_once('inc/head.php'); ?>

<section class="grid grid-cols-1 gap-4 pb-12 md:grid-cols-2 justify-center">

    <div class="">
        <p class="text-sm font-medium leading-6 text-gray-600">Pseudo : <?= $_SESSION['membre']['pseudo'] ?></p>
        <p class="text-sm font-medium leading-6 text-gray-600">Email : <?= $membre['email'] ?></p>
        <p class="text-sm font-medium leading-6 text-gray-600">Nom et Prenom : <?= $membre['nom']." ". $membre['prenom'] ?></p>
        <p class="text-sm font-medium leading-6 text-gray-600">Date Anniversaire : <?= $membre['birthday'] ?></p>
        <p class="text-sm font-medium leading-6 text-gray-600">Pays : <?= $membre['country'] ?></p>
        <p class="text-sm font-medium leading-6 text-gray-600">Ville : <?= $membre['city'] ?></p>
    </div>
    <div class=" flex items-center gap-x-8">
                <div class="h-56">
                <?php if($_SESSION['membre']['photo_membre']): ?>
                     <img
                  src="<?= $_SESSION['membre']['photo_membre'] ?>"
                  alt="<?= $_SESSION['membre']['pseudo'] ?> photo"
                  class="h-full max-w-full flex-none rounded-lg object-cover"
                /> 
                <?php else: ?>
                  <img
                  src="./inc/LOGO.png"
                  alt="<?= $_SESSION['membre']['pseudo'] ?> photo"
                  class="h-full max-w-full flex-none rounded-lg object-cover"
                /> 
                <?php endif; ?>
                </div>
              

                <img src="./image/icons8-flèche-50.png" alt="Arrow icon" class="">

                <div class="flex gap-x-2.5 text-white text-sm">
                <?php if($_SESSION['membre']['photo_membre']): ?>
                        <img
                        src="<?= $_SESSION['membre']['photo_membre'] ?>"
                        alt="<?= $_SESSION['membre']['pseudo'] ?> photo"
                        class="h-12 w-12 flex-none rounded-full bg-white/10 object-cover"
                        />
                        <?php else: ?>
                          <img
                          src="./inc/LOGO.png"
                        alt="<?= $_SESSION['membre']['pseudo'] ?> photo"
                        class="h-12 w-12 flex-none rounded-full bg-white/10 object-cover"
                        />
                        <?php endif; ?>
                </div>
    </div>
    <div class="mt-4">
        <p class="text-sm font-medium leading-6 text-gray-600">Modifier vos informations personnelles !</p>
        <a href="modif_perso.php?action=modification&pseudo=<?= $_SESSION['membre']['pseudo'] ?>"><button class="rounded-lg bg-[#C8B6FF] px-4 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:scale-95">Modif.</button></a>
    </div>
    <div class="mt-4">
        <p class="text-sm font-medium leading-6 text-gray-600">Modifier les DONNEES importantes !</p>
        <a href="<?= URL ?>modif_important.php?action=modification&pseudo=<?= $_SESSION['membre']['pseudo'] ?>"><button class="rounded-lg bg-[#C8B6FF] px-4 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:scale-95">Modif.</button></a>   
    </div>
</section>

    <hr>
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <?php if(!empty($posts)): ?>
          <?php foreach($posts as $post): ?>
          <?php $resultat = $pdo->query("SELECT * FROM membre WHERE id_membre = $post[id_membre]");
            $membre = $resultat->fetchAll(PDO::FETCH_ASSOC);?>
      <div class="grid gap-4 h-52">
          <!-- ------------------------------- Card #1 ------------------------------- -->
        <div class="relative overflow-hidden rounded-lg">
              <?php foreach($membre as $key => $value): ?>
            <!-- ------------------------------- Overlay ------------------------------- -->
            <div class="absolute inset-0 bg-gradient-to-t from-gray-500 via-gray-900/40"></div>
            <div class="absolute inset-0 rounded-lg ring-1 ring-inset ring-gray-900/10"></div>

            <!-- -------------------------------- Badge -------------------------------- -->
            <div class="absolute right-1.5 top-1.5 z-20">
              <span
                class="inline-flex items-center rounded-md bg-[#FFD6FF] px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10"
                ><?= $post['tag'] ?></span>
            </div>
                <?php if($post['mature_content'] == 'Yes'): ?>
            <img
              class="h-auto w-full rounded-lg blur-sm"
              src="<?= $post['photo_forum'] ?>"
              alt="<?= $value['pseudo'] ?> post"
            > 
            <?php else: ?>
            <img
              class="h-auto w-full rounded-lg"
              src="<?= $post['photo_forum'] ?>"
              alt="<?= $value['pseudo'] ?> post"
            />
            <?php endif; ?>
            <div class="absolute w-full left-3 bottom-3">
              <!-- -------------------------------- Title -------------------------------- -->
              <h3 class="mb-2 text-md font-semibold leading-6 text-white">
                <a href="<?= URL ?>post_share.php?post_id=<?= $post['id_reference']?>">
                  <?= $post['titre'] ?>
                </a>
              </h3>

              <!-- ------------------------------- Avatar -------------------------------- -->
              <div class="-ml-4 flex items-center gap-x-4">
                <svg viewBox="0 0 2 2" class="-ml-0.5 h-0.5 w-0.5 flex-none fill-white/50">
                  <circle cx="1" cy="1" r="1" />
                </svg>
                <div class="flex gap-x-2.5 text-white text-sm">
                    <a href="<?= URL ?>profil_share.php?id_membre=<?= $value['id_membre'] ?>">
                        <img
                        src="<?= $value['photo_membre'] ?>"
                        alt=""
                        class="h-6 w-6 flex-none rounded-full bg-white/10 object-cover"
                        />
                    </a>
                  <a href="<?= URL ?>profil_share.php?id_membre=<?= $value['id_membre'] ?>"><?= $value['pseudo'] ?></a>
                </div>
              </div>
            </div>
        </div> 
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
      <div class="flex flex-cols gap-4 flex-wrap justify-center col-start-1 col-end-3 md:col-end-5">
            <p class="border-red-500 border-2 rounded-lg p-4">Vous n'avez pas encore de post, vous pouvez des maintenant en creer un avec le button en-dessous !</p>
            <a href="<?= URL ?>creation_post.php" class="flex w-1/2 justify-center rounded-md bg-[#C8B6FF] px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:scale-95 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C8B6FF]"><Button>Creer un post</Button></a>
    </div>
    <?php endif; ?>
      </section>

<?php require_once('inc/foot.php'); ?>

