<?php
$handle = curl_init();
 
$url = "http://www.sismologia.cl/links/ultimos_sismos.html";
 
// Set the url
curl_setopt($handle, CURLOPT_URL, $url);
// Set the result output to be a string.
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
 
$output = curl_exec($handle);
 
curl_close($handle);
 
echo $output;
?>