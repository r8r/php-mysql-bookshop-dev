<?php
use Bookshop\Util;
?>

<!--display error messages-->
<?php if (isset($errors) && is_array($errors)): ?>
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
	</div>
	<div class="col-sm-4 pull-right">
		<p><?php print date('r'); ?></p>
	</div>
	<!--/display cart info-->
</div>
</div> <!-- container -->

<script src="assets/jquery-1.11.2.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>