<?php require_once("inc/init.php");?>

<?php  
if(!internauteConnecte())
{
    header('location:connexion.php');
    exit();
}

if(isset($_GET['action']) && $_GET['action'] === "suppression")
{
    $pdo->exec("DELETE FROM forum WHERE id_reference=$_GET[id_post]");
    header('location:your_art.php');
}


$resultat = $pdo->query("SELECT * FROM forum WHERE id_membre=".$_SESSION['membre']['id_membre']."");
$posts = $resultat->fetchAll(PDO::FETCH_ASSOC); 
count($posts);

?>

<?php require_once("inc/head.php");?>

<section class="grid grid-cols-2 md:grid-cols-3 gap-4">
    <?php if(count($posts) !== 0): ?>
          <?php foreach($posts as $post): ?>
          <?php $resultat = $pdo->query("SELECT * FROM membre WHERE id_membre = $post[id_membre]");
            $membre = $resultat->fetchAll(PDO::FETCH_ASSOC);?>
      <div class="grid gap-4 h-64">
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
                  <span class="absolute inset-0"></span>
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
                    <?php if($value['photo_membre']): ?>
                        <img
                        src="<?= $value['photo_membre'] ?>"
                        alt="<?= $value['pseudo'] ?> profil photo"
                        class="h-6 w-6 flex-none rounded-full bg-white/10 object-cover"
                        />
                    <?php else: ?>
                      <img
                        src="./image/LOGO.png"
                        alt="<?= $value['pseudo'] ?> profil photo"
                        class="h-6 w-6 flex-none rounded-full bg-white/10 object-cover"
                        />
                    <?php endif; ?>
                    </a>
                  <a href="<?= URL ?>profil_share.php?id_membre=<?= $value['id_membre'] ?>"><?= $value['pseudo'] ?></a>
                </div>
              </div>
            </div>
        </div> 
        <div class="flex justify-between items-end">
            <div class="">
                <a href="<?= URL ?>modif_post.php?action=modification&id_post=<?= $post['id_reference'] ?>&pseudo=<?= $value["pseudo"] ?>"><button class="rounded-lg bg-[#C8B6FF] px-4 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:scale-95">Modif.</button></a>
            </div>
            <div class="">
                <a href="?action=suppression&id_post=<?= $post['id_reference'] ?>" onclick="return confirm('Etes vous sur de vouloir supprimer le produit ?')"><button class="rounded-lg bg-red-500 px-4 py-2 text-sm font-semibold leading-6 text-white shadow-sm hover:scale-95">Suppr.</button></a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</section>
<?php else: ?>
    <div class="flex flex-cols gap-4 flex-wrap justify-center col-start-1 col-end-3 md:col-end-4">
            <p class="border-red-500 border-2 rounded-lg p-4">Vous n'avez pas encore de post, vous pouvez des maintenant en creer un avec le button en-dessous !</p>
            <a href="<?= URL ?>creation_post.php" class="flex w-1/2 justify-center rounded-md bg-[#C8B6FF] px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:scale-95 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#C8B6FF]"><Button>Creer un post</Button></a>
    </div>
        <?php endif; ?>

<?php require_once("inc/foot.php");?>
