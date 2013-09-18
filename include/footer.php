	<hr>
	<footer>
        <p>&copy; <?= (!isset($title) || trim($title) == false) ? "StickyCo" : $title ?> by <b>ada10fl2</b>, <b>ada10jbe</b></p>
		<b>Debugging: $_SESSION</b>
		<pre><?php var_dump($_SESSION); ?></pre>
	</footer>

</div><!--/.container-->