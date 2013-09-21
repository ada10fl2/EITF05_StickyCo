	<hr>
	<footer>
        <p>&copy; <?= (!isset($title) || trim($title) == false) ? "StickyCo.se" : $title ?> by <b>ada10fl2</b>, <b>ada10jbe</b></p>
		<?php if(isset($debug) && $debug === TRUE) { ?>
		<b>Debugging: $_SESSION</b>
		<pre><?php var_dump($_SESSION); ?></pre>
		<?php } ?>
	</footer>

</div><!--/.container-->