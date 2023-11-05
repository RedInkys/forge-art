<?php require_once("inc/init.php");?>


<?php
$resultat = $pdo->query("SELECT DISTINCT tag FROM forum ORDER BY tag ASC");
$tags = $resultat->fetchAll(PDO::FETCH_ASSOC);

if(isset($_GET['tag']))
{
$resultat = $pdo->query("SELECT * FROM forum WHERE tag='$_GET[tag]'");

}else{
    $resultat = $pdo->query("SELECT * FROM forum");
}

$posts = $resultat->fetchAll(PDO::FETCH_ASSOC);


if(isset($_GET['tag']) && $_GET['tag'] === "All")
{
  header("location:all_art.php");
}




?>


<?php require_once("inc/head.php");?>

<section>
        <div class="sm:hidden">
          <label for="tabs" class="sr-only">Select a tab</label>
          <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
          <form method="get">
          <select
            id="tag"
            name="tag"
            class="block w-full rounded-md border-2 p-2 border-[#C8B6FF] focus:border-indigo-500 focus:ring-indigo-500"
          >
            <option><a href="all_art.php">All</a></option>
            <?php foreach($tags as $tag): ?>
              <option value="<?= $tag['tag'] ?>"><a href="?tag=<?= $tag['tag'] ?>"><?= $tag['tag'] ?></a></option>
              <?php endforeach; ?>
          </select>
          <input class="block w-1/4 rounded-md border-2 p-2 bg-[#C8B6FF] focus:ring-indigo-500" type="submit" value="Filtrer">
        </form>
        </div>
        <div class="hidden sm:block">
          <nav class="flex space-x-4" aria-label="Tabs">
            <!-- Current: "bg-indigo-100 text-indigo-700", Default: "text-gray-500 hover:text-gray-700" -->
            <a
              href="all_art.php"
              class="text-gray-500 hover:bg-[#C8B6FF] hover:text-white rounded-md px-3 py-2 text-sm font-medium"
              >All</a
            >
            <?php foreach($tags as $tag): ?>
            <a
              href="?tag=<?= $tag['tag'] ?>"
              class="text-gray-500 hover:bg-[#C8B6FF] hover:text-white rounded-md px-3 py-2 text-sm font-medium"
              ><?= $tag['tag'] ?></a
            >
            <?php endforeach; ?>
          </nav>
        </div>
      </section>
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
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>        
  </section>
  <section>

  
  </section>



<?php require_once("inc/foot.php");?>
