<?php
require_once('views/partials/header.php');

use Data\DataManager;
use Bookshop\Category;
$categories = DataManager::getCategories();

$categoryId = (int)($_REQUEST['categoryId'] ?? null);
$books = $categoryId > 0 ? DataManager::getBooksByCategory($categoryId) : null;
?>
  <div class="page-header">
    <h2>List of books by category</h2>
  </div>

  <ul class="nav nav-tabs">
		<?php foreach ($categories as $cat) : ?>
      <li role="presentation"
			    <?php if ($cat->getId() === $categoryId) { print ' class="active"'; } ?>>
        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?view=list&categoryId=<?php echo urlencode($cat->getId()); ?>"><?php echo ($cat->getName()); ?></a></span>
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
require_once('views/partials/footer.php');
