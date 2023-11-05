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

      if($_FILES['photo_membre']['size'] > 10000000 )
      {
        $error .= "<p>Votre photo doit faire moins de 10mb !</p>";
      }
      
      if(!($_FILES["photo_membre"]["type"] == "image/png") 
        && !($_FILES["photo_membre"]["type"] == "image/jpeg") // La convention est "jpeg" pour les fichiers JPEG
        && !($_FILES["photo_membre"]["type"] == "image/jpg")) 
      {
        $error .= "Les formats autorisé sont JPEG, JPG ou PNG";
      } 
      if(!$error){
        $nom_photo = $_GET['pseudo'] ."_". uniqid(). $_FILES['photo_membre']['name'];
        $photo_bdd .= URL . "photo_membre/$nom_photo";
        $photo_dossier = RACINE_SITE . "photo_membre/$nom_photo";
        copy($_FILES['photo_membre']['tmp_name'],$photo_dossier);
        $_SESSION['membre']['photo_membre'] = $photo_bdd;
      }
      
    }elseif($_POST['photo_actuelle'])
    {
      $photo_bdd .= $_POST['photo_actuelle'];
    }
    
    if(!$error)
    {
      if(isset($_GET['action']) && $_GET['action'] === "modification")
      {
        $pdo->exec("UPDATE membre SET prenom='$_POST[prenom]', nom='$_POST[nom]', city='$_POST[ville]', country='$_POST[pays]', photo_membre='$photo_bdd' WHERE pseudo = '$_GET[pseudo]' ");
      };
      header('location: profil.php');
      exit();
    }
            
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


?>


<?php require_once('inc/head.php'); ?>


<?php if(isset($_GET['action']) && $_GET['action'] == 'modification'): ?>
<section class="divide-y divide-white/5 mt-10">
        <div
          class="grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8"
        >
          <div>
            <h2 class="text-base font-semibold leading-7 text-gray-600">informations personnelles</h2>
            <p class="mt-1 text-sm leading-6 text-gray-400">
              Modifier votre Photo, Prenom, Nom, Pays, Ville.
            </p>
            <?php if($error): ?>
              <div class="bg-red-500 rounded-lg flex flex-wrap items-center justify-center p-2">
                <?= $error ?>
              </div>
            <?php endif; ?>
          </div>

          <form class="md:col-span-2" method="post" enctype="multipart/form-data">
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:max-w-xl sm:grid-cols-6">
              <div class="col-span-full flex items-center gap-x-8">
              <div>
                    <label
                      for="cover-photo"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Photo de profil</label
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
                              name="photo_membre"
                              type="file"
                              class="sr-only"
                            />
                          </label>
                          <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs leading-5 text-gray-600">PNG, JPG, JPEG up to 10MB</p>
                      </div>
                      <input type="hidden" name="photo_actuelle" value="<?= $photo ?>">
                    </div>
                  </div>
            </div>
        </div>

              <div class="sm:col-span-3">
                <label for="prenom" class="block text-sm font-medium leading-6 text-gray-600"
                  >Prenom</label
                >
                <div class="mt-2">
                  <input
                    type="text"
                    name="prenom"
                    id="prenom"
                    value="<?= $prenom ?>"
                    class="block w-full rounded-md border-2 border-gray-400 bg-white/5 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                  />
                </div>
                <input type="hidden" name="prenom_actuelle" value="<?= $prenom ?>">
              </div>

              <div class="sm:col-span-3">
                <label for="nom" class="block text-sm font-medium leading-6 text-gray-600"
                  >Nom</label
                >
                <div class="mt-2">
                  <input
                    type="text"
                    name="nom"
                    id="nom"
                    value="<?= $nom ?>"
                    class="block w-full rounded-md border-2 border-gray-400 bg-white/5 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                  />
                </div>
                <input type="hidden" name="nom_actuelle" class="input-form" value="<?= $nom ?>">
              </div>

              <div class="col-span-full">
                <label for="pays" class="block text-sm font-medium leading-6 text-gray-600"
                  >Pays</label
                >
                <div class="mt-2">
                  <input
                  type="text" name="pays"
                    id="pays"
                    value="<?= $pays ?>"
                    class="block w-full rounded-md border-2 border-gray-400 bg-white/5 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                  />
                </div>
                <input type="hidden" name="pays_actuelle" class="input-form" value="<?= $pays ?>">
              </div>
              <div class="col-span-full">
                <label for="ville" class="block text-sm font-medium leading-6 text-gray-600"
                  >Ville</label
                >
                <div class="mt-2">
                  <input
                    type="text"
                    name="ville"
                    id="ville"
                    value="<?= $ville ?>"
                    class="block w-full rounded-md border-2 border-gray-400 bg-white/5 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                  />
                </div>
                <input type="hidden" name="ville_actuelle" class="input-form" value="<?= $ville ?>">
              </div>


            <div class="mt-8 flex">
              <button
                type="submit"
                value="Envoyer"
                class="rounded-md bg-[#C8B6FF] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#C8B6FF]/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C8B6FF]"
              >
                Sauvegarder
              </button>
            </div>
          </form>
        </div>
    </section>
    <?php endif; ?>







<?php require_once('inc/foot.php'); ?>
