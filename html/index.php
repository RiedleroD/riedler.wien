<?php
	require_once("befuncs/snips.php");
	require_once("befuncs/db_music.php");
	require_once("befuncs/db_coding.php");
	genUsual("Riedler",['/style/home.css'],"");
?>
<body>
	<main>
		<h1>Riedler</h1>
		<a href="music">Music</a>
		<a href="coding">Code</a>
		<a href="about">About</a>
	</main>
	<?php genFooter(); ?>
</body>
</html>