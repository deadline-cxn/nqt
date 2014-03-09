<?
$title="Political Game";
chdir("../../");
$RFS_DO_NOT_SHOW_MENU=true;
include("lilheader.php"); // lil header doesn't show every module and other information
echo "<img src=\"$RFS_SITE_URL/modules/political_game/pg.png\"><br>";
echo "<h1>The Political Game</h1>";

lib_rfs_do_action();

function political_game_buttons() { eval(lib_rfs_get_globals());
/*	echo "<hr>";	
	lib_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=edit_bullets","My Bullets");
	lib_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=short_list","Brief List");
	lib_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=epr_format","Format for EPR");
	lib_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=send_report","Send Bullets to Supervisor");
	if(lib_access_check("bullet_log","admin")) {
		lib_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=edit_categories","Edit Categories");
	} */
}

function political_game_action_() { eval(lib_rfs_get_globals());

	if(!$_SESSION['logged_in']) {
		echo "Log in to play.<br>";
		echo "<table border=0><tr><td>";
		lib_rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
		echo "</td></tr></table>";
		return;
	}
	
	$r=lib_mysql_query("select * from rfsm_political_game where owner='$data->id'");
	$player=mysql_fetch_object($r);
	if(empty($player->character)) {
		echo "<div> You do not have a character set up.</div> ";
		lib_mysql_query("insert into rfsm_political_game (`owner`) values('$data->id')");
		$id=mysql_insert_id();
		lib_ajax("Character,80","rfsm_political_game","owner","$data->id","character",70,"","political_game","new_character","");
		
	}
	else {
		
		echo "<div> $player->character, you have not selected any actions! </div> ";
		
		?>
<style type="text/css">
      #map-canvas { height: 400px; width: 600px; }
    </style>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAWHdpkV1Mz394OUrB7gkA9Tk5tMmUyI2c&sensor=false">
    </script>



    <script type="text/javascript">
      function initialize() {
        
		  var mapOptions = {
          center: new google.maps.LatLng(-34.397, 150.644),
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
		
        var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
			
		var marker = new google.maps.Marker({
			position: map.getCenter(),
			map: map,
			title: 'Click to zoom'
			});
  

		google.maps.event.addListener(map, "click", function (e) { 
				// document.form1.waypointLog.value 
				document.getElementById("mapcoords").innerHTML = e.latLng.lat().toFixed(6) 
				+ ' |' + e.latLng.lng().toFixed(6); 
		}); 
	  }     
	  
	  google.maps.event.addDomListener(window, 'load', initialize);
	  
    </script>

<form id="form1">

	
     <div id="map-canvas"/>
	 
	 <div name="mapcoords" id="mapcoords" />
</form>
		<?
		
	}

	
	
}



?>

