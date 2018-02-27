<?php
if (Storemaker\System\Libraries\Session::flashExists('home')):
	$flash = Storemaker\System\Libraries\Session::flash('home');
?>
	<div class="alert <?php echo $flash['type']; ?>">
		<span class="alertBody"><?php echo $flash['msg']; ?></span>
		<a href="#" class="alertClose">&times;</a>
	</div>
<?php endif; ?>


