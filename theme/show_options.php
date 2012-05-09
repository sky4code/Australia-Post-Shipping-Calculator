<?php
    global $wpdb;
	$countrylist = $wpdb->get_results( "SELECT id,country,visible FROM `" . WPSC_TABLE_CURRENCY_LIST . "` ORDER BY country ASC ", ARRAY_A );
?>
<div class="domesticoptions">
<h1>Shipping options</h1>

<form method="post">
	<table> 
	     <tr>
			 <td>Base City<sup>*</sup></td>
			 <td><input type="text" name="base_city" value=""></td>
		 </tr>
		 <tr>
			 <td>From Postcode<sup>*</sup></td>
			 <td><input type="text" name="from_postcode" value=""></td>
		 </tr>
		 <tr>
			<td>Target shipping countries</td>
			<td>
					// Display here MULTISELECT box, jquery preferred but only if that plugin can load data from request and show it checked in a box
					// #ISSUE- 1
			</td>
		 </tr>
	</table>

</form>

</div>