<strong>Calculate postage</strong>
 
<?php
 if($_POST) {
 // GET the variables from the POST
 
 $from_postcode = 3000; // wylong postcode
 $to_postcode = $_POST['to_postcode'];
 $length = $_POST['length']; // (cm)
 $height = $_POST['height']; // (cm)
 $width = $_POST['width']; // (cm)
 $weight = $_POST['weight']; // kgs
 }
 else {
 $to_postcode = '';
 $length = ''; // (cm)
 $height = ''; // (cm)
 $width = ''; // (cm)
 $weight = ''; // kgs
 }
?>
 
<form method="post">
 
<table>
 <tr>
 <td>From Postcode</td>
 <td>3000 (Melbourne)</td>
 </tr>
 <tr>
 <td>To Postcode</td>
 <td><input type="text" name="to_postcode" value="<?php echo $to_postcode;?>"></td>
 </tr>
 <tr>
 <td>Height (cm)</td>
 <td><input type="text" name="height" value="<?php echo $height;?>"></td>
 </tr>
 <tr>
 <td>Width (cm)</td>
 <td><input type="text" name="width" value="<?php echo $width;?>"></td>
 </tr>
 <tr>
 <td>Length (cm)</td>
 <td><input type="text" name="length" value="<?php echo $length;?>"></td>
 </tr>
 <tr>
 <td>Weight (kgs)</td>
 <td><input type="text" name="weight" value="<?php echo $weight;?>"></td>
 </tr>
</table>
<input type="submit" value="Calculate" />
</form>
<hr />
<?php
if ($_POST) {
 
 // http://forums.whirlpool.net.au/forum-replies.cfm?t=1770950&p=7
 include_once 'auspost_api.php';
 //Domestic parcel postage calculation example (page 17 api specs)
 $post_str = "https://auspost.com.au/api/postage/parcel/domestic/service.json?from_postcode=$from_postcode&to_postcode=$to_postcode&length=$length&height=$height&width=$width&weight=$weight";
 
 $auspost_json = get_auspost_api($post_str);
 
 //convert json to array
 $auspost_array = json_decode($auspost_json,true);
 print_r($auspost_array);
 $options = $auspost_array['services']['service'];
 if ($options) {
 // if postage options exist, create postage options form
 ?>
 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
 <script>
 $(document).ready(function() {
 
$('.service_code').change(function() {
 // remove all previous selections
 $('.option_all_box').attr('checked', false);
 $('.option_all').val('0');
 
 // hide all divs
 $('.option_top').hide();
 
 // show appropriate div & select first option
 if($(this).val() == 'AUS_PARCEL_REGULAR')
 {
 $('.option_regular').fadeIn();
 $('.standard_first').attr('checked', true);
 }
 else if ($(this).val() == 'AUS_PARCEL_PLATINUM')
 {
 $('.option_platinum').fadeIn();
 $('.platinum_first').attr('checked', true);
 }
 
 });
 
 $('form#frm_calculate').change(function() {
 // ajaxify the calculate
 // submit the form
 $('#totals').html('Recalculating...');
 formdata = $("#frm_calculate").serialize()
 $.post('calculate.php', formdata ,function(data) {
 
 if(data.error)
 {
 
if(data.error.errorMessage == 'Please enter a valid ausParcelForm.searchCriteria.extraCoverMap[AUS_PARCEL_REGULAR] amount.')
 {
 $('#totals').html('Please enter a valid amount for extra cover (greater than zero)');
 }
 else
 {
 $('#totals').html(data.error.errorMessage);
 }
 }
 else
 {
 result = data.postage_result;
 str = result.service + '<br>' + result.delivery_time + '<br>Total: $' + result.total_cost;
 
 // loop over the cost breakdown
 //alert($(result.costs.cost).size());
 listsize = $(result.costs.cost).size();
 
 str = str + "<table width='100%'>";
 if (listsize > 1)
 {
 $.each(result.costs.cost, function(key,value) {
 str = str +'<tr><td width="50%">'+ value.item + '</td><td width="50%" style="text-align: right;">$' + value.cost + '</td></tr>';
 });
 }
 else if (listsize == 1)
 {
 value = result.costs.cost;
 str = str + '<tr><td width="50%">'+value.item + '</td><td width="50%" style="text-align: right;">$' + value.cost + '</td></tr>';
 }
 
 str = str + "</table>";
 
 $('#totals').html(str);
 
 }
 }, 'json');
 
 });
 
 });
 </script>
 
 <style>
 tr.strong td {
 font-weight: bold;
 }
 .smaller {
 font-size: smaller;
 }
 .indent {
 margin-left: 20px;
 }
 .hide {
 display: none;
 }
 </style>
 <form action='calculate.php' method="post" id="frm_calculate">
 <input type='hidden' name='to_postcode' value='<?php echo $to_postcode;?>' />
 <input type='hidden' name='height' value='<?php echo $height;?>' />
 <input type='hidden' name='width' value='<?php echo $width;?>' />
 <input type='hidden' name='length' value='<?php echo $length;?>' />
 <input type='hidden' name='weight' value='<?php echo $weight;?>' />
 <div style="width: 50%; float: left;">
 <table>
 <tr class="strong">
 <td> </td>
 <td>Postage method</td>
 <td>Price</td>
 </tr>
 <?php
 foreach($options as $row)
 {
 // if you wish, just show the options you want
 if ($row['code'] == 'AUS_PARCEL_REGULAR'
 || $row['code'] == 'AUS_PARCEL_EXPRESS'
 || $row['code'] == 'AUS_PARCEL_PLATINUM')
 {
 echo '<tr><td valign="top">';
 echo '<input type="radio" name="service_code" class="service_code" value="'.$row['code'].'" />';
 echo '</td><td>';
 echo $row['name'];
 
 if($row['options'])
 {
 // show the options in the div when the radio is clicked
 echo '<div class="smaller">';
 // show options
 
 if($row['code'] == 'AUS_PARCEL_REGULAR')
 {
 // show standard service and registered mail with extra cover option
 ?>
 <div class="option_regular indent hide option_top">
 <input type="radio" name="option_code" value="AUS_SERVICE_OPTION_STANDARD" class="option_all_box standard_first" />Standard Service <br />
 <input type="radio" name="option_code" value="AUS_SERVICE_OPTION_REGISTERED_POST" class="option_all_box" />Registered Post <br />
 <div class="indent">
 <input type="checkbox" name="suboption_code" value="AUS_SERVICE_OPTION_EXTRA_COVER" class="option_all_box" />Extra Cover <br />
 $<input type="text" name="extra_cover_standard" class="option_all" /> up to $<?php echo $row['max_extra_cover']; ?>
 </div>
 </div>
 <?php
 }
 elseif($row['code'] == 'AUS_PARCEL_PLATINUM')
 {
 // show standard service with extra cover option
 ?>
 <div class="option_platinum indent hide option_top">
 <input type="radio" name="option_code" value="AUS_SERVICE_OPTION_PLATINUM_EXTRA_COVER_SERVICE" class="option_all_box platinum_first" />Standard Service <br />
 <div class="indent">
 <input type="checkbox" name="suboption_code" value="AUS_SERVICE_OPTION_EXTRA_COVER" class="option_all_box" />Extra Cover <br />
 $<input type="text" name="extra_cover_platinum" class="option_all" /> up to $<?php echo $row['max_extra_cover']; ?>
 </div>
 </div>
 <?php
 }
 // loop over each of the options
 //display_options($row['options']['option']);
 echo '</div>';
 }
 
 echo '</td><td valign="top">';
 echo '$'.$row['price'];
 echo '</td></tr>';
 }
 }
 
?>
 </table>
 </div>
 <!--<input type="submit" value="Recalculate" /> -->
 </form>
 
 <div id="totals" style="width: 45%; float: right;">
 
 </div>
 
 <p><br /><br /><br />
 <?php
 //new dBug($options);
 }
 else {
 // if no postage options exist, show error message
 echo $auspost_array['error']['errorMessage'];
 //new dBug($auspost_array);
 }
 
}
?>