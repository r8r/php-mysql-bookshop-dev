
<!--display error messages-->

<?php
use Bookshop\Util;

if (isset($errors) && is_array($errors)): ?>
    <div class="errors alert alert-danger">
      <ul>
        <?php foreach ($errors as $errMsg): ?>
          <li><?php echo(Util::escape($errMsg)); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
<?php endif; ?>

<!--/display error messages-->
<div class="footer">
	<!--display cart info-->
	<hr />
	<div class="col-sm-8">
		<button class="btn btn-primary btn-xs" type="button">
			<span class="badge"><?php print \Bookshop\ShoppingCart::size(); ?></span> items in cart
		</button>
	</div>
	<div class="col-sm-4 pull-right">
		<p>Fri Mar 3 21:17:10 2019</p>
	</div>
	<!--/display cart info-->
</div>
</div> <!-- container -->

<script src="assets/jquery-1.11.2.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>