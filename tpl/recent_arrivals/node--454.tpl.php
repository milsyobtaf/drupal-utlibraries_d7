<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <header>
    <?php print render($title_prefix); ?>
    <?php if (!$page): ?>
      <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>" rel="bookmark"><?php print $title; ?></a></h2>
    <?php endif; ?>
    <?php if ($region['group_nav'] = render($region['group_nav'])): ?>
      <div class="group-nav">
        <?php print $region['group_nav']; ?>
      </div>
    <?php endif; ?>
        <?php print render($title_suffix); ?>
  </header>

  <?php if ($region['sidebar_first'] = render($region['sidebar_first'])): ?>
    <aside class="sidebar sidebar-first">
      <?php print $region['sidebar_first']; ?>
    </aside>
  <?php endif; ?>

  <div class="content content-group recent-arrivals-full"<?php print $content_attributes; ?>>
  <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>

<?php

// Database Functions //
function ut_db_connect($db)
{
    $user = "utlol";
    $pass = "utlol";
    $host = ini_get("mysql.default_host");
    
    $db = strtoupper($db);
    $link = mysql_connect($host, $user, $pass)
    or die ("Could not connect to $host<br>\n");
    mysql_select_db($db, $link) 
        or die ("Could not select $db<br>\n");
    return $link;
}



function get_ra_session_query($location, $type, $language, $sort_by)
{	
	//Returns the results of the stored session query 
	//based on theme, exhibit and type
	$SITESESSNAME = "new_items";
    session_set_save_handler('ra_sess_open','ra_sess_close','ra_sess_read','ra_sess_write','ra_sess_destroy','ra_sess_gc');
    session_name($SITESESSNAME);
	session_start();
	
	$return_early = 0;
		
	if (! isset($_SESSION["session_query_var"])) {
		//No session variable ret. return.	
		return;
	}
	
	
	
	//Check theme, exhibit and type. Return if no match
	if ($_SESSION["session_query_var"]["location"] != $location) {
		$return_early = 1;	
	}
	if ($_SESSION["session_query_var"]["type"] != $type) {
		$return_early = 1;
	}
	if ($_SESSION["session_query_var"]["language"] != $language) {
		$return_early = 1;
	}
	if ($_SESSION["session_query_var"]["sort_by"] != $sort_by) {
		$return_early = 1;
	}

	if ($return_early) {
		//Start of a new query. Delete the old session query var if present
		if (isset($_SESSION["session_query_var"])) {
			unset($_SESSION["session_query_var"]);
		}
		session_destroy();
		return "";
	}
	
	//Get results
	$k = 0;
	$return_ary = "";
	while (isset($_SESSION["session_query_var"][$k])) {
		$return_ary[$k] = $_SESSION["session_query_var"][$k];
		$k++;
	}
	
	return($return_ary);
}

function set_ra_session_query($query_ary, $location, $type, $language, $sort_by)
{
	//Sets the session variable "session_query_var" to $query_ary
	if (! is_array($query_ary)) {
		return 0;
	}
	//Set the session array
	$SITESESSNAME = "new_items";
    session_set_save_handler('ra_sess_open','ra_sess_close','ra_sess_read','ra_sess_write','ra_sess_destroy','ra_sess_gc');
    session_name($SITESESSNAME);
	session_start();
	
	$count = count($query_ary);

	$_SESSION["session_query_var"]["type"] = $type;
	$_SESSION["session_query_var"]["language"] = $language;
	$_SESSION["session_query_var"]["location"] = $location;
	$_SESSION["session_query_var"]["sort_by"] = $sort_by;
	for ($i=0;$i<$count;$i++) {
		$_SESSION["session_query_var"][$i] = $query_ary[$i];		
	}
	if (isset($_SESSION["session_query_var"])) {
		return 1;
	} else {
		return 0;
	}
	
}

//Session Variables
$GLOBALS["session_db"] = "UTLOL";
$GLOBALS["sess_timeout"] = 3600; //timeout in 1 hour







function ra_sess_open($path,$name)
{
	global $sess_path, $sess_name;
	$sess_path = $path;
	$sess_name = $name;
	 _drupal_session_garbage_collection();
	
	return true;
}

function ra_sess_close()
{
	global $sess_path, $sess_name;
	 _drupal_session_garbage_collection();
	
	return true;
}

function ra_sess_read($sess_id)
{
	global $sess_path, $sess_name;
	
	$link = ut_db_connect($GLOBALS["session_db"]);
	$sql = "SELECT session_data FROM session 
			WHERE session_id='".$sess_id."' AND session_name='".$sess_name."'";
	$result = mysql_query($sql, $link);
	if (mysql_num_rows($result) == 1) {
		$row = mysql_fetch_row($result);
		$sessdata = $row[0];
		
		return $sessdata; 
	} else {
		$sql = "DELETE FROM session WHERE session_id='$sess_id'";
		mysql_query($sql, $link);
		$sql = "INSERT INTO session (session_data, session_name, session_id) VALUES ('', '$sess_name', '$sess_id')";
		mysql_query($sql, $link);
		
		return true;
	}
}

function ra_sess_write($sess_id,$sess_data)
{
	global $sess_path, $sess_name;
	
	$link = ut_db_connect($GLOBALS["session_db"]);
	$sql = "UPDATE session SET session_data='$sess_data' WHERE session_id='".$sess_id."'AND session_name='".$sess_name."'";
	mysql_query($sql, $link);
	return true;
}

function ra_sess_destroy($sess_id)
{
	global $sess_path, $sess_name;
	
	$link = ut_db_connect($GLOBALS["session_db"]);
	$sql = "DELETE FROM session 
	WHERE session_id='".$sess_id."' AND session_name='".$sess_name."'";
	mysql_query($sql,$link);
	
	return true;
}

function ra_sess_gc()
{
	global $sess_path, $sess_name;
	
	$link = ut_db_connect($GLOBALS["session_db"]);
	$current_time = time() - $GLOBALS["sess_timeout"];
	$now = date("Y-m-d H:i:s", $current_time);
	$sql = "DELETE FROM session WHERE last_modified <'".$now."' AND session_name='".$sess_name."'";
	mysql_query($sql,$link);
	
	return true;
}



/* 	#####################################################
	# name: 	recent_arrivals.php
	# input:	GET: location, start, language, sort_by, type,
	#			results_per_page, new items XML files
	# output:	recent arrivals viewer functionality based
	#			on branch
	# includes:	recent_arrivals_branch_config.php,
	#			recent_arrivals_branch.php
	#			recent_arrivals_branch_print.php
	#			normal_header.php
	#			normal_footer.php
	#			
	# description: Main script for all branches version of
	#			   recent_arrivals
	#
	#####################################################


#############
# Variables #
#############
*/

//ini_set('register_globals', 0);			//Make sure register globals is off
$default_location = "la";				//Default branch location
$this_script = "recent-arrivals"; 	//The name of this script
$display_filter = 0; 					// Set to 1 to display branch filter 
$display_filter_type = 1;     // Set to 1 to display type filter	

/*
########
# MAIN #
########
*/

$file_prefix = "/home/utlol/htdocs/lib-recentarrivals/";

$GLOBALS['image_dir'] = "/d7/sites/all/themes/utlibraries_d7/images/recent_arrivals/"; //Absolute to DOCUMENT_ROOT
$GLOBALS['rss_dir'] = "/recentarrivals/";	
$GLOBALS['xml_dir'] = $file_prefix."xml/";



/*
#############
# Variables #
#############
*/

//Some variables are located in recent_arrivals_config.php & including script
//ini_set('register_globals', 0);
$clean = array();	//array for filtered inputs
$html = array();	//array for escaped outputs
$record = array(); 	//individual record data

//Scripts allowed to Include recent_arrivals_branch.php
$allowed_scripts = array('recent-arrivals', 'fal_recent_arrivals.php');

//Defaults
$default_results_per_page = 10;
$default_sort_by = "date_added";
$default_language = "any";
$default_type = "any";
$default_display_filter = 1;
$primary_default_location = "fi";	

$sort_array = array();		//array for sorting records based on sory_by
$record_array = array();  	//array of records
$tmp_record_array = array();//sorting array for records
$results_per_page_array = array(10, 20, 50, 100);

//Exclude these branches from displaying in the filter options
$filter_exclude = array('gen', 'chn', 'cln', 'lagbr', 'lsn', 'pmn'); 

$sort_by_long['date_added'] = "Date Added";
$sort_by_long['title'] = "Title";
$sort_by_array = array_keys($sort_by_long);


$language_long['any'] = 'Any Language';
$language_long['ara'] = 'Arabic';
$language_long['chi'] = 'Chinese';
$language_long['eng'] = 'English';
$language_long['fre'] = 'French';
$language_long['ger'] = 'German';
$language_long['heb'] = 'Hebrew';
$language_long['hin'] = 'Hindi';
$language_long['ita'] = 'Italian';
$language_long['jpn'] = 'Japanese';
$language_long['lat'] = 'Latin';
$language_long['por'] = 'Portuguese';
$language_long['rus'] = 'Russian';
$language_long['spa'] = 'Spanish';
$language_long['---'] = '--------';
$language_long['afr'] = 'Afrikaans';
$language_long['arm'] = 'Armenian';
$language_long['asm'] = 'Assamese';
$language_long['aze'] = 'Azerbaijani';
$language_long['ben'] = 'Bengali';
$language_long['bho'] = 'Bhojpuri';
$language_long['bra'] = 'Braj';
$language_long['bul'] = 'Bulgarian';
$language_long['bel'] = 'Byelorussian';
$language_long['cat'] = 'Catalan';
$language_long['chu'] = 'Church Slavic';
$language_long['crp'] = 'Creoles/Pidgins';
$language_long['cze'] = 'Czech';
$language_long['dan'] = 'Danish';
$language_long['doi'] = 'Dogri';
$language_long['dut'] = 'Dutch';
$language_long['egy'] = 'Egyptian';
$language_long['enm'] = 'English, Middle';
$language_long['ang'] = 'English, Old';
$language_long['fin'] = 'Finnish';
$language_long['frm'] = 'French, Middle';
$language_long['fro'] = 'French, Old';
$language_long['glg'] = 'Galician';
$language_long['gmh'] = 'German, Middle High';
$language_long['grc'] = 'Greek, Ancient';
$language_long['gre'] = 'Greek, Modern';
$language_long['guj'] = 'Gujarati';
$language_long['hun'] = 'Hungarian';
$language_long['ice'] = 'Icelandic';
$language_long['inc'] = 'Indic';
$language_long['ind'] = 'Indonesian';
$language_long['gle'] = 'Irish';
$language_long['kan'] = 'Kannada';
$language_long['kor'] = 'Korean';
$language_long['mai'] = 'Maithili';
$language_long['may'] = 'Malay';
$language_long['mal'] = 'Malayalam';
$language_long['mni'] = 'Manipuri';
$language_long['mar'] = 'Marathi';
$language_long['myn'] = 'Mayan';
$language_long['nep'] = 'Nepali';
$language_long['new'] = 'Newari';
$language_long['nor'] = 'Norwegian';
$language_long['ori'] = 'Oriya';
$language_long['pli'] = 'Pali';
$language_long['pan'] = 'Panjabi';
$language_long['per'] = 'Persian';
$language_long['pol'] = 'Polish';
$language_long['pra'] = 'Prakrit';
$language_long['pus'] = 'Pushto';
$language_long['que'] = 'Quechua';
$language_long['raj'] = 'Rajasthani';
$language_long['roa'] = 'Romance';
$language_long['rum'] = 'Romanian';
$language_long['san'] = 'Sanskrit';
$language_long['gla'] = 'Scottish Gaelic';
$language_long['scc'] = 'Serbo-Croatian Cyrillic';
$language_long['scr'] = 'Serbo-Croatian Roman';
$language_long['snd'] = 'Sindhi';
$language_long['sin'] = 'Sinhalese';
$language_long['slo'] = 'Slovak';
$language_long['slv'] = 'Slovenian';
$language_long['sai'] = 'South American Indian';
$language_long['swa'] = 'Swahili';
$language_long['swe'] = 'Swedish';
$language_long['tam'] = 'Tamil';
$language_long['tel'] = 'Telugu';
$language_long['tur'] = 'Turkish';
$language_long['ota'] = 'Turkish Ottoman';
$language_long['ukr'] = 'Ukranian';
$language_long['urd'] = 'Urdu';
$language_long['wel'] = 'Welsh';
$language_long['yid'] = 'Yiddish';
$languages_array = array_keys($language_long);

$location_long['ar'] = 'Architecture Library';
$location_long['la'] = 'Benson Latin American Collection';
$location_long['lagbr'] = 'Benson Latin American Collection Browsing';
$location_long['ca'] = 'Center for American History';
$location_long['ch'] = 'Chemistry Library';
$location_long['chn'] = 'Chemistry New Books Collection';
$location_long['cl'] = 'Classics Library';
$location_long['cln'] = 'Classics New Books Collection';
$location_long['cd'] = 'Collections Deposit Library';
$location_long['in'] = 'Electronic Resource';
$location_long['en'] = 'Engineering Library';
$location_long['fi'] = 'Fine Arts Library';
$location_long['ge'] = 'Geology Library';
$location_long['gen'] = 'Geology New Books Collection';
$location_long['hn'] = 'Harry Ransom Center';
$location_long['ls'] = 'Life Science Library';
$location_long['lsn'] = 'Life Science New Books Collection';
$location_long['pcn'] = 'PCL New Books - Main Lobby';
$location_long['pc'] = 'PCL Stacks';
$location_long['pm']= 'Physics-Math-Astronomy Library';
$location_long['pmn']= 'Physics-Math-Astronomy New Books Collection';
$locations_array = array_keys($location_long);

$type_long['any'] = 'Any Type';
$type_long['a'] = 'Books &amp; Printed Materials';
$type_long['z'] = 'EBooks';
$type_long['l'] = 'DVDs';
$type_long['2'] = 'Videocassettes';
$type_long['h'] = 'Compact Discs';
$type_long['1'] = 'Records(LPs)';
$type_long['3'] = 'Audio Cassettes';
$type_long['j'] = 'Other Recorded Formats';
$type_long['i'] = 'Non-Music Recordings';
$type_long['c'] = 'Music Scores (Printed)';
$type_long['d'] = 'Music Scores (Manuscripts)';
$type_long['e'] = 'Maps &amp; Atlases';
$type_long['f'] = 'Maps (Manuscripts)';
$type_long['m'] = 'CD-ROMs, Computer Files';
$type_long['p'] = 'Archival Collections (Mixed)';
$type_long['t'] = 'Archival Collections (Manuscripts)';
$type_long['g'] = 'Films, Slides, etc.';
$type_long['k'] = 'Graphics, Charts, Art';
$type_long['r'] = 'Artifacts, Models, Realia';
$type_long['o'] = 'Kits';
$type_array = array_keys($type_long);

$type_icon['a'] = 'icon-books-printed-material.gif';
$type_icon['z'] = 'icon-eBook.gif ';
$type_icon['l'] = 'icon-dvd.gif';
$type_icon['2'] = 'icon-video-cassettes.gif';
$type_icon['h'] = 'icon-compact-disc.gif';
$type_icon['1'] = 'icon-record-LP.gif';
$type_icon['3'] = 'icon-audio-cassette.gif';
$type_icon['j'] = 'icon-other-recorded-formats.gif';
$type_icon['i'] = 'icon-recording-non-music.gif';
$type_icon['c'] = 'icon-music-scores-printed.gif';
$type_icon['d'] = 'icon-music-score-manuscripts.gif';
$type_icon['e'] = 'icon-maps-atlases.gif';
$type_icon['f'] = 'icon-maps-manuscripts.gif';
$type_icon['m'] = 'icon-cd-rom-computer-file.gif';
$type_icon['p'] = 'icon-archival-collection-mixed.gif';
$type_icon['t'] = 'icon-archival-collection-manuscript.gif';
$type_icon['g'] = 'icon-film-slides-etc.gif';
$type_icon['k'] = 'icon-graphics-charts-art.gif';
$type_icon['r'] = 'icon-artifacts-model-realia.gif';
$type_icon['o'] = 'icon-kits.gif';

$catalog_url = "http://catalog.lib.utexas.edu/search/?searchtype=.&searcharg=RECORD_ID&SORT=D&extended=0&SUBMIT=Search&searchlimits=&searchorigarg=.RECORD_ID";
$author_catalog_url = "http://catalog.lib.utexas.edu/search/a?SEARCH=AUTHOR&sortdropdown=-";


/*
########
# MAIN #
########
*/

//Input Filtering

//$this_script
if (isset($this_script)) {
	if (in_array($this_script, $allowed_scripts)) {
		$clean['this_script'] = $this_script;
	} else {
		exit("Error: Script Not Allowed");
	}
} else {
	$clean['this_script'] = "recent_arrivals.php";
}

//$display_filter
if (isset($display_filter)) {
	if(!($display_filter == 1 || $display_filter == 0)) {
		exit("Invalid Display Filter");
	}
} else {
	$display_filter = $default_display_filter;
}

//$default_location
if (isset($default_location)) {
	if(!in_array($default_location, $locations_array)) {
		exit("Invalid Default Location");
	}
} else {
	$default_location = $primary_default_location;
}

//$start
if (isset($_GET['start'])) {
	if($_GET['start'] == '') {
		$clean['start'] = 0;
	} else {
		if (!is_numeric($_GET['start'])) {
			exit();
		}
		if ($_GET['start'] < 0) {
			exit();
		}
		$clean['start'] = $_GET['start'];
	}
} else {
	$clean['start'] = 0;
}

//$location
if (isset($_GET['location'])) {
	if ($_GET['location'] == '') {
		$clean['location'] = $default_location;
	} else {
		$is_valid = 0;
		foreach($locations_array as $location_match) {
			if ($location_match == $_GET['location']) {
				$is_valid = 1;
			}
		}
		if (!$is_valid) {
			exit();
		} else {
			$clean['location'] = $_GET['location'];
		}
	}
} else {
	$clean['location'] = $default_location;
}

//$type
if (isset($_GET['type'])) {
	if ($_GET['type'] == '') {
		$clean['type'] = $default_type;
	} else {
		if (!in_array(strtolower($_GET['type']), $type_array)) {
			exit();
		} else {
			$clean['type'] = $_GET['type'];
		}
	}
} else {
	$clean['type'] = $default_type;;
}

//$results_per_page
if (isset($_GET['results_per_page'])) {
	if($_GET['results_per_page'] == '') {
		$clean['results_per_page'] = $default_results_per_page;
	}
	$is_valid = 0;
	foreach($results_per_page_array as $results_per_page_match) {
		if ($results_per_page_match == $_GET['results_per_page']) {
			$is_valid = 1;
		}
	}
	if (!is_numeric($_GET['results_per_page'])) {
		exit();
	}

	if (!$is_valid) {
		exit();
	} else {
		$clean['results_per_page'] = $_GET['results_per_page'];
	}
} else {
	$clean['results_per_page'] = $default_results_per_page;
}

//$language
if (isset($_GET['language'])) {
	if($_GET['language'] == '') {
		$clean['language'] = $default_language;
	} else {
		$is_valid = 0;
		foreach($languages_array as $language_match) {
			if ($language_match == $_GET['language']) {
				$is_valid = 1;
			}
		}
		if (!$is_valid) {
			exit();
		} else {
			$clean['language'] = $_GET['language'];
		}
	}
} else {
	$clean['language'] = $default_language;
}

//$sort_by
if (isset($_GET['sort_by'])) {
	if($_GET['sort_by'] == '') {
		$clean['sort_by'] = $default_sort_by;
	} else {
		$is_valid = 0;
		foreach($sort_by_array as $sort_by_match) {
			if ($sort_by_match == $_GET['sort_by']) {
				$is_valid = 1;
			}
		}
		if (!$is_valid) {
			exit();
		} else {
			$clean['sort_by'] = $_GET['sort_by'];
		}
	}
} else {
	$clean['sort_by'] = $default_sort_by;
}



//Check to see if the query exists in the session table
$sess_records = get_ra_session_query($clean['location'], $clean['type'], $clean['language'], $clean['sort_by']);

if (empty($sess_records)) {
	//Session is empty. Get records and put them in the records array

	//Open file based on location
	$new_items_xml = $GLOBALS['xml_dir'].$clean['location'].".xml";
	if (!file_exists($new_items_xml)) {
		$num_items = 0;
	} else {

		$xml = @simplexml_load_file($new_items_xml);

		if (!$xml) {
			exit("Error: Could not load file ".$new_items_xml."\n");
		}

		//Sort based on $sort_by
		foreach ($xml->record as $node) {
			$sort_value = "";
			$sort_record_id = "";
			$sort_title = "";
			$sort_cat_date = "";
			$sort_marc_245 = "";

			$sort_record_id = (string)$node->record_id;
			$sort_title = (string)$node->title;
			$sort_cat_date = (string)$node->cat_date;
			$sort_marc_245 = (string)$node->marc_245;

			//Format $sort_title to remove articles
			$sort_title = substr($sort_title, $sort_marc_245);

			if ($clean['sort_by'] == 'title') {
				$sort_value =  $sort_title.":".$sort_record_id;
			}
			if ($clean['sort_by'] == 'date_added') {
				//Reverse Date so year appears first for sorting
				$date_matches = array();
				if (preg_match("/(\d+)-(\d+)-(\d+)/", $sort_cat_date, $date_matches)) {
					$sort_cat_date = $date_matches[3].$date_matches[1].$date_matches[2];
				}
				$sort_value =  $sort_cat_date.":".$sort_record_id;
			}
			array_push($sort_array, $sort_value);
		}

		natcasesort($sort_array);

		if ($clean['sort_by'] == "date_added") {
			//Return Most Recent First
			$sort_array = array_reverse($sort_array);
		}

		foreach ($xml->record as $node) {
			$record['record_id'] = (string)$node->record_id;
			$record['title'] = (string)$node->title;
			$record['other_title'] = (string)$node->other_title;
			$record['marc_245'] = (string)$node->marc_245;
			$record['author'] = (string)$node->author;
			$record['cat_date'] = (string)$node->cat_date;
			$record['mat_type'] = (string)$node->mat_type;
			$record['oclc'] = (string)$node->oclc;
			$record['pub_info'] = (string)$node->pub_info;
			$record['isbn'] = (string)$node->isbn;
			$record['location'] = (string)$node->location;
			$record['lang'] = (string)$node->lang;

			//Check Type & Language
			if (($clean['type'] == 'any') || ($clean['type'] == $record['mat_type'])) {
				if (($clean['language'] == 'any') || ($clean['language'] == $record['lang'])) {
					$tmp_record_array[$record['record_id']] = $record;
				}
			}
		}

		//Sort $tmp_record_array
		foreach ($sort_array as $sort_value) {
			//Get the Record ID value and compare
			$tmp_sort_array = explode(":", $sort_value);
			$sort_id = array_pop($tmp_sort_array);
			if (isset($tmp_record_array[$sort_id])) {
				array_push($record_array, $tmp_record_array[$sort_id]);
			}
		}

	    set_ra_session_query($record_array, $clean['location'], $clean['type'], $clean['language'], $clean['sort_by']);
	}
} else {
	//Use the query stored in session table
	$record_array = $sess_records;
}

$num_items = count($record_array);
if ($clean['start'] > ($clean['results_per_page'] + $num_items)) {
	exit();
}


/*
##############
# Begin HTML #
##############
*/

print "
<div class=\"topnav\">
";

if ($num_items > 0) {
	print "<!-- # of results, page number -->
	<p>".$num_items." Results | Page ".ceil((($clean['start'] + 1)/$clean['results_per_page']))." of ".ceil($num_items/$clean['results_per_page'])."</p>";
	paginate($clean['start'], $num_items, $clean['this_script'], $clean['results_per_page'], $clean['type'], $clean['location'], $clean['language'], $clean['sort_by']);

//Display Type Filter
if ($display_filter_type) {
	print "
	<form action='".$clean['this_script']."' method='get'>
	<input type='hidden' name='sort_by' value='".$clean['sort_by']."'>
	<input type='hidden' name='results_per_page' value='".$clean['results_per_page']."'>
	<span class='filter-by-type'>
	<label for='type'>Material Type:</label>
	<select onchange='form.submit()' name='type' id='type'>";
	foreach ($type_array as $type) {
		if ($type == $clean['type']) {
			print "<option value='".$type."' SELECTED>".$type_long[$type]."</option>";
		} else {
			print "<option value='".$type."'>".$type_long[$type]."</option>";
		}
	}
	print "
	</select>
	</span>
	</form>";
}

	print "
	<!-- Limit results per page -->
  <form action='".$clean['this_script']."' method='get'>
 		<span class='results-per-page'><label for='results_per_page_top'>Results per page: </label>
  	<input type='hidden' name='start' value='".$clean['start']."'>
  	<input type='hidden' name='location' value='".$clean['location']."'>
  	<input type='hidden' name='type' value='".$clean['type']."'>
  	<input type='hidden' name='language' value='".$clean['language']."'>
  	<input type='hidden' name='sort_by' value='".$clean['sort_by']."'>
  	<select onchange='form.submit()' name='results_per_page' id='results_per_page_top'>";
  	foreach($results_per_page_array as $results_per_page_option) {
  		if ($results_per_page_option == $clean['results_per_page']) {
  			print "<option selected='selected' value=".$results_per_page_option.">".$results_per_page_option."</option>";
  		} else {
  			print "<option value=".$results_per_page_option.">".$results_per_page_option."</option>";
  		}
  	}
	print "
  	</select>
  	</span>
	</form>";
}

//Display Filter
if ($display_filter) {
	print "
	<form action='".$clean['this_script']."' method='get'>
	<input type='hidden' name='sort_by' value='".$clean['sort_by']."'>
	<input type='hidden' name='results_per_page' value='".$clean['results_per_page']."'>
	<div class='filter'>
	<div class='filterselect'>
	<label for='location'>Location:</label><br />
	<select name='location' id='location'>";
	foreach ($locations_array as $location) {
		if (!in_array($location, $filter_exclude)) {
			//Display only non-excluded location options
			if ($location == $clean['location']) {
				print "<option value='".$location."' SELECTED>".$location_long[$location]."</option>";
			} else {
				print "<option value='".$location."'>".$location_long[$location]."</option>";
			}
		}
	}
	print "
	</select>
	</div>
	<div class='filterselect'>
	<label for='type'>Type:</label><br />
	<select name='type' id='type'>";
	foreach ($type_array as $type) {
		if ($type == $clean['type']) {
			print "<option value='".$type."' SELECTED>".$type_long[$type]."</option>";
		} else {
			print "<option value='".$type."'>".$type_long[$type]."</option>";
		}
	}
	print "
	</select>
	</div>
	<div class='filterselect'>
	<label for='language'>Language:</label><br />
	<select name='language' id='language'>";
	foreach ($languages_array as $language_option) {
		if ($language_option == $clean['language']) {
			print "<option value='".$language_option."' SELECTED>".$language_long[$language_option]."</option>";
		} else {
			//Divider
			if ($language_option == '---') {
				print "<option disabled='disabled'>---------</option>";
			} else {
				print "<option value='".$language_option."'>".$language_long[$language_option]."</option>";
			}
		}
	}
	print "
	</select>
	</div>
	<p>
	<input type='submit' value ='Show Results'>
	</p>
	</div>
	</form>";
}

//Sort Options
print"
<!-- Sort options -->
<div class=\"sort\">
Sort by: ";
$sort_count = count(sort_ary);
$i = 0;
foreach ($sort_by_array as $sort_option) {
	if ($sort_option == $clean['sort_by']) {
		print "<span class=\"currentsort\">".$sort_by_long[$sort_option]."</span>";
	} else {
		print "<a href='".$clean['this_script']."?language=".$clean['language']."&sort_by=".$sort_option."&results_per_page=".$clean['results_per_page']."&start=".$clean['start']."&location=".$clean['location']."&type=".$clean['type']."'>".$sort_by_long[$sort_option]."</a>";
	}
	if ($i != $sort_count) {
		print " | ";
	}
	$i++;
}

//Results
if ($num_items == 0) {
	print "
	</div>
	</div>
	<!-- Results start here -->

	<p>We're sorry, there are no recent arrivals that meet all the requirements you selected.</p>

	<p>You can expand your results by including recent arrivals&hellip;</p>

	<ul>
	<li>of any type</li>
	<li>in any language</li>
	</ul>

	<p><a href='".$clean['this_script']."?start=&location=&type=&language=&sort_by=".$clean['sort_by']."&results_per_page=".$clean['results_per_page']."'>Start Over</a></p>
	<!--<div style=\"clear:left;\"></div>-->";

} else {

	print "
	</div>
	</div>
	<!-- Results start here -->
	<ul>";

	$i = $clean['start'];
	for ($j = 0;$j<$clean['results_per_page'];$j++) {
		if ($i >= $num_items) {
			break;
		}

		$record = $record_array[$i];

		if (isset($record['isbn'])) {
			$isbn = $record['isbn'];
			$isbn_ary = explode(" ", $isbn);
			$isbn = array_shift($isbn_ary);
		} else {
			$isbn = "";
		}

		$record_url = str_replace("RECORD_ID", $record['record_id'], $catalog_url);
		$author_url = str_replace("AUTHOR", urlencode($record['author']), $author_catalog_url);

		print "<!-- Individ result begin -->
		<li class=\"result\">
		<span class=\"icons\">
		<!-- Media type icon -->";
		if (isset($type_icon[$record['mat_type']])) {
			print "<img src=\"".$GLOBALS['image_dir']."media-types/".$type_icon[$record['mat_type']]."\" alt=\"Media Type: ".$type_long[$record['mat_type']]."\" /><br />";
		} else {
			//No Type Icon Defined
			print "&nbsp;";
		}
		print "
		</span>
		<span class=\"largeicons\">";

		//Format Title - Remove the '/(Author)' substring
		$title = $record['title'];
		$title_ary = explode("/", $title);
		$title = array_shift($title_ary);
		unset($title_ary);

		if (isset($record['other_title']) && (!empty($record['other_title']))) {
			$other_title = $record['other_title'];
			$other_title_ary = explode("/", $other_title);
			$other_title = array_shift($other_title_ary);
			unset($other_title_ary);
		}
		if ($isbn != "") {
			print "
			<!-- Cover Image from Amazon and Link to Catalog -->
			<a href='".$record_url."'><img src=\"http://www.lib.utexas.edu/amazon_check/image-test.php?isbn=".urlencode($isbn)."\" alt=\"".xml_decode($title)." class=\"bookcover\" /></a>";
			}

		print"
		</span>

		<!-- Title -->
		<span class=\"text\">
		<h4><a href='".$record_url."'>".xml_decode($title)."</a></h4>";
		if (isset($record['other_title']) && (!empty($record['other_title']))) {
			print "<h5>OTHER TITLE: <a href='".$record_url."'>".xml_decode($other_title)."</a></h5>";
		}
		print "
		</span>
		<!-- Meta -->
		<p class=\"meta-recentarrivals\"><a href='".$author_url."'>".xml_decode($record['author'])."</a><br />

		<!-- Location should be in darker span -->
		<span class=\"darker\">".$location_long[$clean['location']]."<br />
		</span>
		".$type_long[$record['mat_type']]."<br />";
		if (isset($record['lang'])) {
			print $language_long[$record['lang']]."<br />";
		}
		if (isset($record['pub_info'])) {
			print $record['pub_info']."<br /> ";
		}

		print "
		<!-- Date Added should be in darker span -->
		<span class=\"darker\">Added: ".$record['cat_date']."<br /></span>
		<span title=\".".$record['record_id']."\" class=\"majax-showholdings-div\">
		</span>
		</p>
		</span><!-- close text class -->
		</li>";
		$i++;
	}
	print"
	</ul>";
}


print "
<div class=\"bottomnav\">
";


if ($num_items > 0) {
	print "<!-- # of results, page number -->
	<p>".$num_items." Results | Page ".ceil((($clean['start'] + 1)/$clean['results_per_page']))." of ".ceil($num_items/$clean['results_per_page'])."</p>";
	paginate($clean['start'], $num_items, $clean['this_script'], $clean['results_per_page'], $clean['type'], $clean['location'], $clean['language'], $clean['sort_by']);

//Display Type Filter
if ($display_filter_type) {
	print "
	<form action='".$clean['this_script']."' method='get'>
	<input type='hidden' name='sort_by' value='".$clean['sort_by']."'>
	<input type='hidden' name='results_per_page' value='".$clean['results_per_page']."'>
	<span class='filter-by-type'>
	<label for='type'>Material Type:</label>
	<select onchange='form.submit()' name='type' id='type'>";
	foreach ($type_array as $type) {
		if ($type == $clean['type']) {
			print "<option value='".$type."' SELECTED>".$type_long[$type]."</option>";
		} else {
			print "<option value='".$type."'>".$type_long[$type]."</option>";
		}
	}
	print "
	</select>
	</span>
	</form>";
}

	print "
	<!-- Limit results per page -->
	<form action='".$clean['this_script']."' method='get'>
  	<span class='results-per-page'><label for='results_per_page_bottom'>Results per page: </label>
  	<input type='hidden' name='start' value='".$clean['start']."'>
  	<input type='hidden' name='location' value='".$clean['location']."'>
  	<input type='hidden' name='type' value='".$clean['type']."'>
  	<input type='hidden' name='language' value='".$clean['language']."'>
  	<input type='hidden' name='sort_by' value='".$clean['sort_by']."'>
  	<select onchange='form.submit()' name='results_per_page' id='results_per_page_bottom'>";
    foreach($results_per_page_array as $results_per_page_option) {
		  if ($results_per_page_option == $clean['results_per_page']) {
			  print "<option selected='selected' value=".$results_per_page_option.">".$results_per_page_option."</option>";
      } else {
			  print "<option value=".$results_per_page_option.">".$results_per_page_option."</option>";
		}
	}
	print "
  	</select>
    </span>
	</form>";
}

//Sort Options
print"
<!-- Sort options -->
<div class=\"sort\">
Sort by: ";
$sort_count = count(sort_ary);
$i = 0;
foreach ($sort_by_array as $sort_option) {
	if ($sort_option == $clean['sort_by']) {
		print "<span class=\"currentsort\">".$sort_by_long[$sort_option]."</span>";
	} else {
		print "<a href='".$clean['this_script']."?language=".$clean['language']."&sort_by=".$sort_option."&results_per_page=".$clean['results_per_page']."&start=".$clean['start']."&location=".$clean['location']."&type=".$clean['type']."'>".$sort_by_long[$sort_option]."</a>";
	}
	if ($i != $sort_count) {
		print " | ";
	}
	$i++;
}
print"
</div>
</div>";

/*  #############
 	# Functions #
	#############
*/

function paginate($start, $total, $this_script, $results_per_page, $type, $location, $language, $sort_by)
{

 	if ((!is_numeric($start)) || (!is_numeric($total)) || (!is_numeric($results_per_page))) {
		return;
	}

	if ($total <= $results_per_page) {
		//No need for pagination
		return;
	}

	if ($results_per_page == 0) {
		return;
	}

	if ($start > $total) {
		return;
	}

	// Set start to be divisible by results per page
	$start = $start - ($start % $results_per_page);

	//Current Page Number
	$current_page = ($start / $results_per_page) + 1;

	//Find last page of results to use for Last Page button
	$last_page_start = (ceil($total/$results_per_page) * $results_per_page) - $results_per_page;
	$last_page = ($last_page_start / $results_per_page) + 1;

	//Create String of Variables (less typing)
	$var_string = "&location=".$location."&type=".$type."&language=".$language."&sort_by=".$sort_by."&results_per_page=".$results_per_page;

	//Pagination output
	print "<!-- pagination -->
	<span class=\"pagination\">";

    /* Link to Previous */
    if ($current_page == 1) {
        print "<img src=\"".$GLOBALS['image_dir']."button-back.gif\" alt=\"Go to the previous page\"/>";
    } else {
    	$prev_start = $start - $results_per_page;
        print "<a href=\"$this_script?start=".$prev_start.$var_string."\">";
        print "<img src=\"".$GLOBALS['image_dir']."button-back.gif\" alt=\"Go to the previous page\"/></a>";
    }

	print "<span class=\"pagination-numbers\">";

    //Back to First Page - only displayed on page 4 and above
    if (($current_page >= 4) && ($last_page > 5)) {
        print "<a href=\"$this_script?start=0".$var_string."\">1</a><span class='ellipsis'>&hellip;</span> ";
    }

    //Middle Page numbers
	if (($current_page < 4) || ($last_page == 4)) {
    	//Print pages up to page 5 if they exist
    	$last = min($last_page, 5);
    	for ($i = 1; $i <= $last; $i++) {
    		if ($current_page == $i) {
    			print " <span id=\"currentpage\">".$i."</span> ";
    		} else {
    			$new_start = (($i - 1) * $results_per_page);
    			print " <a href=\"$this_script?start=".$new_start.$var_string."\">".$i."</a> ";
    		}
    	}
    } elseif ($current_page >= ($last_page - 3)) {
    	$lowest = $last_page - 4;
    	for ($i = $lowest; $i <= $last_page; $i++) {
    		if ($current_page == $i) {
    			print " <span id=\"currentpage\">".$i."</span> ";
    		} else {
    			$new_start = (($i - 1) * $results_per_page);
    			print " <a href=\"$this_script?start=".$new_start.$var_string."\">".$i."</a> ";
    		}
    	}
	} else {
    	$lowest = $current_page - 2;
    	$highest = $current_page + 2;
    	for ($i = $lowest; $i <= $highest; $i++) {
    		if ($current_page == $i) {
    			print " <span id=\"currentpage\">".$i."</span> ";
    		} else {
    			$new_start = (($i - 1) * $results_per_page);
    			print " <a href=\"$this_script?start=".$new_start.$var_string."\">".$i."</a> ";
    		}
    	}
	}

    //Forward to Last Page, only shown if more than 5 pages total and more than 4 pages until last page
	if ($last_page > 5) {
		if (($last_page == 6) || ($last_page == 7)) {
			if ($current_page < 4) {
    			print " <a href=\"$this_script?start=".$last_page_start.$var_string;
        		print "\">..".$last_page."</a> ";
    		}
    	} elseif ($current_page < $last_page - 3) {
        	print "<span class='ellipsis'>&hellip;</span><a href=\"$this_script?start=".$last_page_start.$var_string;
        	print "\">".$last_page."</a> ";
    	}
	}
    print "\n</span>"; //Page Numbers Span

    //Next Page(s)
    if ($current_page == $last_page) {
        print " <img src=\"".$GLOBALS['image_dir']."button-forward.gif\" alt=\"Go to the next page\"/>";
    } else {
    	$next_start = $start + $results_per_page;
        print " <a href=\"".$this_script."?start=".$next_start.$var_string."\">";
        print "<img src=\"".$GLOBALS['image_dir']."button-forward.gif\" alt=\"Go to the next page\"/></a>";
    }

    print "\n</span>"; //Pagination Span

}

function xml_decode($value)
{
	$value = trim($value);
	$value = preg_replace("/&quot;/", "\"", $value);
    $value = preg_replace("/&lt;/", "<", $value);
    $value = preg_replace("/&gt;/", ">", $value);
    $value = preg_replace("/&apos;/", "'", $value);
    $value = preg_replace("/&amp;/", "&", $value);

    return $value;
}



?>


    <div class="taxonomy"><?php print $terms?></div>
    <?php if ($links) { ?><div class="links">&raquo; <?php print $links?></div><?php }; ?>
  </div>
  <?php if ($region['sidebar_second'] = render($region['sidebar_second'])): ?>
    <aside class="sidebar sidebar-second">
      <?php print $region['sidebar_second']; ?>
    </aside>
  <?php endif; ?>

  </div>
</article>