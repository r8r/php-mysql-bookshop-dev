<?php
/**
 * Created by PhpStorm.
 * User: r8r
 * Date: 2020-05-14
 * Time: 10:45
 */

use Bookshop\AuthenticationManager;
use Bookshop\Util;
use Bookshop\ShoppingCart;

$nameOnCard = $_REQUEST[Bookshop\Controller::CC_NAME] ?? null;
$cardNumber = $_REQUEST[Bookshop\Controller::CC_NUMBER] ?? null;

require_once ('views/partials/header.php');
$cartSize = ShoppingCart::size();
?>

<div class="page-header">
	<h2>Checkout</h2>
</div>

<?php if ($cartSize > 0) : ?>

	<?php if (AuthenticationManager::isAuthenticated()) : ?>

	<div class="panel panel-default">
		<div class="panel-heading">
			Please provide your credit card details for payment:
		</div>
		<div class="panel-body">
			<form class="form-horizontal" method="post" action="<?php echo Util::action(Bookshop\Controller::ACTION_ORDER); ?>">
				<div class="form-group">
					<label for="nameOnCard" class="col-sm-4 control-label">Name on card:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="nameOnCard" name="<?php print Bookshop\Controller::CC_NAME;
						?>" placeholder="Your name please!" value="<?php echo htmlentities($nameOnCard); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="cardNumber" class="col-sm-4 control-label">Card Number:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="cardNumber" name="<?php print Bookshop\Controller::CC_NUMBER; ?>" placeholder="try '1234567891234567'" value="<?php echo htmlentities($cardNumber); ?>">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-8">
						<button type="submit" class="btn btn-default">Place Order</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<?php else: ?>
		<p class="errors alert alert-info">Please log in to place your order.</p>
	<?php endif; ?>
<?php else: ?>
	<p class="errors alert alert-info">Please add some items to your cart.</p>
<?php endif; ?>

<?php
require_once ('views/partials/footer.php');