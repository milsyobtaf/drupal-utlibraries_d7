<?php 
include("config.php");
include("functions.php");
include("login_test.php");

/* Filter Input */
if (! isset($_GET["ugl_serials_id"])) {
	exit();
} 
if (!is_numeric($_GET["ugl_serials_id"])) {
	exit();	
} 

$ugl_serials_id = $_GET["ugl_serials_id"];
$connection = connect();

//create the loc_code_array

$sql = "
	SELECT * 
	FROM ugl_serials
	WHERE ugl_serials_id = '$ugl_serials_id'
	";
	$result = @mysql_query($sql,$connection) or die ("whoops");
        while ($row = mysql_fetch_array($result)) {
        $ugl_serials_id = $row['ugl_serials_id'];
        $title = $row['title'];
        $issn = $row['issn'];
        $oclc_num = $row['oclc_num'];
        $kardex_note = $row['kardex_note'];
        $display_flag = $row['display_flag'];
                if ($display_flag == '0') {
                $display_toggle = "<a href=\"display_func.php?ugl_serials_id=$ugl_serials_id&display_flag=$display_flag\" class=\"modify\">display</a>";
                }
                if ($display_flag == '1') {
                $display_toggle = "<a href=\"display_func.php?ugl_serials_id=$ugl_serials_id&display_flag=$display_flag\" class=\"modify\">hide</a>";
                }
               $modify = "<a href=\"modify_full_rec.php?ugl_serials_id=$ugl_serials_id\" class=\"modify\">modify</a>";

        $sql = "
	    SELECT
            kardex_id,
	    received_date,
	    DATE_FORMAT(received_date, '%a, %b %e, %Y') as rcvd,
	    cover_date,
	    volume,
	    issue,
	    sort_index
            FROM
	    ugl_kardex
	    WHERE
	    ugl_serials_id = '$ugl_serials_id'
	    ORDER BY sort_index DESC, volume DESC, issue DESC 
	    ";
	$result3 = @mysql_query($sql,$connection) or die ("whoops3");
        while ($row = mysql_fetch_array($result3)) {
        $received_date = $row['received_date'];
        $kardex_id = $row['kardex_id'];
        $rcvd = $row['rcvd'];
        $cover_date = $row['cover_date'];
        $volume = $row['volume'];
        $issue = $row['issue'];
        $sort_index = $row['sort_index'];
	$holdings_row = 
	    "<tr>
	    <td></td>
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
            <td>
	    $sort_index
	    </td>
		<td>
		<a href=\"delete_issue_func.php?kardex_id=$kardex_id&ugl_serials_id=$ugl_serials_id\" class=\"modify\">delete</a>
		</td>
		</tr>";
	$holdings_rows .= "$holdings_row";
	}
	unset ($holdings_row);
	$today = date('Y-m-d');

		$kardex_display =
		"
		<table>
		<tr>
		<td></td>
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
		<td class=\"kardex_header\">
		Sort Index	
		</td>
		</tr>

		<tr>
		<form action=\"add_issue_func.php\" method=\"post\">
		<input type=\"hidden\" name=\"ugl_serials_id\"
		value=\"$ugl_serials_id\" />
		<td></td>
		<td>
		<input type=\"text\" size=\"9\" name=\"received_date\"
		value=\"$today\" />
		</td>
		<td>
		<input type=\"text\" size=\"12\" name=\"cover_date\" />
		</td>
		<td>
		<input type=\"text\" size=\"4\" name=\"volume\" />
		</td>
		<td>
		<input type=\"text\" size=\"4\" name=\"issue\" />
		</td>
		<td>
		<input type=\"text\" size=\"2\" name=\"sort_index\" />
		</td>
		<td>
		<input type=\"submit\" value=\"submit\" />
		</td>
		</form>
		</tr>
		$holdings_rows
		</table>
		";
	unset($holdings_rows);
	$all_kardex .= "$kardex_display";	

	}
	$title_link = "<a href=\"http://catalog.lib.utexas.edu/search/t?SEARCH=".urlencode($title)."\">".htmlentities($title, ENT_QUOTES, "iso-8859-1")."</a> | $display_toggle | $modify";
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
		<table cellpadding=\"5\" cellspacing=\"2\">
		<tr>
		<td class=\"header\">
		Title:
		</td>
		<td>
		$title_link 
		</td></tr>
		$issn_link
		$oclc_link
	<tr>
	<td class=\"header\">
	Kardex Note:
	</td>
	<td>
	$kardex_note
	</td>
	</tr>
		</table>
		</div>
		<div class=\"holdings\">
                $all_kardex
                </div>
		";
include("../header.php");
echo "
	$display_record
	<br />&nbsp;<br />
	<a href=\"$path/admin/staff_index.php\">Back to Complete List of Serials</a>
";

?>
