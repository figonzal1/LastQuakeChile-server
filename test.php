<?php 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://www.sismologia.cl/links/ultimos_sismos.html"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$temp = trim(curl_exec($ch));
curl_close($ch);

?>