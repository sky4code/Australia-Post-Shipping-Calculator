<?php
 
if($_POST) {
 // GET the variables from the POST
 
 $from_postcode = 2671; // wylong postcode
 $to_postcode = $_POST['to_postcode'];
 $length = $_POST['length']; // (cm)
 $height = $_POST['height']; // (cm)
 $width = $_POST['width']; // (cm)
 $weight = $_POST['weight']; // kgs
 $service_code = $_POST['service_code'];
 $option_code = $_POST['option_code'];
 $suboption_code = $_POST['suboption_code'];
 $extra_cover_standard = $_POST['extra_cover_standard'];
 $extra_cover_platinum = $_POST['extra_cover_platinum'];
 
 if($extra_cover_standard > 0 && $extra_cover_standard != '')
 {
 $extra_cover = $extra_cover_standard;
 }
 else
 {
 $extra_cover = $extra_cover_platinum;
 }
 
 include_once 'auspost_api.php';
 
 $post_str = "https://auspost.com.au/api/postage/parcel/domestic/calculate.json?from_postcode=$from_postcode&to_postcode=$to_postcode&length=$length&height=$height&width=$width&weight=$weight&service_code=$service_code";
 
 if($option_code)
 {
 $post_str = $post_str . "&option_code=$option_code";
 }
 if($suboption_code)
 {
 $post_str = $post_str . "&suboption_code=$suboption_code";
 }
 if($suboption_code == 'AUS_SERVICE_OPTION_EXTRA_COVER' && $extra_cover != '')
 {
 $post_str = $post_str . "&extra_cover=$extra_cover";
 }
 
 
//echo $post_str;
 //echo $extra_cover;
 $auspost_json = get_auspost_api($post_str);
 
 header('Content-type: application/json');
 echo $auspost_json;
 
 //convert json to array
 //$auspost_array = json_decode($auspost_json,true);
 
 //new dBug($auspost_array);
}
?>