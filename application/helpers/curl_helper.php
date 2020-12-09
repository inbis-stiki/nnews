<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('download_image')){
  function download_image($url){
    $start = curl_init();
    curl_setopt($start, CURLOPT_URL, $url);
    curl_setopt($start, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($start, CURLOPT_SSLVERSION, 3);
    $file_data = curl_exec($start);
    curl_close($start);
    return $file_data;
  }  
}
if (!function_exists('get_data')){
  function getData($url){
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output);
  }
}
?>