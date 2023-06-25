<?php
	require_once("../befuncs/snips.php");
	require_once("../befuncs/db_user.php");
	genUsual('Riedler: Admin Panel',['/style/admin.css'],'');
	
	$userdb = new accountdb();
?>
<body>
	<?php genNavBar(); ?>
	<h1>The almighty Admin Panel</h1>
	<?php
		$user = $userdb->get_user_by_id($_SESSION['userid']);
		if($user['type']!='Admin')
			echo '<h3 style="text-align:center">lose all hope, for thine privileges are lackin\'</h3>'
	?>
	
	<div id="panelgrid">
	<div>
		<fieldset>
			<legend><h3>Add New Track</h3></legend>
			<b>TBD</b>
		</fieldset>
		<fieldset>
			<legend><h3>Edit Track</h3></legend>
			<b>TBD</b>
		</fieldset>
		<fieldset>
			<legend><h3>Add New Project</h3></legend>
			<b>TBD</b>
		</fieldset>
		<fieldset>
			<legend><h3>Edit Project</h3></legend>
			<b>TBD</b>
		</fieldset>
	</div>
	<div>
		<fieldset>
			<legend><h3>Add New User</h3></legend>
			<form method="POST" action="/api.php?c=createaccount">
				<input class="input__text" type="text" placeholder="Username" name="name"/>
				<input class="input__text" type="text" placeholder="Hash of Password" name="passwd"/>
				<select class="input__select" required name="type">
					<option hidden selected value=""></option>
					<option>Admin</option>
					<option>User</option>
				</select>
				<br/>
				<br/>
				<input class="btn" type="submit"/>
			</form>
		</fieldset>
		<fieldset>
			<legend><h3>Remove User</h3></legend>
			<b>TBD</b>
		</fieldset>
	</div>
	</div>
	<?php genFooter(); ?>
</body>
</html>