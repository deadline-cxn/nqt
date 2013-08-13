<? include_once("include/lib.all.php");
///////////////////////////////////////////////////////////////
// Module Configuration
///////////////////////////////////////////////////////////////
// Register this module in the menu system
sc_menus_register("Political Game","$RFS_SITE_URL/modules/political_game/political_game.php");
///////////////////////////////////////////////////////////////
// Add security access methods for this module
// Examples:
// sc_access_method_add("name_of_module", "action1");
// sc_access_method_add("name_of_module", "action2");
sc_access_method_add("political_game","admin"); // this will allow site admin to assign this security level
///////////////////////////////////////////////////////////////
// Add database information for the module here
// sc_database_add("table_name","username_example","text","NOT NULL");
// sc_database_add("table_name","timestamp_example","timestamp"," ");
// sc_query("ALTER TABLE `table_name` CHANGE `when` `when` DATETIME ON UPDATE CURRENT_TIMESTAMP;");
// sc_database_data_add("table_name","name","data",0);
// Make a new column in a table, if the table does not exist
// a new table will be automatically created with an id column
sc_database_add("rfsm_political_game","name","text","NOT NULL");
// Fill in some value to the database
sc_database_data_add("rfsm_political_game","name","Computer Player",0);
///////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////
// functions named sc_module_x will be made available as a
// layout component in admin arrangement
function sc_module_political_game($x) { eval(scg());
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

