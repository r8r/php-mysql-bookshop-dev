<?php

use Data\DataManager;
use Bookshop\Util;

$categories = DataManager::getCategories();

// $categoryId = isset($_REQUEST['categoryId']) ? $_REQUEST['categoryId'] : null;
$categoryId = (int)($_REQUEST['categoryId'] ?? null);
$books = (isset($categoryId) && $categoryId > 0) ?
    DataManager::getBooksByCategory($categoryId) : null;

require_once('views/partials/header.php');
?>

<div class="page-header">
  <h2>List of books by category</h2>
</div>

<ul class="nav nav-tabs">

  <?php foreach ($categories as $cat) : ?>

    <li role="presentation" 
        <?php if ($categoryId === $cat->getId()) : ?>
        class="active"
        <?php endif; ?>
    >
      <a href="<?php print $_SERVER['PHP_SELF']; ?>?view=<?php print $_REQUEST['view']; ?>&amp;categoryId=<?php print
        $cat->getId(); ?>">
        <?php print $cat->getName(); ?>
      </a>
    </li>

  <?php endforeach; ?>

</ul>

<?php if (isset($books)) : ?>
  <?php if (sizeof($books) > 0) : ?>
    <?php require_once('views/partials/booklist.php'); ?>
  <?php else : ?>
    <div class="alert alert-warning" role="alert">No books in this category.</div>
	<?php endif; ?>
<?php else : ?>
  <div class="alert alert-info" role="alert">Please select a category.</div>
<?php endif; ?>

<?php
require_once('views/partials/footer.php');