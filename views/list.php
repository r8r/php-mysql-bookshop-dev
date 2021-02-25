<?php
include('views/partials/header.php');

//include('lib/Bookshop/Category.php'); // nicht mehr notwendig, weil classloader in inc/bootstrap.php implementiert

use Data\DataManager;

$categories = DataManager::getCategories();
$categoryId = (int)($_REQUEST['categoryId'] ?? null);
$books = (isset($categoryId) && ($categoryId > 0))
  ? DataManager::getBooksByCategory($categoryId)
  : null;

require_once('views/partials/header.php');

?>


<div class="page-header">
  <h2>List of books by category</h2>
</div>

<ul class="nav nav-tabs">
  <?php foreach ($categories as $cat) : ?>

    <li role="presentation" <?php if ($cat->getId() === $categoryId) { ?>class="active" <?php } ?>>
      <a href="<?php echo $_SERVER['PHP_SELF'] ?>?view=list&categoryId=<?php echo urlencode($cat->getId()); ?>">
        <?php echo ($cat->getName()); ?>
      </a>
    </li>

  <?php endforeach; ?>
</ul>

<br />

<?php if (isset($books)) : ?>
	<?php
	if (sizeof($books) > 0) :
		require('views/partials/booklist.php');
	else :
		?>
    <div class="alert alert-warning" role="alert">No books in this category.</div>
	<?php endif; ?>
<?php else : ?>
  <div class="alert alert-info" role="alert">Please select a category.</div>
<?php endif; ?>

<?php
/*
$cat = new Category(5, 'Testbuch');

print "id: " . $cat->getId() . " – name: " . $cat->getName();
*/
?>


<?php
include('views/partials/footer.php');