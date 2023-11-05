<?php require_once('inc/init.php'); ?>

<?php

$resultat = $pdo->query("SELECT * FROM forum WHERE id_membre='$_GET[id_membre]'");
$posts = $resultat->fetchAll(PDO::FETCH_ASSOC);

$resultat = $pdo->query("SELECT * FROM membre WHERE id_membre = $_GET[id_membre]");
$membre = $resultat->fetch(PDO::FETCH_ASSOC);
?>


<?php require_once('inc/head.php'); ?>

<section class="grid grid-cols-1 gap-4 md:grid-cols-2 justify-center">

    <div class="">
        <p class="text-sm font-medium leading-6 text-gray-600">Pseudo : <?= $membre['pseudo'] ?></p>
        <p class="text-sm font-medium leading-6 text-gray-600">Date Anniversaire : <?= $membre['birthday'] ?></p>
        <p class="text-sm font-medium leading-6 text-gray-600">Ville : <?= $membre['city'] ?></p>
        <p class="text-sm font-medium leading-6 text-gray-600">Pays : <?= $membre['country'] ?></p>
    </div>
    <div class=" flex items-center gap-x-8">
                <div class="h-56">
                    <?php if($membre['photo_membre']): ?>
                     <img
                  src="<?= $membre['photo_membre'] ?>"
                  alt="<?= $membre['pseudo'] ?> photo"
                  class="h-full max-w-full flex-none rounded-lg bg-gray-800 object-cover"
                /> 
                <?php else:?>
                    <img
                  src="./image/LOGO.png"
                  alt="<?= $membre['pseudo'] ?> photo"
                  class="h-full max-w-full flex-none rounded-lg bg-gray-800 object-cover"
                /> 
                <?php endif;?>
                </div>
              
                <img src="./image/icons8-flèche-50.png" alt="Arrow icon" class="">

                <div class="flex gap-x-2.5 text-white text-sm">
                <?php if($membre['photo_membre']): ?>
                        <img
                        src="<?= $membre['photo_membre'] ?>"
                        alt="<?= $membre['pseudo'] ?> photo"
                        class="h-12 w-12 flex-none rounded-full bg-white/10 object-cover"
                        />
                <?php else:?>
                    <img
                        src="./image/LOGO.png"
                        alt="<?= $membre['pseudo'] ?> photo"
                        class="h-12 w-12 flex-none rounded-full bg-white/10 object-cover"
                        />
                <?php endif;?>
                </div>
    </div>
</section>
<hr>
<h1 class="text-sm font-medium leading-6 text-gray-600">Voici tout les posts de <?= $membre['pseudo'] ?></h1>
<section class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
                    <?php if($value["photo_membre"]): ?>
                        <img
                        src="<?= $value['photo_membre'] ?>"
                        alt="<?= $value['pseudo'] ?> photo"
                        class="h-6 w-6 flex-none rounded-full bg-white/10 object-cover"
                        />
                    <?php else: ?>
                        <img
                        src="./image/LOGO.png"
                        alt="<?= $value['pseudo'] ?> photo"
                        class="h-6 w-6 flex-none rounded-full bg-white/10 object-cover"
                        />
                    <?php endif; ?>
                    </a>
                  <a href="<?= URL ?>profil_share.php?id_membre=<?= $value['id_membre'] ?>"><?= $value['pseudo'] ?></a>
                </div>
              </div>
            </div>
        </div> 
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
      </section>

<?php require_once('inc/foot.php'); ?>
