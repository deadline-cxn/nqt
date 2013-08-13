<?
$title="Political Game";
chdir("../../");
include("lilheader.php"); // lil header doesn't show every module and other information
echo "<img src=\"$RFS_SITE_URL/modules/political_game/pg.png\"><br>";
echo "<h1>The Political Game</h1>";

sc_do_action();

function political_game_buttons() { eval(scg());
/*	echo "<hr>";	
	sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=edit_bullets","My Bullets");
	sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=short_list","Brief List");
	sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=epr_format","Format for EPR");
	sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=send_report","Send Bullets to Supervisor");
	if(sc_access_check("bullet_log","admin")) {
		sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=edit_categories","Edit Categories");
	} */
}

function political_game_action_() { eval(scg());
	echo "<div> You have not selected any actions! </div> ";
}



?>

