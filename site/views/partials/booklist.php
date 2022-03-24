<?php
use Bookshop\Util;
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
				Platzhalter
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
