<?php
	include("../befuncs/snips.php");
	include("../befuncs/coding.php");
	include("../befuncs/db_coding.php");
	genUsual('Riedler\'s Coding Projects','@import "../style/coding.css"','');
?>
<body>
	<?php genNavBar(); ?>
	<h1>Riedler's Coding Projects</h1>
	<fieldset>
		<legend><h3>Support me!</h3></legend>
		<?php genHeBiLink(rwicon("lp"),"Liberapay","https://liberapay.com/Riedler"); ?>
		and
		<?php genHeBiLink(rwicon("pt"),"Patreon","https://patreon.com/RiedlerM"); ?>
		are ways to give me money, so I can contribute to Open-Source projects without worrying about monetary issues.
	</fieldset>
	<fieldset>
		<legend><h3>Services</h3></legend>
		<?php
			services_as_html(db_get_services());
		?>
	</fieldset>
	<div class="projectlist">
		<?php
			$lastdead=false;
			foreach(db_get_projects() as $project){
				if(!$lastdead && $project['status']=='Dead'){
					echo '</div><hr><div class="projectlist">';
					$lastdead=true;
				}
				echo '<div class="project';
				if($project['status']=='Dead'){
					echo ' dead';
				}
				echo '">'.
					 '<div class="projectheader">'.
					 "<h3>${project['name']}</h3>".
					 "<span class='pdate'>${project['mydate']}</span>".
					 '<span class="plinks">';
				foreach(db_get_links_by_pid($project['id']) as $service){
					genImgLink(rwicon($service['abbr']),complete_link_protocol($service['link']),$service['name']);
				}
				echo '</span>'.
					 "<span class='pstatus'>${project['status']}</span>".
					 '</div>'.
					 "<p>${project['shortdesc']}</p>".
					 '</div>';
			}
		?>
	</div>
	<?php genFooter(); ?>
</body>
</html>