<?php
use Bookshop\Category;
use Data\DataManager;

require_once('views/partials/header.php');

?>

	<div class="page-header">
		<h2>Books</h2>
	</div>

<?php

$categories = DataManager::getCategories();

foreach ($categories AS $category) {
  print 'Category ' . $category->getId() . ': ' . $category->getName() . '<br>';
}



?>

<?php
require_once('views/partials/footer.php');