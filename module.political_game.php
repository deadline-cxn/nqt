<? include_once("include/lib.all.php");
///////////////////////////////////////////////////////////////
// Module Configuration
///////////////////////////////////////////////////////////////
// Register this module in the menu system
lib_menus_register("Political Game","$RFS_SITE_URL/modules/political_game/political_game.php");
///////////////////////////////////////////////////////////////
// Add security access methods for this module
// Examples:
// lib_access_add_method("name_of_module", "action1");
// lib_access_add_method("name_of_module", "action2");
lib_access_add_method("political_game","admin"); // this will allow site admin to assign this security level
lib_access_add_method("political_game","new_character");
///////////////////////////////////////////////////////////////
// Add database information for the module here
// lib_mysql_add("table_name","username_example","text","NOT NULL");
// lib_mysql_add("table_name","timestamp_example","timestamp"," ");
// lib_mysql_query("ALTER TABLE `table_name` CHANGE `when` `when` DATETIME ON UPDATE CURRENT_TIMESTAMP;");
// lib_mysql_data_add("table_name","name","data",0);
// Make a new column in a table, if the table does not exist
// a new table will be automatically created with an id column
lib_mysql_add("rfsm_political_game","owner","text","NOT NULL");
lib_mysql_add("rfsm_political_game","name","text","NOT NULL");
lib_mysql_add("rfsm_political_game","character","text","NOT NULL");
// Fill in some value to the database
lib_mysql_data_add("rfsm_political_game","name","Computer Player",0);
///////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////
// functions named module_x will be made available as a
// layout component in admin arrangement
function module_political_game($x) { eval(lib_rfs_get_globals());
	echo "<h2>Political Game</h2>";	
	if(!$_SESSION['logged_in']) {
		echo "Log in to play.<br>";
	}
	else {
		echo "<div> You have 0 shekels </div>";
		echo "<div> <a href=\"".$RFS_MENU_OPTION['Political Game']."\">Play</a> </div>";

	}
}


?>

