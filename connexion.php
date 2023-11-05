<?php require_once("inc/init.php");?>


<?php

if(isset($_GET['action'])&& $_GET['action']=='deconnexion')
{
    unset($_SESSION['membre']);//On retire le  tableau membre de la session pour se deconnecter (ne plus etre authentifie)

}

if(internauteConnecte())
{
    header('location:profil.php');
    exit();
}

$error='';

if($_POST)
{
    $resultat = $pdo->query("SELECT * FROM membre WHERE email='$_POST[email]'");
    if($resultat->rowCount() == 1)
    {
        $membre = $resultat->fetch(PDO::FETCH_ASSOC);

        if(password_verify($_POST['mdp'], $membre['password']))
        {
            $_SESSION['membre']['id_membre']=$membre['id_membre'];
            $_SESSION['membre']['pseudo']=$membre['pseudo'];
            $_SESSION['membre']['mdp']=$membre['mdp'];
            $_SESSION['membre']['email']=$membre['email'];
            $_SESSION['membre']['prenom']=$membre['prenom'];
            $_SESSION['membre']['nom']=$membre['nom'];
            $_SESSION['membre']['birthday']=$membre['birthday'];
            $_SESSION['membre']['pays']=$membre['country'];
            $_SESSION['membre']['ville']=$membre['city'];
            $_SESSION['membre']['photo_membre']=$membre['photo_membre'];
            $_SESSION['membre']['role']=$membre['role'];

            header('location:index.php');
            exit();

        }else{
            $error .= "<p>L'email ou le mot de passe est incorrect !</p>";
        }
    }else{
        $error .= "<p>L'email rentre n'est pas associer a un compte !</p>";
    }
}

?>


<?php require_once("inc/head.php");?>

<!-- <form action="" method="post" class="form-connexion form-all">

    <label for="pseudo">Email :</label>
    <input type="email" name="email" class="input-connexion input-form" placeholder="Email..">

    <label for="mdp">Mot de Passe :</label>
    <input type="password" name="mdp" class="input-connexion input-form" placeholder="Password...">

    <input type="submit" value="Envoyer" class="input-submit">
</form> -->

<section class="flex min-h-full">
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
          <div class="mx-auto w-full max-w-sm lg:w-96">
            <div>
              <img
                class="h-12 w-auto"
                src="./image/LOGO.png"
                alt="Your Company"
              />
              <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">
              Connexion à votre compte
              </h2>
              <p class="mt-2 text-sm leading-6 text-gray-500">
                Pas de compte ? <a href="<?= URL ?>inscription.php" class="font-semibold text-[#C8B6FF] hover:text-[#FFD6FF]">Clique ici !</a>
              </p>
            </div>

            <?php if($error): ?>
              <div class="bg-red-500 rounded-lg flex items-center justify-center p-2">
                <?= $error ?>
              </div>
            <?php endif; ?>
            <div class="mt-10">
              <div>
                <form action="#" method="POST" class="space-y-6">
                  <div>
                    <label class="block text-sm font-medium leading-6 text-gray-900"
                      >Email</label>
                    <div class="mt-2">
                      <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                      />
                    </div>
                  </div>

                  <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900"
                      >Mot de passe</label
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

                  <!-- <div class="flex items-center justify-between">
                    <div class="text-sm leading-6">
                      <a href="#" class="font-semibold text-[#C8B6FF] hover:text-indigo-500"
                        >Forgot password?</a
                      >
                    </div>
                  </div> -->

                  <div>
                    <button
                      type="submit"
                      value="Connexion"
                      class="flex w-full justify-center rounded-md bg-[#C8B6FF] px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:scale-95 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C8B6FF]"
                    >
                    Connexion
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
<?php require_once("inc/foot.php");?>
