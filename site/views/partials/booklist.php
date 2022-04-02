<?php
use Bookshop\Util;
use Bookshop\Controller;
use Bookshop\ShoppingCart;
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
    $inCart = ShoppingCart::contains($book->getId());
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
				<?php echo sprintf('%01.2f', $book->getPrice()); ?>&nbsp;&euro;
			</td>
			<td class="add-remove">
        <?php if ($inCart) : ?>
          <form method="post" action="<?php
	        print Util::action(Controller::ACTION_REMOVE, ['bookId' => $book->getId()])
	        ?>">
            <button type="submit" role="button" class="btn btn-default btn-xs btn-info">
              <span class="glyphicon glyphicon-minus"></span>
            </button>
          </form>
        <?php else : ?>
          <form method="post" action="<?php
          print Util::action(Controller::ACTION_ADD, ['bookId' => $book->getId()])
          ?>">
            <button type="submit" role="button" class="btn btn-default btn-xs btn-success">
              <span class="glyphicon glyphicon-plus"></span>
            </button>
          </form>
        <?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
