<?php include_once("init.php"); ?>

<?php  

$toutLesPosts = $pdo->query("SELECT * FROM forum");

if(isset($_GET['recherche']) && !empty($_GET['recherche']))
{
  $recherche = htmlspecialchars($_GET['recherche']);
  $toutLesPosts = $pdo->query('SELECT * FROM forum WHERE titre LIKE "%'.$recherche.'%"');
} 

// debug(RACINE_SITE,2);

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- ---------------------------- Tailwind css ----------------------------- -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- ---------------------------- Alpine js cdn ---------------------------- -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Forge-art</title>

    <style>
      [x-cloak] {
        display: none !important;
      }
    </style>
  </head>
  <body class="grid">
    <header>
      <nav x-data="{show: false, openMenuDropdown: false}" class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-2 sm:px-4 lg:px-8">
          <div class="relative flex h-16 items-center justify-between">
            <div class="flex items-center px-2 lg:px-0">
              <div class="flex-shrink-0">
                <a href="<?= URL ?>">
                  <img
                  class="h-10 w-auto"
                  src="./inc/LOGO.png"
                  alt="Forge-art"
                  />  
                </a>             
              </div>
              <div class="hidden lg:ml-6 lg:block">
              <div class="flex space-x-4">
                  <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                  <a
                  href="<?= URL ?>"
                    class="rounded-md capitalize px-3 py-2 text-sm font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                    >Accueil</a
                  >
                  <a
                  href="<?= URL ?>all_art.php"
                    class="rounded-md capitalize px-3 py-2 text-sm font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                    >Filtre étiquette</a
                  >
                  <a
                  href="<?= URL ?>filter_az.php"
                    class="rounded-md capitalize px-3 py-2 text-sm font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                    >Filtre date</a
                  >
                  <?php if(internauteConnecte()): ?>
                  <a
                  href="<?= URL ?>your_art.php"
                    class="rounded-md capitalize px-3 py-2 text-sm font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                    >Tes publications</a
                  >
                  <?php endif ?>
                  <?php if(internauteConnecte()  && $_SESSION['membre']['role'] === "admin"): ?>
                  <a
                  href="<?= URL ?>admin/allInfo.php"
                    class="rounded-md capitalize px-3 py-2 text-sm font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                    >Admin</a
                  >
                  <?php endif ?>
                  <a
                  href="<?= URL ?>propos.php"
                    class="rounded-md capitalize px-3 py-2 text-sm font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                    >À propos</a
                  >
                </div>
              </div>
            </div>
            <div class="flex flex-1 justify-center px-2 lg:ml-6">
              <div class="w-full max-w-lg lg:max-w-xs">
                
                <label for="recherche" class="sr-only">recherche</label>
                <div class="relative">
                    <form action="<?= URL ?>recherche.php" method="get">
                    <input
                    id="recherche"
                    name="recherche"
                    class="block w-full rounded-l-md border-0 bg-white py-1.5 pl-1 pr-3 text-gray-900 ring-1 ring-inset ring-gray-400 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
                    placeholder="recherche... par titre"
                    type="search"
                    />
                  </div>
                </div>
                <div class="flex align-center ring-gray-400 ring-1 ring-inset rounded-r-md bg-[#FFD6FF] hover:bg-[#C8B6FF]">
                  <button type="submit" value="envoyer"><img src="./image/icons8-loupe-30" alt=""></button>
                </div>
              </form>
              </div>

            <!-- ----------------------------------------------------------------------- -->
            <!--                               Icon menu Open and close                  -->
            <!-- ----------------------------------------------------------------------- -->
            <div class="flex lg:hidden">
              <!-- Mobile menu button -->
              <button
                type="button"
                class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                aria-controls="mobile-menu"
                aria-expanded="false"
                @click="show=!show"
              >
                <span class="absolute -inset-0.5"></span>
                <span class="sr-only">Open main menu</span>
                <!--
              Icon when menu is closed.
  
              Menu open: "hidden", Menu closed: "block"
              icon pour ouvrir le menu
            -->
                <svg
                  :class="`${show ? 'hidden' : 'block'} h-6 w-6`"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  aria-hidden="true"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                  />
                </svg>
                <!--
              Icon when menu is open.
  
              Menu open: "block", Menu closed: "hidden"
              icon pour fermer le menu
            -->
                <svg
                  :class="`${show ? 'block' : 'hidden'} h-6 w-6`"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  aria-hidden="true"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <div class="hidden lg:ml-4 lg:block">
              <div class="flex items-center">
                <!-- Profile dropdown -->
                <div class="relative ml-4 flex-shrink-0">
                  <?php if(internauteConnecte()): ?>
                  <div>
                    <button
                      type="button"
                      class="relative flex rounded-full bg-[#C8B6FF] text-sm text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#C8B6FF]"
                      id="user-menu-button"
                      aria-expanded="false"
                      aria-haspopup="true"
                      @click="openMenuDropdown=!openMenuDropdown"
                      @click.outside="openMenuDropdown ? openMenuDropdown = false : null"
                    >
                      <span class="absolute -inset-1.5"></span>
                      <span class="sr-only">Open user menu</span>
                      <?php if($_SESSION['membre']['photo_membre']): ?>
                      <img
                        class="h-8 w-8 rounded-full object-cover"
                        src="<?= $_SESSION['membre']['photo_membre'] ?>"
                        alt="profil photo <?= $_SESSION['membre']['pseudo'] ?>"
                      />
                      <?php else: ?>
                        <img
                        class="h-8 w-8 rounded-full object-cover"
                        src="./image/LOGO.png"
                        alt="profil photo <?= $_SESSION['membre']['pseudo'] ?>"
                      />
                      <?php endif; ?>
                    </button>
                  </div>

                  <!--
                Dropdown menu, show/hide based on menu state.
  
                Entering: "transition ease-out duration-100"
                  From: "transform opacity-0 scale-95"
                  To: "transform opacity-100 scale-100"
                Leaving: "transition ease-in duration-75"
                  From: "transform opacity-100 scale-100"
                  To: "transform opacity-0 scale-95"
              -->
                  <div
                    x-show="openMenuDropdown"
                    class="absolute right-0 z-30 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    role="menu"
                    aria-orientation="vertical"
                    aria-labelledby="user-menu-button"
                    tabindex="-1"
                  >

                    <a
                      href="<?= URL ?>creation_post.php"
                      class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                      >Création publication</a
                    >

                    <!-- Active: "bg-gray-100", Not Active: "" -->
                    <a
                      href="<?= URL ?>profil.php"
                      class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                      role="menuitem"
                      tabindex="-1"
                      id="user-menu-item-0"
                      >Profil de <?= $_SESSION['membre']['pseudo'] ?></a
                    >
                    <a
                    href="<?= URL ?>connexion.php?action=deconnexion"
                      class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                      role="menuitem"
                      tabindex="-1"
                      id="user-menu-item-2"
                      >Déconnexion</a
                    >
                  </div>
                  <?php else: ?>
                    <div class="flex space-x-4">
                      <a
                      href="<?= URL ?>inscription.php"
                      class="rounded-md capitalize px-3 py-2 text-sm font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                      role="menuitem"
                      tabindex="-1"
                      id="user-menu-item-0"
                      >Inscription</a
                      >
                      <a
                      href="<?= URL ?>connexion.php"
                      class="rounded-md capitalize px-3 py-2 text-sm font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                      role="menuitem"
                      tabindex="-1"
                      id="user-menu-item-2"
                      >Connexion</a
                      >                      
                    </div>
                    <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <!-- x-cloak de cacher les elements à l'affichage comme le menu dropdrown ou modal -->
        <div
          x-show="show"
          x-cloak
          x-transition:enter="transition ease-in duration-500"
          x-transition:enter-start="transform origin-top scale-y-0 opacity-50 shadow-none"
          x-transition:enter-end="transform origin-top scale-y-100 opacity-100 shadow-2xl"
          x-transition:leave="transition ease-out duration-200"
          x-transition:leave-start="transform origin-top scale-y-100 opacity-100 shadow-2xl"
          x-transition:leave-end="transform origin-top scale-y-0 opacity-50 shadow-none"
          class="lg:hidden"
          id="mobile-menu"
        >
          <div class="space-y-1 px-2 pb-3 pt-2">
            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
            <a
            href="<?= URL ?>accueil.php"
              class="block rounded-md bg-[#C8B6FF] px-3 py-2 text-base font-medium text-white"
              >Home</a
            >
            <a
              href="<?= URL ?>all_art.php"
              class="block capitalize rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
              >Filtre étiquette</a
            >
            <a
                  href="<?= URL ?>filter_az.php"
                  class="block capitalize rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                    >Filtre date</a
                  >
            <?php if(internauteConnecte()): ?>
                  <a
                  href="<?= URL ?>your_art.php"
                  class="block capitalize rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                    >Your art</a
                  >
                  <?php endif ?>
                  <a
                  href="<?= URL ?>propos.php"
                  class="block capitalize rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                    >À propos</a
                  >
          </div>
          <div class="border-t border-gray-200 pb-3 pt-4">
            <?php if(internauteConnecte()): ?>
            <div class="flex items-center px-5">
              <div class="flex-shrink-0">
                <img
                  class="h-10 w-10 rounded-full object-cover"
                  src="<?= $_SESSION['membre']['photo_membre'] ?>"
                  alt="Profil photo <?= $_SESSION['membre']['pseudo'] ?>"
                />
              </div>
              <div class="ml-3">
                <div class="text-base font-medium text-white"><?= $_SESSION['membre']['pseudo'] ?></div>
                <div class="text-base font-medium text-white"><?= $_SESSION['membre']['email'] ?></div>
              </div>
            </div>
            <div class="mt-3 space-y-1 px-2">
              <a
              href="<?= URL ?>creation_post.php"
                class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                >Création publication</a
              >

              <a
              href="<?= URL ?>profil.php"
                class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                >Profil de <?= $_SESSION['membre']['pseudo'] ?></a
              >

              <a
                href="<?= URL ?>connexion.php?action=deconnexion"
                class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                >Déconnexion</a
              >
            </div>
            <?php else: ?>
            <div class="mt-3 space-y-1 px-2">
              <a
              href="<?= URL ?>inscription.php"
                class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                >Inscription</a
              >

              <a
              href="<?= URL ?>connexion.php"
                class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-[#C8B6FF] hover:text-white"
                >Connexion</a
              >
            </div>
            <?php endif; ?>
          </div>
        </div>
      </nav>
    </header>
    <main class="mt-10 mx-auto max-w-7xl px-2 sm:px-4 lg:px-8 flex flex-col gap-10 relative">
    <div class="fixed bottom-4 right-4 z-40">
    <a href="#"><button href="#" class="p-1.5 bg-[#FFD6FF] rounded-full text-xs font-medium text-purple-700"><img src="./image/icons8-en-haut-48.png" alt="fleche vers le haut"></button></a>
  </div>