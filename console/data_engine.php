<?PHP
/**
Copyright (C) 2008 ionix Limited
http://www.ionix.ltd.uk/

This script was written by ionix Limited, and was distributed
via the OpenCrypt.com Blog.

AJAX data listings engine with PHP and mySQL (BETA)
http://www.OpenCrypt.com/blog.php?a=22

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License as
published by the Free Software Foundation; either version 2 of
the License, or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

GNU GPL License
http://www.opensource.org/licenses/gpl-license.php
*/


ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 'Off');
#ini_set('log_errors', 'On');
#ini_set('error_log', 'C:\XAMPP\xampp\htdocs\i\errors.log');

if (!isset($global)) {

	print "\$global configuration array not set!  See <a href=\"http://www.OpenCrypt.com/blog.php?a=22\">original article</a>.";

}

function data_engine_select($conf_table, $conf_columns, $conf_headers = "1", $conf_rows = "", $conf_order = "", $conf_edit = "0", $conf_query = "", $conf_header = "", $conf_footer = "", $conf_buttons = "") {

# table: mySQL table name
# columns: field names/mySQL colum names to display (in table), seperate with pipe delimeter, field1|field2|field4
# headers: 0 = no column names displayed, 1 = column names are displayed
# rows to display: number of rows to return, and whether to enable pages.  e.g. 20, would list 20 results.  20|1 would list 20 + other pages.  blank or 0/default is all results, and no pages (max 999999 results).
# default order = order of results, field by desc/asc.  e.g. FIELDNAME|DESC
# edit = position|type, type 0 = no, 1 = radio, 2 = checkbox. position 0 = default, on left, 1 = on right
# query = custom mySQL query.  after the WHERE.  e.g. FIELDNAME = 'keyword', the WHERE is inserted automatically, incase an AND is required
# header = places input (html or text) after header names
# footer = places input (html or text) after rows, before page links.
# button = places input (html or text) in bottom left, instead of 'Page 1 of 2' text.

global $global;
global $field;
global $text;
global $input;

$global['queries'] = "0";
$global['query_log'] = "";

$global['envself'] = $_SERVER['PHP_SELF'];
$global['envip'] = $_SERVER['REMOTE_ADDR'];

$input['get_action'] = "";
$input['post_action'] = "";
if (isset($_GET["a"])) {
	$input['get_action'] = $_GET["a"];
}
if (isset($_POST["a"])) {
	$input['post_action'] = $_POST["a"];
}
if ("$input[get_action]"=="") {
	$input['get_action'] = $input['post_action'];
}
$field['get_action'] = mysql_escape_string($input['get_action']);
$field['post_action'] = mysql_escape_string($input['post_action']);

$input['get_ajax'] = "";
$input['post_ajax'] = "";
if (isset($_GET["ajax"])) {
	$input['get_ajax'] = $_GET["ajax"];
}
if (isset($_POST["ajax"])) {
	$input['post_ajax'] = $_POST["ajax"];
}
if ("$input[get_ajax]"=="") {
	$input['get_ajax'] = $input['post_ajax'];
}
$field['get_ajax'] = mysql_escape_string($input['get_ajax']);
$field['post_ajax'] = mysql_escape_string($input['post_ajax']);

if (!isset($global['header_color'])) {
	$global['header_color'] = "ffffff"; # header color
}
if (!isset($global['header_selected_color'])) {
	$global['header_selected_color'] = "eeeeee"; # selected header color
}
if (!isset($global['line_color'])) {
	$global['line_color'] = "dddddd";
}
if (!isset($global['row_color1'])) {
	$global['row_color1'] = "ffffff"; # row color
}
if (!isset($global['row_color2'])) {
	$global['row_color2'] = "f7f7f7"; # row color
}
if (!isset($global['row_color_selected'])) {
	$global['row_color_selected'] = "FBFBC4"; # row color
}
if (!isset($global['row_color_mouseover'])) {
	$global['row_color_mouseover'] = "F5F5BA";
}
if (!isset($global['page_box_color'])) {
	$global['page_box_color'] = "f7f7f7";
}
if (!isset($global['page_box_border'])) {
	$global['page_box_border'] = "000080";
}

$global['header_color'] = "#".$global['header_color'];
$global['header_selected_color'] = "#".$global['header_selected_color'];
$global['row_colora1'] = $global['row_color1'];
$global['row_color1'] = "#".$global['row_color1'];
$global['row_colora2'] = $global['row_color2'];
$global['row_color2'] = "#".$global['row_color2'];
$global['row_colora_selected'] = $global['row_color_selected'];
$global['row_color_selected'] = "#".$global['row_color_selected'];
$global['line_color'] = "#".$global['line_color'];
$global['page_box_color'] = "#".$global['page_box_color'];
$global['page_box_border'] = "#".$global['page_box_border'];


if ("$conf_rows"=="") {

	$conf_rows = "999999|0";

}

$conf_rows = explode("|",$conf_rows);
$conf_rows_count = count($conf_rows);
$conf_order = explode("|",$conf_order);
$conf_order_count = count($conf_order);
$conf_edit = explode("|",$conf_edit);

if (isset($_GET["o_b"])) {
	$input['order_by'] = mysql_escape_string($_GET["o_b"]);
} else {
	$input['order_by'] = "";
}
if (isset($_GET["o_d"])) {
	$input['order_direction'] = mysql_escape_string($_GET["o_d"]);
} else {
	$input['order_direction'] = "";
}
if (isset($_GET["p"])) {
	$input['records_page'] = mysql_escape_string($_GET["p"]);
} else {
	$input['records_page'] = "";
}
if (isset($_GET["data_search"])) {
	$input['data_search'] = mysql_escape_string($_GET["data_search"]);
} else {
	$input['data_search'] = "";
}
if (isset($_GET["s_f"])) {
	$input['search_field'] = mysql_escape_string($_GET["s_f"]);
} else {
	$input['search_field'] = "";
}
if (isset($_GET["ajax"])) {
	$input['print_ajax'] = mysql_escape_string($_GET["ajax"]);
} else {
	$input['print_ajax'] = "";
}

$field['data_query'] = "SELECT ";
$field['search_query'] = "";
$field['search_menu'] = "";

$field['search_menu'] .= "<option value=\"0\"";

if ("$input[search_field]"=="0") {

	$field['search_menu'] .= " selected";

}

$field['search_menu'] .= ">All Fields";


$field['output_noscript'] = "<form action=\"$global[envself]\" method=\"GET\" name=\"form\" style=\"margin:0px\">
<table width=\"100%\" border=0 cellpadding=4 cellspacing=0>
";
$field['output_script'] = "<form action=\"$global[envself]\" method=\"GET\" name=\"form\" style=\"margin:0px\">
<table width=\"100%\" border=0 cellpadding=4 cellspacing=0>
";

if ("$conf_columns"!="") {

	$conf_columns = explode("|",$conf_columns);
	$conf_columns_count = count($conf_columns);

	if ("$conf_headers"=="1") {

		$field['output_noscript'] .= "<tr>";
		$field['output_script'] .= "<tr>";

		if ("$conf_edit[0]"=="1") {

			if ("$conf_edit[1]"=="2") {

				$field['output_noscript'] .= "<td bgcolor=\"$global[header_color]\"><input type=\"checkbox\" name=\"CheckAll\" value=\"\" onClick=\"checkAll(document.form.data_input,this)\"></td>
				";
				$field['output_script'] .= "<td bgcolor=\"$global[header_color]\"><input type=\"checkbox\" name=\"CheckAll\" value=\"\" onClick=\"checkAll(document.form.data_input,this)\"></td>
				";

			} else {

				$field['output_noscript'] .= "<td bgcolor=\"$global[header_color]\"><span class=\"field\">&nbsp;</span></td>
				";
				$field['output_script'] .= "<td bgcolor=\"$global[header_color]\"><span class=\"field\">&nbsp;</span></td>
				";

			}
		}
	}

	for ($i = 0; $i < $conf_columns_count; $i++) {

		if ("$input[order_by]"=="$conf_columns[$i]") {

			$conf_order[0] = $conf_columns[$i];
			$conf_order[1] = $input['order_direction'];

		}

		if ("$i"!="0") {

			$field['data_query'] .= ", ";

			if ("$input[search_field]"=="0") {

				if ("$input[data_search]"!="") {

					$field['search_query'] .= "	OR ";

				}
			}
		}

		$field['data_query'] .= "$conf_columns[$i]";

		if ("$input[search_field]"=="0") {

			if ("$input[data_search]"!="") {

				$field['search_query'] .= "$conf_columns[$i] LIKE '%$input[data_search]%'";

			}

		} else {

			if ("$input[search_field]"=="$conf_columns[$i]") {

				if ("$input[data_search]"!="") {

					$field['search_query'] .= "$conf_columns[$i] LIKE '%$input[data_search]%'";

				}
			}
		}

		$field['search_menu'] .= "<option value=\"$conf_columns[$i]\"";

		if ("$input[search_field]"=="$conf_columns[$i]") {

			$field['search_menu'] .= " selected";

		}

		$field['search_menu'] .= ">".ucwords(str_replace("_"," ",$conf_columns[$i]));

		$field['order_direction'] = "ASC";

		if ("$input[order_by]"=="$conf_columns[$i]") {

			if ("$input[order_direction]"=="ASC") {

				$field['order_direction'] = "DESC";

			} else {

				$field['order_direction'] = "ASC";

			}

		}

		if ("$conf_headers"=="1") {

			if ("$input[order_by]"=="$conf_columns[$i]") {

				$field['order_bgcolor'] = $global['header_selected_color'];

			} else {

				$field['order_bgcolor'] = $global['header_color'];

			}

			$field['output_noscript'] .= "<td nowrap bgcolor=\"$field[order_bgcolor]\"><span class=\"field\">&nbsp;<b><a href=\"$global[envself]?o_b=$conf_columns[$i]&o_d=$field[order_direction]&p=$input[records_page]&s_f=$input[search_field]&data_search=$input[data_search]\" class=\"field\">";
			$field['output_noscript'] .= ucwords(str_replace("_"," ",$conf_columns[$i]));
			$field['output_noscript'] .= "</a></b>";

			$field['output_script'] .= "<td nowrap bgcolor=\"$field[order_bgcolor]\"><span class=\"field\">&nbsp;<b><a href=\"javascript:;\" onclick=\"SimpleAJAXCall('$global[envself]?o_b=$conf_columns[$i]&o_d=$field[order_direction]&p=$input[records_page]&s_f=$input[search_field]&data_search=$input[data_search]&ajax=1',SimpleAJAXCallback, '', 'data_listings');\" class=\"field\">";

			$field['output_script'] .= ucwords(str_replace("_"," ",$conf_columns[$i]));
			$field['output_script'] .= "</a></b>";


			if ("$input[order_by]"=="$conf_columns[$i]") {

				if ("$input[order_direction]"=="ASC") {

					$field['output_noscript'] .= "&nbsp;<img src=\"$global[url_images]/asc.gif\">";
					$field['output_script'] .= "&nbsp;<img src=\"$global[url_images]/asc.gif\">";

				} else {

					$field['output_noscript'] .= "&nbsp;<img src=\"$global[url_images]/dsc.gif\">";
					$field['output_script'] .= "&nbsp;<img src=\"$global[url_images]/dsc.gif\">";

				}
			}

			$field['output_noscript'] .= "</span></td>
			";
			$field['output_script'] .= "</span></td>
			";

		}

	}

	if ("$conf_headers"=="1") {

		if ("$conf_edit[0]"=="2") {

			if ("$conf_edit[1]"=="2") {

				$field['output_noscript'] .= "<td bgcolor=\"$global[header_color]\" align=right><input type=\"checkbox\" name=\"CheckAll\" value=\"\" onClick=\"checkAll(document.form.data_input,this)\"></td>
				";
				$field['output_script'] .= "<td bgcolor=\"$global[header_color]\" align=right><input type=\"checkbox\" name=\"CheckAll\" value=\"\" onClick=\"checkAll(document.form.data_input,this)\"></td>
				";

			} else {

				$field['output_noscript'] .= "<td bgcolor=\"$global[header_color]\"><span class=\"field\">&nbsp;</span></td>
				";
				$field['output_script'] .= "<td bgcolor=\"$global[header_color]\"><span class=\"field\">&nbsp;</span></td>
				";

			}
		}

		$field['output_noscript'] .= "</tr>
		";
		$field['output_script'] .= "</tr>
		";

	}

} else {

	return "No mySQL columns specified for data listings.";

}

if ("$conf_table"!="") {

	$field['data_query'] .= " FROM $conf_table";

} else {

	return "No mySQL table specified for data listings.";

}

if (("$conf_query"!="") && ("$field[search_query]"!="")) {

	$field['data_query'] .= " WHERE $conf_query AND $field[search_query]";

} elseif ("$conf_query"!="") {

	$field['data_query'] .= " WHERE $conf_query";

} elseif ("$field[search_query]"!="") {

	$field['data_query'] .= " WHERE $field[search_query]";

}

if ("$conf_order[0]"!="") {

	$field['data_query'] .= " ORDER BY $conf_order[0] $conf_order[1]";

}

$field['data_query'] .= ";";

$data = "";

database($field['data_query']);

if ("$input[records_page]"!="") {

} else {

	$input['records_page'] = "0";

}

$field['total_pages'] = ceil($global['dbnumber']/$conf_rows[0]);
$field['total_pages'] = round($field['total_pages']);
$field['records_start'] = $input['records_page']*$conf_rows[0];
$field['records_finish'] = $field['records_start']+$conf_rows[0];







##### HEADER AND SEARCH MENU LAYOUT #####



$field['output_noscript'] = <<<END
<span class="header">$conf_table</span><br>
<span class="sub_header">$global[dbnumber] Records</span>
<p>
<form action="$global[envself]" name="search_form" method="GET" style="margin:0px;">
<input type="hidden" name="o_b" value="$input[order_by]">
<input type="hidden" name="o_d" value="$input[order_direction]">
<span class="search">Search: <select name="s_f" class="input" style="font-size:8pt;">$field[search_menu]</select> <input type="text" name="data_search" size=20 value="$input[data_search]" class="input" style="font-size:8pt;"> <input type="submit" name="submit" value="Search" class="input" style="font-size:8pt;"></span>
</form>
<br>&nbsp;
<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td bgcolor="$global[line_color]" height="1"></td></tr></table>
$conf_header
$field[output_noscript]
END;

$field['output_script'] = <<<END
&nbsp;<br>
<table cellpadding=0 cellspacing=0 border=0>
<tr><td width=25 valign=top>
<div id="showimg"></div>
</td><td valign=top>
<span class="header">$conf_table</span>
<br>
<span class="sub_header">$global[dbnumber] Records</span>
<p>
<form action="$global[envself]" name="asearch_form" method="GET" style="margin:0px;">
<input type="hidden" name="o_b" value="$input[order_by]">
<input type="hidden" name="o_d" value="$input[order_direction]">
<span class="search">Search: <select name="s_f" class="input" style="font-size:8pt;">$field[search_menu]</select> <input type="text" name="data_search" size=20 value="$input[data_search]" class="input" style="font-size:8pt;">
<input type="submit" name="submit" value="Search" class="input" style="font-size:8pt;">
</form>
</td></tr></table>
<br>
<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td bgcolor="$global[line_color]" height="1"></td></tr></table>
$conf_header
$field[output_script]
END;

if (!isset($field['row_color'])) {
	$field['row_color'] = "";
}

for ($i = 0; $i < $global['dbnumber']; $i++) {

	if ("$field[row_color]"=="$global[row_color2]") {

		$field['row_color'] = $global['row_color1'];
		$field['row_colora'] = $global['row_colora1'];

	} else {

		$field['row_color'] = $global['row_color2'];
		$field['row_colora'] = $global['row_colora2'];

	}

	if (($i>=$field['records_start']) && ($i<$field['records_finish'])) {

	$field['output_noscript'] .= "<tr id=\"row$i\" bgcolor=\"$field[row_color]\" onmouseover=\"checktoggle_over('input_row$i', 'row$i','$global[row_color_mouseover]');\" onmouseout=\"checktoggle('input_row$i', 'row$i','$field[row_colora]');\">
	";
	$field['output_script'] .= "<tr id=\"row$i\" bgcolor=\"$field[row_color]\" onmouseover=\"checktoggle_over('input_row$i', 'row$i','$global[row_color_mouseover]');\" onmouseout=\"checktoggle('input_row$i', 'row$i','$field[row_colora]');\">
	";

	for ($j = 0; $j < $conf_columns_count; $j++) {

		$data[$j][$conf_columns[$j]] = mysql_result($global['dbresult'],$i,"$conf_columns[$j]");

		$data[$j][$conf_columns[$j]] = strip_tags($data[$j][$conf_columns[$j]]);

		if (isset($data[$j][$conf_columns[0]])) {
			if ($data[$j][$conf_columns[0]]!="") {

				$field['input_id'] = $data[$j][$conf_columns[0]];

			}
		}

		if (!isset($field['input_id'])) {
			$field['input_id'] = "";
		}

		if ("$j"=="0") {

			if ("$conf_edit[0]"=="1") {

				if ("$conf_edit[1]"=="1") {

					$field['output_noscript'] .= "<td width=20 align=center><input type=\"radio\" name=\"data_input\" value=\"$field[input_id]\" id=\"input_row$i\" onclick=\"checktoggle('input_row$i', 'row$i','$field[row_color]');\"></td>
					";
					$field['output_script'] .= "<td width=20 align=center><input type=\"radio\" name=\"data_input\" value=\"$field[input_id]\" id=\"input_row$i\" onclick=\"checktoggle('input_row$i', 'row$i','$field[row_color]');\"></td>
					";

				} elseif ("$conf_edit[1]"=="2") {

					$field['output_noscript'] .= "<td width=20 align=center><input type=\"checkbox\" name=\"data_input\" value=\"$field[input_id]\" id=\"input_row$i\" onclick=\"checktoggle('input_row$i', 'row$i','$field[row_color]');\"></td>
					";
					$field['output_script'] .= "<td width=20 align=center><input type=\"checkbox\" name=\"data_input\" value=\"$field[input_id]\" id=\"input_row$i\" onclick=\"checktoggle('input_row$i', 'row$i','$field[row_color]');\"></td>
					";

				}
			}
		}

		$field['output_noscript'] .= "<td><span class=\"field\">&nbsp;".$data[$j][$conf_columns[$j]];
		$field['output_noscript'] .= "</span></td>
		";
		$field['output_script'] .= "<td><span class=\"field\">&nbsp;".$data[$j][$conf_columns[$j]];
		$field['output_script'] .= "</span></td>
		";

	}

	if ("$conf_edit[0]"=="2") {
		if ("$conf_edit[1]"=="1") {

			$field['output_noscript'] .= "<td width=20 align=center><input type=\"radio\" name=\"data_input\" value=\"$field[input_id]\" id=\"input_row$i\" onclick=\"checktoggle('input_row$i', 'row$i','$field[row_color]');\"></td>
			";
			$field['output_script'] .= "<td width=20 align=center><input type=\"radio\" name=\"data_input\" value=\"$field[input_id]\" id=\"input_row$i\" onclick=\"checktoggle('input_row$i', 'row$i','$field[row_color]');\"></td>
			";

		} elseif ("$conf_edit[1]"=="2") {

			$field['output_noscript'] .= "<td width=20 align=center><input type=\"checkbox\" name=\"data_input\" value=\"$field[input_id]\" id=\"input_row$i\" onclick=\"checktoggle('input_row$i', 'row$i','$field[row_color]');\"></td>
			";
			$field['output_script'] .= "<td width=20 align=center><input type=\"checkbox\" name=\"data_input\" value=\"$field[input_id]\" id=\"input_row$i\" onclick=\"checktoggle('input_row$i', 'row$i','$field[row_color]');\"></td>
			";

		}
	}

	$field['output_noscript'] .= "</tr>
	";
	$field['output_script'] .= "</tr>
	";

	}
}

if ("$global[dbnumber]"=="0") {

	$conf_columns_count2 = $conf_columns_count;

	if ("$conf_edit[0]"!="0") {

		$conf_columns_count2 = $conf_columns_count + 1;

	}

	$field['output_noscript'] .= "<tr><td colspan=$conf_columns_count2 align=center><span class=\"field\">&nbsp;<br><b>No records found.</b><br>&nbsp;</span></td></tr>
	";
	$field['output_script'] .= "<tr><td colspan=$conf_columns_count2 align=center><span class=\"field\">&nbsp;<br><b>No records found.</b><br>&nbsp;</span></td></tr>
	";

}

$field['output_noscript'] .= "</table><table width=100% cellpadding=0 cellspacing=0 border=0><tr><td bgcolor=\"$global[line_color]\" height=\"1\"></td></tr></table>$conf_footer
<!-- /td><td bgcolor=\"$global[page_box_color]\" width=1><img src=\"$global[url_images]/space.gif\" width=1 height=1></td></tr></table -->
";
$field['output_script'] .= "</table><table width=100% cellpadding=0 cellspacing=0 border=0><tr><td bgcolor=\"$global[line_color]\" height=\"1\"></td></tr></table>$conf_footer
<!-- /td><td bgcolor=\"$global[page_box_color]\" width=1><img src=\"$global[url_images]/space.gif\" width=1 height=1></td></tr></table -->
";

if ("$input[print_ajax]"=="1") {

	#print $field['output_script'];
	#exit;

}

if ("$conf_rows[1]"=="1") {

	if ("$field[total_pages]"=="0") {

		$field['total_pages'] = "1";

	}

		$field['output_noscript'] .= "
		<table width=\"100%\" border=0 cellpadding=3 cellspacing=0><tr><td bgcolor=\"$global[page_box_color]\">
		<table width=\"100%\" border=0 cellpadding=3 cellspacing=0>
		";
		$field['output_noscript'] .= "<tr><td><span class=\"field\">&nbsp;";

		$field['output_script'] .= "
		<table width=\"100%\" border=0 cellpadding=3 cellspacing=0><tr><td bgcolor=\"$global[page_box_color]\">
		<table width=\"100%\" border=0 cellpadding=3 cellspacing=0>
		";
		$field['output_script'] .= "<tr><td><span class=\"field\">&nbsp;";

		if ("$conf_buttons"!="") {

			$field['output_noscript'] .= "$conf_buttons";
			$field['output_script'] .= "$conf_buttons";

		} else {

			$field['output_noscript'] .= "Page ".($input['records_page']+1)." of $field[total_pages]";
			$field['output_script'] .= "Page ".($input['records_page']+1)." of $field[total_pages]";

		}

		$field['output_noscript'] .= "</span></td>
		<td align=right>";
		$field['output_script'] .= "</span></td>
		<td align=right>";

		$field['output_noscript'] .= "
		<table cellpadding=0 cellspacing=0 border=0><tr>";
		$field['output_script'] .= "
		<table cellpadding=0 cellspacing=0 border=0><tr>";

		if ($input['records_page']>0) {

			$field['output_noscript'] .= "<td><table cellpadding=1 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_border]\"><table cellpadding=4 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_color]\" align=center valign=middle><span class=\"small\"><a href=\"$global[envself]?o_b=$conf_order[0]&o_d=$conf_order[1]&s_f=$input[search_field]&data_search=$input[data_search]&p=".($input['records_page']-1)."\" class=\"small\">&lt;&lt; Prev</a></span></td></tr></table></td></tr></table></td><td width=2><img src=\"$global[url_images]/space.gif\" width=2 height=2></td>
			";
			$field['output_script'] .= "<td><table cellpadding=1 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_border]\"><table cellpadding=4 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_color]\" align=center valign=middle><span class=\"small\"><a href=\"javascript:;\" onclick=\"SimpleAJAXCall('$global[envself]?o_b=$conf_order[0]&o_d=$conf_order[1]&s_f=$input[search_field]&data_search=$input[data_search]&p=".($input['records_page']-1)."&ajax=1',SimpleAJAXCallback, '', 'data_listings');\" class=\"small\">&lt;&lt; Prev</a></span></td></tr></table></td></tr></table></td><td width=2><img src=\"$global[url_images]/space.gif\" width=2 height=2></td>
			";

		}

		if (($input['records_page']>0) && (($input['records_page']+1)<$field['total_pages'])) {

			#$field['output_noscript'] .= "&nbsp;";

		}


		for ($i = 0; $i<$field['total_pages']; $i++) {

			if ($i==$input['records_page']-3) {

				$field['output_noscript'] .= "<td align=center valign=middle><span class=\"small\">&nbsp;<a href=\"$global[envself]?o_b=$conf_order[0]&s_f=$input[search_field]&data_search=$input[data_search]&o_d=$conf_order[1]&p=0\" class=\"small\">...</a>&nbsp;</span></td><td width=2><img src=\"$global[url_images]/space.gif\" width=2 height=2></td>
				";
				$field['output_script'] .= "<td align=center valign=middle><span class=\"small\">&nbsp;<a href=\"javascript:;\" onclick=\"SimpleAJAXCall('$global[envself]?o_b=$conf_order[0]&s_f=$input[search_field]&data_search=$input[data_search]&o_d=$conf_order[1]&p=0&ajax=1',SimpleAJAXCallback, '', 'data_listings');\"	class=\"small\">...</a>&nbsp;</span></td><td width=2><img src=\"$global[url_images]/space.gif\" width=2 height=2></td>
				";

			}

			if (($i>$input['records_page']-3) && ($i<$input['records_page']+3)) {

				if (($i)==$input['records_page']) {

					$field['output_noscript'] .= "<td><table cellpadding=1 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_border]\"><table cellpadding=4 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_border]\" align=center valign=middle><span class=\"small_white\">";
					$field['output_noscript'] .= "<a href=\"$global[envself]?o_b=$conf_order[0]&s_f=$input[search_field]&data_search=$input[data_search]&o_d=$conf_order[1]&p=".($i)."\" class=\"small_white\">";
					$field['output_noscript'] .= "<b>".($i+1)."</b>";
					$field['output_noscript'] .= "</a></span></td></tr></table></td></tr></table></td><td width=2><img src=\"$global[url_images]/space.gif\" width=2 height=2></td>
					";
					$field['output_script'] .= "<td><table cellpadding=1 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_border]\"><table cellpadding=4 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_border]\" align=center valign=middle><span class=\"small_white\">";
					$field['output_script'] .= "<a href=\"javascript:;\" onclick=\"SimpleAJAXCall('$global[envself]?o_b=$conf_order[0]&s_f=$input[search_field]&data_search=$input[data_search]&o_d=$conf_order[1]&p=".($i)."&ajax=1',SimpleAJAXCallback, '', 'data_listings');\" class=\"small_white\">";
					$field['output_script'] .= "<b>".($i+1)."</b>";
					$field['output_script'] .= "</a></span></td></tr></table></td></tr></table></td><td width=2><img src=\"$global[url_images]/space.gif\" width=2 height=2></td>
					";

				} else {

					$field['output_noscript'] .= "<td><table cellpadding=1 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_border]\"><table cellpadding=4 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_color]\" align=center valign=middle><span class=\"small\">";
					$field['output_noscript'] .= "<a href=\"$global[envself]?o_b=$conf_order[0]&s_f=$input[search_field]&data_search=$input[data_search]&o_d=$conf_order[1]&p=".($i)."\" class=\"small\">";
					$field['output_noscript'] .= ($i+1);
					$field['output_noscript'] .= "</a></span></td></tr></table></td></tr></table></td><td width=2><img src=\"$global[url_images]/space.gif\" width=2 height=2></td>
					";
					$field['output_script'] .= "<td><table cellpadding=1 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_border]\"><table cellpadding=4 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_color]\" align=center valign=middle><span class=\"small\">";
					$field['output_script'] .= "<a href=\"javascript:;\" onclick=\"SimpleAJAXCall('$global[envself]?o_b=$conf_order[0]&s_f=$input[search_field]&data_search=$input[data_search]&o_d=$conf_order[1]&p=".($i)."&ajax=1',SimpleAJAXCallback, '', 'data_listings');\" class=\"small\">";
					$field['output_script'] .= ($i+1);
					$field['output_script'] .= "</a></span></td></tr></table></td></tr></table></td><td width=2><img src=\"$global[url_images]/space.gif\" width=2 height=2></td>
					";

				}

			}

			if ($i==$input['records_page']+3) {

				$field['output_noscript'] .= "<td align=center valign=middle><span class=\"small\">&nbsp;<a href=\"$global[envself]?o_b=$conf_order[0]&s_f=$input[search_field]&data_search=$input[data_search]&o_d=$conf_order[1]&p=".($field['total_pages']-1)."\" class=\"small\">...</a>&nbsp;</span></td><td width=2><img src=\"$global[url_images]/space.gif\" width=2 height=2></td>
				";
				$field['output_script'] .= "<td align=center valign=middle><span class=\"small\">&nbsp;<a href=\"javascript:;\" onclick=\"SimpleAJAXCall('$global[envself]?o_b=$conf_order[0]&s_f=$input[search_field]&data_search=$input[data_search]&o_d=$conf_order[1]&p=".($field['total_pages']-1)."&ajax=1',SimpleAJAXCallback, '', 'data_listings');\" class=\"small\">...</a>&nbsp;</span></td><td width=2><img src=\"$global[url_images]/space.gif\" width=2 height=2></td>
				";

			}


		}

		if (($input['records_page']+1)<$field['total_pages']) {

			$field['output_noscript'] .= "<td><table cellpadding=1 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_border]\"><table cellpadding=4 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_color]\" align=center valign=middle><span class=\"small\"><a href=\"$global[envself]?o_b=$conf_order[0]&o_d=$conf_order[1]&s_f=$input[search_field]&data_search=$input[data_search]&p=".($input['records_page']+1)."\" class=\"small\">Next &gt;&gt;</a></span></td></tr></table></td></tr></table></td><td width=2><img src=\"$global[url_images]/space.gif\" width=2 height=2></td>
			";
			$field['output_script'] .= "<td><table cellpadding=1 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_border]\"><table cellpadding=4 cellspacing=0 border=0><tr><td bgcolor=\"$global[page_box_color]\" align=center valign=middle><span class=\"small\"><a href=\"javascript:;\" onclick=\"SimpleAJAXCall('$global[envself]?o_b=$conf_order[0]&o_d=$conf_order[1]&s_f=$input[search_field]&data_search=$input[data_search]&p=".($input['records_page']+1)."&ajax=1',SimpleAJAXCallback, '', 'data_listings');\" class=\"small\">Next &gt;&gt;</a></span></td></tr></table></td></tr></table></td><td width=2><img src=\"$global[url_images]/space.gif\" width=2 height=2></td>
			";

		}

		$field['output_noscript'] .= "</tr></table></td></tr>
		";
		$field['output_script'] .= "</tr></table></td></tr>
		";

	}


$field['output_noscript'] .= "</table></td></tr></table></form>
";
$field['output_script'] .= "</table></td></tr></table></form>
";

$output = "<div id=\"data_listings\" style=\"width:100%;\">
$field[output_script]
</div>
<noscript>$field[output_noscript]</noscript>";



if ("$input[get_ajax]"=="1") {

	print $field['output_script'];
	exit;

}

return $output;

}

function database($querydb) {

global $global;
global $field;

$global['queries']++;
$field['queries'] = $global['queries'];
$global['query_log'] .= "\n<br>$querydb";

mysql_connect($global['dbhost'], $global['dbuser'], $global['dbpass']) or return_error("Unable to connect to host $global[dbhost]");
mysql_select_db($global['dbname']) or return_error("Unable to select database $global[dbname]");
$global['dbresult'] = mysql_query($querydb) or return_error("Query Error: $querydb");

if ((substr($querydb,0,6)!="INSERT") && (substr($querydb,0,6)!="UPDATE")) {

	$global['dbnumber'] = mysql_numrows($global['dbresult']);

}

return;

}

function return_error($error_string) {

print "<B>Error:</B><br> $error_string";
exit;

}

?>