<?php require_once('inc/init.php'); ?>


<?php 

$error = '';


  if($_POST)
{

    if($_POST['pseudo'] !== $_SESSION['membre']['pseudo'])
    {
        $resultat = $pdo->query("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]' ");
        if($resultat->rowCount() == 1)
        {
            $error .="<p>Le pseudo existe deja !</p>";
        }
     
    }

    if(strlen($_POST['pseudo']) < 3 || strlen($_POST['pseudo']) > 20)
        {
            $error .= '<p>Votre pseudo doit etre entre 3 et 20 Caracteres maximum !</p>';
        }

        if(!preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo']))
        {
            $error .= '<p>Votre pseudo doit contenir uniquement des caracteres ALPHANUMERIQUE !';
        }

    if($_POST['email'] !== $_SESSION['membre']['email'])
    {
        $resultat = $pdo->query("SELECT * FROM membre WHERE email = '$_POST[email]");
        if($resultat->rowCount()==1)
        {
            $error .= "<p>Ce mail est deja utilise !</p>";
        }
    }
    $resultat = $pdo->query("SELECT * FROM membre WHERE id_membre=".$_SESSION['membre']['id_membre']."");
    $membre = $resultat->fetch(PDO::FETCH_ASSOC);
    
    $mdp = $membre['password'];
    
    if($_POST['mdp_old'] )
    {
        if(password_verify($_POST['mdp_old'],$membre['password']))
        {
            if($_POST['mdp_new'])
            {
                if(!preg_match("#^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,50})$#", $_POST['mdp_new']))
                    {
                        $error .= "<p>Votre Mot De Passe doit contenir au moins UNE majuscule , UN chiffre est UN caractere special</p>"; 
                    }
                $mdp = password_hash($_POST['mdp_new'], PASSWORD_DEFAULT);
            }else{
                $error .= "vous n'avez pas renseigné de nouveau mdp";
            }
        }else{
            $error .= "votre ancien mot de passe n'est pas valide !";
        }
    }

    
    if(!$error){


        $_POST['mdp_new']= password_hash($_POST['mdp_new'],PASSWORD_DEFAULT);

            if(isset($_GET['action']) && $_GET['action'] == "modification")
            {
                $pdo->exec("UPDATE membre SET pseudo='$_POST[pseudo]', password='$mdp', email='$_POST[email]' WHERE id_membre=".$_SESSION['membre']['id_membre']."");
            
                $_SESSION['membre']['pseudo'] = $_POST['pseudo'];
                $_SESSION['membre']['email'] = $_POST['email'];
                $content .= "Votre changement a bien été pris en compte !";
            }
    }
}

if(isset($_GET['action']) && $_GET['action'] == "modification")
{
$resultat = $pdo->query("SELECT * FROM membre WHERE id_membre=".$_SESSION['membre']['id_membre']."");
$profil_modification = $resultat->fetch(PDO::FETCH_ASSOC);

}

$pseudo = (isset($profil_modification['pseudo'])) ? $profil_modification['pseudo']:"";
$password = (isset($profil_modification['password'])) ? $profil_modification['password']:"";
$email = (isset($profil_modification['email'])) ? $profil_modification['email']:"";

// debug($_SESSION,2);  
?>
<?php require_once('inc/head.php'); ?>

<?php if($error):  ?>
<div class="bg-red-500 p-4 rounded-lg">
<p><?= $error ?></p>
</div>
    <?php endif; ?>
    <?php if($content):  ?>
<div class="bg-emerald-500 p-4 rounded-lg">
<p><?= $content ?> <a href="<?= URL ?>profil.php" class="text-white hover:bg-[#FFD6FF] rounded-md p-1">retour au profil</a></p>
</div>
    <?php endif; ?>
<section class="flex min-h-full justify-center">
    <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
        <form class="md:col-span-2" method="post" enctype="multipart/form-data">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div class="col-span-full">
                    <label
                    for="pseudo"
                    class="block text-sm font-medium leading-6 text-gray-600">
                        Pseudo
                    </label>
                    <div class="mt-2">
                    <input
                        id="pseudo"
                        name="pseudo"
                        type="text"
                        class="block w-full rounded-md border-2 border-gray-400 bg-white/5 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                        value="<?= $pseudo ?>"
                        />
                    </div>
                    <input type="hidden" name="pseudo_actuelle" class="input-form" value="<?= $pseudo ?>">
                </div>
                <div class="col-span-full">
                    <label
                    for="email"
                    class="block text-sm font-medium leading-6 text-gray-600"
                    >Email</label
                    >
                    <div class="mt-2">
                    <input
                        id="email"
                        name="email"
                        type="email"
                        class="block w-full rounded-md border-2 border-gray-400 bg-white/5 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                        value="<?= $email ?>"
                        />
                    </div>
                    <input type="hidden" name="email_actuelle" class="input-form" value="<?= $email ?>">
                </div>

                <div class="col-span-full">
                    <label for="mdp" class="block text-sm font-medium leading-6 text-gray-600"
                    >Mot de passe actuelle</label
                    >
                    <div class="mt-2">
                    <input
                        id="mdp_old"
                        name="mdp_old"
                        type="password"
                        placeholder="Obligatoire..."
                        value=""
                        class="block w-full rounded-md border-2 border-gray-400 bg-white/5 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                    />
                    </div>
                    <input type="hidden" name="mdp_actuelle" class="input-form" value="<?= $password ?>">
                </div>

                <div class="col-span-full">
                    <label
                    for="mdp"
                    class="block text-sm font-medium leading-6 text-gray-600"
                    >Nouveau Mot de passe</label
                    >
                    <div class="mt-2">
                    <input
                        id="mdp_new"
                        name="mdp_new"
                        type="password"
                        value=""
                        class="block w-full rounded-md border-2 border-gray-400 bg-white/5 py-1.5 text-gray-600 shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                    />
                    </div>
                </div>
            </div>

                <div class="mt-8 flex">
                <button
                    type="submit" value="Envoyer"
                    class="rounded-md bg-[#C8B6FF] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#C8B6FF]/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C8B6FF]"
                    onclick="return confirm('Etes vous sur de vouloir faire ce changement ?')"
                    >
                    Sauvegarder
                </button>
                </div>
        </form>
    </div>
</section>

<?php require_once('inc/foot.php'); 






?>
