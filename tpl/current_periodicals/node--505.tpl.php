<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

<!-- I hate having to do this, but for now I do to get the Back link to work -->

<?php $field_url_short_title = 'fal'; ?>

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

  <div class="content content-group current-periodicals current-periodicals-detail"<?php print $content_attributes; ?>>
  <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>

  <?php 
    include("admin/config.php");
    include("admin/functions.php");
    $connection = connect();
    
    //Filter Input
    $clean = array();
    if (is_numeric($_GET["ugl_serials_id"])) {
        $clean["ugl_serials_id"] = $_GET["ugl_serials_id"];
    } else {
    	die("Fatal Error");
    }
    
    
    //create the loc_code_array
    $sql = "
    	SELECT * 
    	FROM ugl_serials
    	WHERE ugl_serials_id = ".$clean["ugl_serials_id"];
    	
    	$result = @mysql_query($sql,$connection) or die ("whoops");
            while ($row = mysql_fetch_array($result)) {
            $title = $row['title'];
            $issn = $row['issn'];
            $oclc_num = $row['oclc_num'];
            $kardex_note = $row['kardex_note'];
            $display_flag = $row['display_flag'];
    
            $sql = "
    	    SELECT
    	    received_date,
    	    DATE_FORMAT(received_date, '%a, %b %e, %Y') as rcvd,
    	    cover_date,
    	    volume,
    	    issue
                FROM
    	    ugl_kardex
    	    WHERE ugl_serials_id = ".$clean["ugl_serials_id"]."
    	    ORDER BY sort_index DESC, volume DESC, issue DESC
    	    ";
    	$result3 = @mysql_query($sql,$connection) or die ("whoops3");
            while ($row = mysql_fetch_array($result3)) {
            $received_date = $row['received_date'];
            $rcvd = $row['rcvd'];
            $cover_date = $row['cover_date'];
            $volume = $row['volume'];
            $issue = $row['issue'];
    	$holdings_row = 
    	    "<tr>
    	    <td>
    	    $rcvd
    	    </td><td>
    	    $cover_date
    	    </td><td>
    	    $volume
    	    </td>
                <td>
    	    $issue
    	    </td>
    		</tr>";
    	$holdings_rows .= "$holdings_row";
    	}
    	unset ($holdings_row);
    
    		$kardex_display =
    		"
    		<table>
    		<tr>
    		<td class=\"kardex_header\">
    		Received Date	
    		</td>
    		<td class=\"kardex_header\">
    		Cover Date	
    		</td>
    		<td class=\"kardex_header\">
    		Volume	
    		</td>
    		<td class=\"kardex_header\">
    		Issue	
    		</td>
    		</tr>
    		$holdings_rows
    		</table>
    		";
    	unset($holdings_rows);
    	$all_kardex .= "$kardex_display";	
    
    	}
    	$title_link = "<a href=\"http://catalog.lib.utexas.edu/search/t?SEARCH=".urlencode($title)."\">".htmlentities($title, ENT_QUOTES, "iso-8859-1")."</a>";
    	
    	if ($issn) {
    	
    		$issn_link = 
    		"<tr>
    		<td class=\"header\">
    		ISSN:
    		</td>
    		<td>
    		<a href=\"http://catalog.lib.utexas.edu/search/i?SEARCH=".urlencode($issn)."\">".htmlentities($issn, ENT_QUOTES, "iso-8859-1")."</a>
    		</td></tr>";
    	}
    	
    	if ($oclc_num) {
    		$oclc_link = "
    		<tr>
    		<td class=\"header\">
    		OCLC Number:	
    		</td>
    		<td>
    		<a href=\"http://catalog.lib.utexas.edu/search/o?SEARCH=".urlencode($oclc_num)."\">".htmlentities($oclc_num, ENT_QUOTES, "iso-8859-1")."</a>
    		</td></tr>";
    	}
    		
    	$display_record = "
    	<div class=\"record\">
    	<table>
    	<tr>
    	<td class=\"header\">
    	Title:
    	</td>
    	<td>
    	$title_link 
    	</td></tr>
    	$add_title_display
    	$continues_display
    	$cont_by_display
    	$subject_display
    	$issn_link
    	$oclc_link
    	</table>
    	</div>
    	<div class=\"holdings\">$all_kardex</div>
    		";
    //include("header.php");
    print $display_record . 
    "<p>
    	  <a href=\"/d7/" . $field_url_short_title . "/about/holdings/currentperiodicals/\">Back to Complete List of Current Periodicals</a>
    </p>";
    ?>

  </div>
  <?php if ($region['sidebar_second'] = render($region['sidebar_second'])): ?>
    <aside class="sidebar sidebar-second">
      <?php print $region['sidebar_second']; ?>
    </aside>
  <?php endif; ?>

  </div>
</article>