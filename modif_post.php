<?php require_once("inc/init.php");?>
<?php  

if(isset($_GET['pseudo']) && $_GET['pseudo'] !== $_SESSION['membre']['pseudo'])
{
    header('location: accueil.php');
    exit();
}
$resultat = $pdo->query("SELECT * FROM forum WHERE id_reference=$_GET[id_post]");
$post = $resultat->fetch(PDO::FETCH_ASSOC);

$error = "";

if($_POST)
{


    if (strlen($_POST['titre']) < 3 || strlen($_POST['titre']) > 30) {
        $error .= "<p>Votre titre doit être compris entre 3 et 30 caractères</p>";
    }

    if (!preg_match('#^[a-zA-Z0-9 ._-]+$#', $_POST['titre'])) {
        $error .= "<p>Votre titre doit contenir uniquement des lettres, des chiffres et des caractères spéciaux autorisés telle que (le point, le tiret du 8 et le tiret du 6) !</p>";
    }

    if (strlen($_POST['description']) > 162) {
        $error .= "<p>Votre description doit être INFÉRIEURE à 162 caractères !</p>";
    }

    if(!$error)
    {
        if (isset($_GET['action']) && $_GET['action'] === 'modification') 
        {
            $stmt = $pdo->exec("UPDATE forum SET titre='$_POST[titre]', description='$_POST[description]' WHERE id_reference='$_GET[id_post]' ");
            header("location:".URL."your_art.php");
        }
    }
    
}


?>








<?php require_once("inc/head.php");?>

<section class="flex min-h-full justify-center">
        <div class="flex flex-1 flex-col px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
          <div class="mx-auto w-full max-w-sm lg:w-96">
            <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">
              Modifier la publication
            </h2>        
            <p>Vous pouvez modifier le titre et la description</p>
            <?php if($error): ?>
            <p><?= $error ?></p>
            <?php endif; ?>

            <div class="mt-10">
              <div>
                <form action="" method="post" class="space-y-6" enctype="multipart/form-data">
                  <!-- -------------------------------- Title -------------------------------- -->
                  <div>
                    <label for="titre" class="block text-sm font-medium leading-6 text-gray-900"
                      >Titre</label
                    >
                    <div class="mt-2">
                      <input
                        id="titre"
                        name="titre"
                        type="text"
                        required
                        value="<?= $post['titre'] ?>"
                        class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                      />
                    </div>
                  </div>

                  <!-- -------------------------------- Tags --------------------------------- -->
<!-- 
                  <div >
                    <label for="tag" class="block text-sm font-medium leading-6 text-gray-900"
                      >Tags</label
                    >
                    <div class="mt-2">
                      <select
                        id="tag"
                        name="tag"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                        <option <?php if($post['tag']=="3d") echo 'selected';?>>3D</option>
                        <option <?php if($post['tag']=="animaux") echo 'selected';?>>Animaux</option>
                        <option <?php if($post['tag']=="croquis") echo 'selected';?>>Croquis</option>
                        <option <?php if($post['tag']=="dessin") echo 'selected';?>>Dessin</option>
                        <option <?php if($post['tag']=="fan-art") echo 'selected';?>>Fan-art</option>
                        <option <?php if($post['tag']=="fantasy") echo 'selected';?>>Fantasy</option>
                        <option <?php if($post['tag']=="paysage") echo 'selected';?>>Paysage</option>
                        <option <?php if($post['tag']=="photo") echo 'selected';?>>Photo</option>
                        <option <?php if($post['tag']=="realiste") echo 'selected';?>>Realiste</option>
                        <option <?php if($post['tag']=="science-fiction") echo 'selected';?>>Science-fiction</option>
                        <option <?php if($post['tag']=="tuto") echo 'selected';?>>Tuto</option>
                      </select>
                    </div>
                  </div> -->

                  <!-- ----------------------------- description ----------------------------- -->
                  <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900"
                      >Description</label
                    >
                    <div class="mt-2">
                      <textarea
                        id="about"
                        name="description"
                        rows="3"
                        maxlength="162"
                        class="block w-full min-h-[5rem] max-h-[10rem] rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                      ><?= $post['description'] ?></textarea>
                    </div>
                    <p class="flex justify-center mt-3 text-sm leading-6 text-gray-600">
                      Ajoute une petite description a ton post.
                    </p>
                  </div>
                  <div>
                    <button
                      type="submit"
                      class="flex w-full justify-center rounded-md bg-[#C8B6FF] px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:scale-95 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C8B6FF]"
                    >
                      Mettre a jour
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>














<?php require_once("inc/foot.php");?>
