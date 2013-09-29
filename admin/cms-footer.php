		</div>

	</div>

</div>

<?php if (in_array('blog-update.php',$php_self_exploded)) { ?>

	<!-- http://docs.jquery.com/Plugins/autocomplete -->
	<script src="<?= HOST ?>/js/jquery.autocomplete.js"></script>

	<script src="http://jhollingworth.github.io/bootstrap-wysihtml5/lib/js/wysihtml5-0.3.0.js"></script>
	<script src="http://jhollingworth.github.io/bootstrap-wysihtml5/lib/js/bootstrap.min.js"></script>
	<script src="http://jhollingworth.github.io/bootstrap-wysihtml5/src/bootstrap-wysihtml5.js"></script>

	<script>
		$('.textarea').wysihtml5();
	</script>

<?php } ?>

<script type="text/javascript" src="<?= HOST ?>/js/cms-jquery-scripts.js"></script>

</body>
</html>