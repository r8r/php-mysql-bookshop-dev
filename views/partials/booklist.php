<?php
use Bookshop\Util;
use Bookshop\ShoppingCart;

/*
//ShoppingCart::clear();
//ShoppingCart::add(2);
//ShoppingCart::add(6);
//ShoppingCart::add(5);
//ShoppingCart::remove(5);
print "<h2>size()</h2>";
print_r(ShoppingCart::size());
print "<h2>getAll()</h2>";
print_r(ShoppingCart::getAll());
print "<h2>session</h2>";
print_r($_SESSION);
print "<h2>contains()</h2>";
print_r(ShoppingCart::contains(2) ? 'ja' : 'nein');
print_r(ShoppingCart::contains(3) ? 'ja' : 'nein');
*/
?>

<table class="table">
	<thead>
	<tr>
		<th>
			Title
		</th>
		<th>
			Author
		</th>
		<th>
			Price
		</th>
		<th>
			<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ($books as $book):
		$inCart = false;
		?>
		<tr>
			<td><strong>
					<?php echo Util::escape($book->getTitle()); ?>
				</strong>
			</td>
			<td>
				<?php echo Util::escape($book->getAuthor()); ?>
			</td>
			<td>
				<?php echo $book->getPrice(); ?>
			</td>
			<td class="add-remove">

			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>