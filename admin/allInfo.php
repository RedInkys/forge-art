<?php require_once("../inc/init.php");?>

<?php 

if(internauteConnecte() && $_SESSION['membre']['role'] !== 'admin'){
    header('location:../accueil.php');
}

$resultat = $pdo->query("SELECT * FROM membre");
$membres = $resultat->fetchAll(PDO::FETCH_ASSOC);

function hashe($item)
{
    return password_hash($item, PASSWORD_DEFAULT);
}

if(isset($_GET['action']) && $_GET['action'] === "suppression")
{
	$pdo->exec("DELETE FROM membre WHERE id_membre=$_GET[id]");
	header('location:allInfo.php');
}





?>

<?php include_once("../inc/head.php") ?>

<div class="flex flex-1 justify-center px-2 lg:ml-6">
    <div class="w-full max-w-lg lg:max-w-xs">
                
      <label for="rechercheAdmin" class="sr-only">recherche</label>
      <div class="relative">
        <form action="<?= URL ?>admin/rechercheAdmin.php" method="get">
          <input
          id="rechercheAdmin"
          name="rechercheAdmin"
          class="block w-full rounded-l-md border-0 bg-white py-1.5 pl-1 pr-3 text-gray-900 ring-1 ring-inset ring-gray-400 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-[#C8B6FF] sm:text-sm sm:leading-6"
          placeholder="recherche... par pseudo"
          type="search"
          />
      </div>
    </div>
    <div class="flex align-center ring-gray-400 ring-1 ring-inset rounded-r-md bg-[#FFD6FF] hover:bg-[#C8B6FF]">
      <button type="submit" value="envoyer"><img src="../image/icons8-loupe-30" alt=""></button>
    </div>
  </form>
</div>
<div class="px-4 sm:px-6 lg:px-8">
  <div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
        <table class="min-w-full divide-y divide-gray-300">
          <thead>
            <tr>
              <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Photo</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Pseudo</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">prenom</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nom</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ville</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Pays</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Anniversaire</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Role</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white">
              <?php foreach ($membres as $membre ): ?>
              <tr>
              <td class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
                <div class="flex items-center">
                  <div class="h-11 w-11 flex-shrink-0">
                    <?php if($membre['photo_membre']): ?>
                    <img class="h-11 w-11 rounded-full object-cover" src="<?= $membre["photo_membre"] ?>" alt="photo de <?= $membre['pseudo'] ?>">
                    <?php  else: ?>
                    <img class="h-11 w-11 rounded-full object-cover" src="../image/LOGO.png" alt="photo de <?= $membre['pseudo'] ?>">
                    <?php endif ?>
                  </div>
                </td>
                <td>
                    <div class="ml-4">
                        <div class="font-medium text-gray-900"><a href="<?= URL ?>admin/profilUser.php?action=modification&pseudo=<?= $membre['pseudo'] ?>"><?= $membre['pseudo'] ?></a></div>
                    </div>
                </td>

                <td>
                <div class="ml-4">
                        <div class="font-medium text-gray-900"><?= $membre['prenom'] ?></div>
                    </div>
                </td>
                <td>
                <div class="ml-4">
                        <div class="font-medium text-gray-900"><?= $membre['nom'] ?></div>
                    </div>
                </td>
                <td>
                <div class="ml-4">
                        <div class="font-medium text-gray-900"><?= $membre['city'] ?></div>
                    </div>
                </td>
                <td>
                <div class="ml-4">
                        <div class="font-medium text-gray-900"><?= $membre['country'] ?></div>
                    </div>
                </td>
                <td>
                <div class="ml-4">
                        <div class="font-medium text-gray-900"><?= $membre['birthday'] ?></div>
                    </div>
                </td>
                <td>
                <div class="ml-4">
                        <div class="font-medium text-gray-900"><?= $membre['role'] ?></div>
                    </div>
                </td>
                <td>
                <div class="ml-4 gap-2 flex flex-col">
                        <div class="font-medium text-gray-900 bg-[#C8B6FF] rounded-md p-1"><a href="<?= URL ?>admin/profilUser.php?action=modification&pseudo=<?= $membre['pseudo'] ?>">Modif.</a></div>
                        <div class="font-medium text-white bg-red-700 rounded-md p-1"><a href="?action=suppression&id=<?= $membre['id_membre'] ?>" onclick="return confirm('Etes vous sur de vouloir supprimer le produit ?')">Suppr.</a></div>
                    </div>
                </td>
                
            </tr>
            <?php endforeach ?>

            <!-- More people... -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>






<?php include_once("../inc/foot.php") ?>
