<?php require_once("inc/init.php");?>

<?php 

if(!internauteConnecte())
{
    header('location:connexion.php');
    exit();
}

// debug($_SESSION, 2);

$error='';

// if($_POST)
// {
//     // foreach($_POST as $key => $value)
//     // {
//     // echo "$key : $value <br>";
//     // }
//     $resultat = $pdo->query("SELECT forum.* , membre.* FROM forum , membre");
//     $forum = $resultat->fetch(PDO::FETCH_ASSOC);
//     // debug($forum,2);
//     $photo_bdd = '';
//         if($_FILES['photo_membre_post']['name'])
//         {
//             $nom_photo = $_SESSION['membre']['pseudo'] ."_". uniqid(). $_FILES['photo_membre_post']['name']."_".$_POST['id_post'];
//             $photo_bdd .= URL . "photo_post/$nom_photo";
//             $photo_dossier = RACINE_SITE . "photo_post/$nom_photo";
//             copy($_FILES['photo_membre_post']['tmp_name'],$photo_dossier);
//         }

//         if(strlen($_POST['titre']) < 3 || strlen($_POST['titre']) > 30)
//         {
//             $error .= "<p>Votre titre doit etre contenu entre 3 et 30 Caracteres</p>";
//         }

//         if(!preg_match('#^[a-zA-Z0-9 ._-]+$#', $_POST['titre']))
//         {
//             $error .= "<p>Votre titre doit contenir que des LETTRES et des NOMBRES/CHIFFRES !</p>";
//         }

//         if(strlen($_POST['description']) > 300)
//         {
//             $error .="<p>Votre description doit etre INFERIEUR a 300 caracteres !</p>";
//         }
        
//         $id_membre = $_SESSION["membre"]['id_membre'];
//         if(!$error)
//         {
//             $pdo->exec("INSERT INTO forum (titre , description, tag, photo_forum, mature_content, date_creation, id_membre)
//              VALUES ('$_POST[titre]','$_POST[description]','$_POST[tag]', '$photo_bdd', '$_POST[mature_content]' , NOW() , '$id_membre' ) ");

//             header('location:'.URL.'your_art.php');
//             exit();
//         } 
        
//         // $tag = $pdo->query("SELECT tag FROM forum");
//         // $mature = $pdo->query("SELECT mature_content FROM forum");
        
//         // debug($pseudo);
//     }
    

//     $resultat = $pdo->query("SELECT * FROM forum");
//     $forums = $resultat->fetchAll(PDO::FETCH_ASSOC);
// // debug($post,2);

// ?>
<?php
if ($_POST) 
{
    $resultat = $pdo->query("SELECT forum.*, membre.* FROM forum, membre");
    $forum = $resultat->fetch(PDO::FETCH_ASSOC);
    $error = "";
    $photo_bdd = '';

    if ($_FILES['photo_membre_post']['name']) {
        $nom_photo = $_SESSION['membre']['pseudo'] .
         "_" . uniqid() . $_FILES['photo_membre_post']['name'] . "_" . $_POST['id_post'];
        $photo_bdd .= URL . "photo_post/$nom_photo";
        $photo_dossier = RACINE_SITE . "photo_post/$nom_photo";
        copy($_FILES['photo_membre_post']['tmp_name'], $photo_dossier);
    }

    if($_FILES['photo_membre_post']['size'] > 10000000 )
    {
      $error .= "<p>Votre photo doit faire moins de 10mb !</p>";
    }
        
        if(!empty($photo_bdd))
        {
          if(!($_FILES["photo_membre_post"]["type"] == "image/png") 
          && !($_FILES["photo_membre_post"]["type"] == "image/jpeg") // La convention est "jpeg" pour les fichiers JPEG
          && !($_FILES["photo_membre_post"]["type"] == "image/jpg")) 
          {
            $error .= "Les formats autorisé sont JPEG, JPG ou PNG";
          }
        }

    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $tag = htmlspecialchars($_POST['tag']);
    $mature_content = htmlspecialchars($_POST['mature_content']);
    $id_membre = htmlspecialchars($_SESSION["membre"]['id_membre']);

    if (strlen($titre) < 3 || strlen($titre) > 30) {
        $error .= "<p>Votre titre doit être compris entre 3 et 30 caractères</p>";
    }

    if (!preg_match('#^[a-zA-Z0-9 ._-]+$#', $titre)) {
        $error .= "<p>Votre titre doit contenir uniquement des lettres, des chiffres et des caractères spéciaux autorisés telle que (le point, le tiret du 8 et le tiret du 6) !</p>";
    }

    if (strlen($description) > 162) {
        $error .= "<p>Votre description doit être INFÉRIEURE à 162 caractères !</p>";
    }

    if (!$error) {
        $stmt = $pdo->prepare("INSERT INTO forum 
        (titre, description, tag, photo_forum, mature_content, date_creation, id_membre)
         VALUES (:titre, :description, :tag, :photo_bdd, :mature_content, NOW(), :id_membre)");

        $stmt->bindParam(":titre", $titre);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":tag", $tag);
        $stmt->bindParam(":photo_bdd", $photo_bdd);
        $stmt->bindParam(":mature_content", $mature_content);
        $stmt->bindParam(":id_membre", $id_membre);

        $stmt->execute();

        header('location:' . URL . 'your_art.php');
        exit();
    }
}

$resultat = $pdo->query("SELECT * FROM forum");
$forums = $resultat->fetchAll(PDO::FETCH_ASSOC);

?>


<?php require_once("inc/head.php");?>

<section class="flex min-h-full justify-center">
        <div
          class="flex flex-1 flex-col px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24"
        >
          <div class="mx-auto w-full max-w-sm lg:w-96">
            <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">
              Creer un post
            </h2>
            <div class="mt-10">
              <?php if($error): ?>
                <div class="bg-red-500 rounded-lg flex items-center justify-center p-2">
                  <?= $error ?>
                </div>
              <?php endif; ?>
              <div>
                <form action="" method="post" class="space-y-6" enctype="multipart/form-data">
                <input type="hidden" name="id_post" >
                  <!-- -------------------------------- Image -------------------------------- -->
                  <div>
                    <label
                      for="photo_post"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Image</label
                    >
                    <div
                      class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
                    >
                      <div class="text-center">
                        <svg
                          class="mx-auto h-12 w-12 text-gray-300"
                          viewBox="0 0 24 24"
                          fill="currentColor"
                          aria-hidden="true"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                            clip-rule="evenodd"
                          />
                        </svg>
                        <div class="mt-4 flex text-sm leading-6 text-gray-600">
                          <label
                            for="file-upload"
                            class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500"
                          >
                            <span>Upload a file</span>
                            <input
                              id="file-upload"
                              name="photo_membre_post"
                              type="file"
                              class="sr-only"
                              required
                            />
                          </label>
                          <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                      </div>
                        <input type="hidden" name="photo_actuelle" value="">
                    </div>
                  </div>
                  <!-- -------------------------------- Title -------------------------------- -->
                  <div>
                    <label for="titre" class="block text-sm font-medium leading-6 text-gray-900"
                      >Titre <span class="font-normal text-gray-300"> * caractères spéciaux autorisés telle que (le point, le tiret du 8 et le tiret du 6)</span></label
                    >
                    <div class="mt-2">
                      <input
                        id="titre"
                        name="titre"
                        type="text"
                        required
                        class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                      />
                    </div>
                  </div>

                  <!-- -------------------------------- Tags --------------------------------- -->

                  <div >
                    <label for="tag" class="block text-sm font-medium leading-6 text-gray-900"
                      >Tags</label
                    >
                    <div class="mt-2">
                      <select
                        id="tag"
                        name="tag"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                        <option >3D</option>
                        <option >Animaux</option>
                        <option >Croquis</option>
                        <option >Dessin</option>
                        <option >Fan-art</option>
                        <option >Fantasy</option>
                        <option >Paysage</option>
                        <option >Photo</option>
                        <option >Realiste</option>
                        <option >Science-fiction</option>
                        <option >Tuto</option>
                      </select>
                    </div>
                  </div>

                  <!-- ----------------------------- description ----------------------------- -->
                  <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900"
                      >Description<span class="font-normal text-gray-300"> * maximum 162 caracteres</span></label
                    >
                    <div class="mt-2">
                      <textarea
                        id="about"
                        name="description"
                        rows="3"
                        maxlength="162"
                        class="block w-full min-h-[5rem] max-h-[10rem] rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                      ></textarea>
                    </div>
                    <p class="flex justify-center mt-3 text-sm leading-6 text-gray-600">
                      Ajoute une petite description a ton post.
                    </p>
                  </div>

                  <!-- -------------------------------- Check -------------------------------- -->
                  <div >
                    <label for="mature_content" class="block text-sm font-medium leading-6 text-gray-900"
                      >Mature content</label
                    >
                    <div class="mt-2">
                      <select
                        id="mature_content"
                        name="mature_content"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                      >
                        <option >Yes</option>
                        <option >No</option>
                      </select>
                    </div>
                    <p class="flex justify-center mt-3 text-sm leading-6 text-gray-600">
                      Ajoute du floue au post.
                    </p>
                  </div>

                  <div>
                    <button
                      type="submit"
                      class="flex w-full justify-center rounded-md bg-[#C8B6FF] px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:scale-95 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C8B6FF]"
                    >
                      Creer ton post
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>



<?php require_once("inc/foot.php");?>
