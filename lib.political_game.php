<?
include_once("include/lib.all.php");

sc_add_menu_option("My Bullets","$RFS_SITE_URL/modules/bullet_log/bullet_log.php");

sc_access_method_add("bullet_log", "admin");

sc_database_add("rfsm_bullet_log","username","text","NOT NULL");
sc_database_add("rfsm_bullet_log","name","text","NOT NULL");
sc_database_add("rfsm_bullet_log","category","text","NOT NULL");
sc_database_add("rfsm_bullet_log","what","text","NOT NULL");
sc_database_add("rfsm_bullet_log","how","text","NOT NULL");
sc_database_add("rfsm_bullet_log","impact","text","NOT NULL");
sc_database_add("rfsm_bullet_log","other","text","NOT NULL");
sc_database_add("rfsm_bullet_log","when","timestamp"," ");
sc_database_add("rfsm_bullet_log","date","text"," ");
sc_database_add("rfsm_bullet_log","shared","text","NOT NULL");
sc_query("ALTER TABLE  `rfsm_bullet_log` CHANGE  `when`  `when` DATETIME ON UPDATE CURRENT_TIMESTAMP;");

sc_database_add("rfsm_bullet_category","name","text","NOT NULL");
sc_database_add("rfsm_bullet_category","image","text","NOT NULL");
sc_database_data_add("rfsm_bullet_category","name","Job Related",0);
sc_database_data_add("rfsm_bullet_category","name","Volunteer",0);
sc_database_data_add("rfsm_bullet_category","name","Self Improvement",0);

function sc_module_bullet_log($x) { eval(scg());
	
	echo "<h2>My Bullets</h2>";	
	
	if(!$_SESSION['logged_in']) {
		echo "Log in to view/edit bullets.<br>";
		
	}
	else {
	
		$r=sc_query("select * from `rfsm_bullet_log` where `username`='$data->name' order by `when` desc limit $x");
		$x=mysql_num_rows($r);
		if($x) {
			for($i=0;$i<$x;$i++) {
					$bullet=mysql_fetch_object($r);			
					if(!empty($bullet->name)) 
						echo "<a href=$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=edit_bullet&bid=$bullet->id>$bullet->name</a><br>";
			}		
		}
		else {
			echo "No bullets yet.<br>";
		}
		sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=edit_bullets","My Bullets");
		
	}
}

function sc_module_bullet_log_long($x) { eval(scg());
	$r=sc_query("select * from `rfsm_bullet_log` where `username`='$data->name' limit $x");
	$x=mysql_num_rows($r);
	echo "<h2>My Bullets</h2>";
	echo "<hr>";
	echo "$x total bullets<br>";

	echo "<table border=0 cellspacing=0 cellpadding=6>";
	echo "<tr>";
	echo "<td> Date </td>";
	echo "<td> Name </td>";
	echo "<td> What </td>";
	echo "<td> How </td>";
	echo "<td> Impact </td>";
	echo "<td> Category </td>";	
	echo "<td> Other </td>";	
	echo "<td> Shared by </td>";
	echo "</tr>";
	$gt=0;
	if($x) {
		for($i=0;$i<$x;$i++) {
				$bullet=mysql_fetch_object($r);
				
				if(!empty($bullet->name)) {
					$gt++; if($gt>1)$gt=0;
					echo "<tr>";
					
					echo "<td class=\"sc_file_table_$gt\">";
					echo "$bullet->date";
					echo "</td>";
					
					echo "<td class=\"sc_file_table_$gt\">";	
					echo "$bullet->name <br>";
					echo "</td>";
					
					
					echo "<td class=\"sc_file_table_$gt\">";
					echo "$bullet->what <br>";
					echo "</td>";
					
					echo "<td class=\"sc_file_table_$gt\">";
					echo "$bullet->how <br>";
					echo "</td>";
					
					echo "<td class=\"sc_file_table_$gt\">";
					echo "$bullet->impact <br>";
					echo "</td>";
					
					echo "<td class=\"sc_file_table_$gt\">";
					echo "$bullet->category <br>";
					echo "</td>";
					
					echo "<td class=\"sc_file_table_$gt\">";
					echo "$bullet->other <br>";
					echo "</td>";
					
					echo "<td class=\"sc_file_table_$gt\">";
					echo "$bullet->shared <br>";
					echo "</td>";
										
					echo "</tr>";
					
				}
		}
	}
	else {
		echo "No bullets yet.<br>";
	}
	echo "</table>";
	echo "<hr>";
	sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=edit_bullets","My Bullets");
}

?>

