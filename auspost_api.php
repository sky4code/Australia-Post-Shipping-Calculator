<?php
function get_auspost_api($url)
 {
 $crl = curl_init();
 $timeout = 5;
 curl_setopt ($crl, CURLOPT_HTTPHEADER, array('AUTH-KEY: 1LmjtHeMtfbRtsZ4UaFq7zI71AO5aLW2'));
 curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, false);
 curl_setopt ($crl, CURLOPT_URL, $url);
 curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
 $ret = curl_exec($crl);
 curl_close($crl);
 
 return $ret;
}
?>