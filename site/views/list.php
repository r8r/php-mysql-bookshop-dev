<?php
require_once('views/partials/header.php');

use Data\DataManager;
use Bookshop\Util;

$categories = DataManager::getCategories();

//$categoryId = isset($_REQUEST['categoryId']) ? $_REQUEST['categoryId'] : null;

$categoryId = $_REQUEST['categoryId'] ?? null;

if ($categoryId) {
  $books = DataManager::getBooksByCategory($categoryId);
}

?>
  <div class="page-header">
    <h2>List of books by category</h2>
  </div>

  <ul class="nav nav-tabs">

		<?php foreach ($categories as $cat) : ?>
      <li role="presentation" class="navitem">
        <button class="nav-link <?php if ($cat->getId() === (int)$categoryId) : ?>active <?php endif; ?>">
          <a href="<?php echo $_SERVER['PHP_SELF'] ?>?view=list&amp;categoryId=<?php echo urlencode($cat->getId()); ?>">
            <?php echo Util::escape($cat->getName()); ?>
          </a>
        </button>
      </li>
		<?php endforeach; ?>
  </ul>

<?php
if (isset($books)) {
  if (sizeof($books) > 0) {
    require_once('views/partials/booklist.php');
  }
}
?>

<?php
require_once('views/partials/footer.php');