<?php


$orderId = isset($_REQUEST['orderId']) ? $_REQUEST['orderId'] : null;

require_once('views/partials/header.php');
?>

	<div class="page-header">
		<h2>Success!</h2>
	</div>

	<p>Thank you for your purchase.</p>

<?php if ($orderId != null) : ?>
	<p>Your order number is <?php echo Bookshop\Util::escape($orderId); ?>.</p>
<?php endif; ?>

<?php /* TODO order Summary */ ?>

<?php require_once('views/partials/footer.php');