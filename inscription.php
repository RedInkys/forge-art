<?php require_once('inc/init.php'); ?>
<?php

$today = date("Y-m-d");

$error='';

if(internauteConnecte())
{
    header('location:profil.php');
    exit();
}

if ($_POST) {
    $resultat = $pdo->query("SELECT * FROM membre ");
    $membre = $resultat->fetchAll(PDO::FETCH_ASSOC);



    if (strlen($_POST['pseudo']) < 3 || strlen($_POST['pseudo']) > 20) {
        $error .= '<p>Votre pseudo doit être entre 3 et 20 caractères maximum !</p>';
    }

    if (!preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo'])) {
        $error .= '<p>Votre pseudo doit contenir uniquement des caractères alphanumériques !';
    }

    if (!preg_match("#^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{6,200})$#", $_POST['mdp'])) {
        $error .= "<p>Votre Mot De Passe doit contenir au moins UNE majuscule, UN chiffre et UN caractère spécial</p>";
    }

    $emailVerif = $pdo->prepare("SELECT * FROM membre WHERE email = :email");
    $emailVerif->bindParam(':email', $_POST['email']);
    $emailVerif->execute();

    if ($emailVerif->rowCount() == 1) {
        $error .= "<p>Un compte existe déjà avec cette adresse mail !</p>";
    }

    $pseudoVerif = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $pseudoVerif->bindParam(':pseudo', $_POST['pseudo']);
    $pseudoVerif->execute();

    if ($pseudoVerif->rowCount() == 1) {
        $error .= "<p>Un compte existe déjà avec ce pseudo !</p>";
    }

    if (empty($_POST['birthday']))
    {
        $error .="<p>Vous devez renseigner votre Date d'annniversaire</p>";
    } 

        $photo_bdd = '';
        if ($_FILES['photo_membre']['name']) {
            $nom_photo = $_POST['pseudo'] . "_" . uniqid() . $_FILES['photo_membre']['name'];
            $photo_bdd .= URL . "photo_membre/$nom_photo";
            $photo_dossier = RACINE_SITE . "photo_membre/$nom_photo";
            copy($_FILES['photo_membre']['tmp_name'], $photo_dossier);
        }

        if($_FILES['photo_membre']['size'] > 10000000 )
        {
          $error .= "<p>Votre photo doit faire moins de 10mb !</p>";
        }
        
        if(!empty($photo_bdd))
        {
          if(!($_FILES["photo_membre"]["type"] == "image/png") 
          && !($_FILES["photo_membre"]["type"] == "image/jpeg") // La convention est "jpeg" pour les fichiers JPEG
          && !($_FILES["photo_membre"]["type"] == "image/jpg")) 
          {
            $error .= "Les formats autorisé sont JPEG, JPG ou PNG";
          }
        }

        $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

        $pseudo = htmlspecialchars($_POST['pseudo']);
        $password = htmlspecialchars($_POST['mdp']);
        $email = htmlspecialchars($_POST['email']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $nom = htmlspecialchars($_POST['nom']);
        $country = htmlspecialchars($_POST['pays']);
        $city = htmlspecialchars($_POST['ville']);


        if (!$error) {
            $stmt = $pdo->prepare("INSERT INTO membre 
            (pseudo, password, email, prenom, nom, birthday, country, city, date_enregistrement, photo_membre, role) 
            VALUES (:pseudo, :password, :email, :prenom, :nom, :birthday, :country, :city, NOW(),:photo_membre, :role)");

            $stmt->bindParam(":pseudo", $pseudo);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":prenom", $prenom);
            $stmt->bindParam(":nom", $nom);
            $stmt->bindParam(":birthday", $_POST['birthday']);
            $stmt->bindParam(":country", $country);
            $stmt->bindParam(":city", $city);
            $stmt->bindParam(":photo_membre", $photo_bdd);
            $stmt->bindParam(":role", $_POST['role']);
            $stmt->execute();

            header("location:connexion.php");
        }
    
}
    
?>



<?php require_once('inc/head.php'); ?>

<section class="flex min-h-full justify-center">
  <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
    <div class="mx-auto w-full max-w-4xl lg:w-96">
      <div>
        <img
        class="h-12 w-auto"
        src="./image/LOGO.png"
        alt="Forge-art logo"
        />
        <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">
          Create an Account
        </h2>
        <p class="mt-2 text-sm leading-6 text-gray-500">
          Vous possédez déjà un compte ?
          <a href="<?= URL ?>connexion.php" class="font-semibold text-[#C8B6FF] hover:text-[#FFD6FF]"
          >Connectez vous</a
          >
        </p>
      </div>
      <?php if($error): ?>
      <div class="flex flex-wrap justify-center gap-2 rounded-md bg-red-700 text-white p-2 mt-4">
        <p><?= $error ?></p>
      </div>
      <?php endif; ?>
      
      <div class="mt-10">
        <div>
          <form action="" method="post" class="space-y-6" enctype="multipart/form-data">
            <input type="hidden" name="id_membre" >
            <div class="flex flex-col md:flex-row justify-between gap-4">
              <div>
                <label
                        for="nom"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Nom  <span class="font-normal text-gray-300">* optionnel</span></label
                        >
                      <div class="mt-2">
                        <input
                          id="nom"
                          name="nom"
                          type="text"
                          class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                        />
                      </div>
                    </div>

                    <div>
                      <label
                        for="prenom"
                        class="block text-sm font-medium leading-6 text-gray-900">Prénom <span class="font-normal text-gray-300">* optionnel</span></label>
                      <div class="mt-2">
                        <input
                          id="prenom"
                          name="prenom"
                          type="text"
                          class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                        />
                      </div>
                    </div>
                  </div>

                  <div>
                    <label for="pseudo" class="block text-sm font-medium leading-6 text-gray-900"
                      >Pseudo</label
                    >
                    <div class="mt-2">
                      <input
                        id="pseudo"
                        name="pseudo"
                        type="text"
                        required
                        class="block w-full rounded-md border-0 py-1.5 shadow-sm
                         ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                          focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                      />
                    </div>
                  </div>
                  <div class="">
                    <label for="birthday" class="block text-sm font-medium leading-6 text-gray-900"
                    >Date anniversaire</label>
                    <div class="mt-2">
                      <input
                      datepicker
                      max="<?= $today ?>" min="1940-01-01"
                      type="date"
                      name="birthday"
                      required
                      class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-1.5 pl-2"
                      placeholder="Birthday date"
                      />
                    </div>
                  </div>
                  <div class="flex flex-col md:flex-row justify-between gap-4">
                    <div>
                      <label for="city" class="block text-sm font-medium leading-6 text-gray-900"
                        >Ville <span class="font-normal text-gray-300">* optionnel</span></label
                      >
                      <div class="mt-2">
                        <input
                          id="city"
                          name="ville"
                          type="text"
                          class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                        />
                      </div>
                    </div>

                    <div>
                      <label for="country" class="block text-sm font-medium leading-6 text-gray-900">Pays <span class="font-normal text-gray-300">* optionnel</span></label>
                      <div class="mt-2">
                        <input
                          id="country"
                          name="pays"
                          type="text"
                          class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                        />
                      </div>
                    </div>
                  </div>

                  <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900"
                      >Email</label
                    >
                    <div class="mt-2">
                      <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                      />
                    </div>
                  </div>
                  <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900"
                      >Mot de passe <span class="font-normal text-gray-300">* Condition (min.6, Une majuscule, un nombre, un charactere special parmis ( -+!*$@%_ ))</span></label
                    >
                    <div class="mt-2">
                      <input
                        id="password"
                        name="mdp"
                        type="password"
                        required
                        class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                      />
                    </div>
                  </div>
                  <div>
                      <label
                        for="cover-photo"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Photo de profil <span class="font-normal text-gray-300">* optionnel</span></label
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
                          <p class="text-xs leading-5 text-gray-600">PNG, JPG up to 10MB</p>
                        </div>
                      </div>
                    </div>
                </div>
                  <input type="hidden" name="role" value="creator">
                  <div class="mt-4">
                    <button
                      type="submit"
                      value="Envoyer"
                      class="flex w-full justify-center rounded-md bg-[#C8B6FF] px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:scale-95 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C8B6FF]">
                      Create Account
                    </button>
                  </div>
                </div>
              </div>
              
            </form>
            </div>
        </div>
    </section>

<?php require_once('inc/foot.php'); ?>
