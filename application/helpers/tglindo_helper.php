<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('tgl_indo')){
  function tgl_indo($tgl){
    $times = explode(' ', $tgl);
    $tanggal = explode('-', $times[0]);
    $bulan = getBulan($tanggal[1]);
    return number_format($tanggal[2]) . ' ' . $bulan . ' ' . $tanggal[0];
  }

  function tglwaktu_indo($tgl){
    $times = explode(' ', $tgl);
    $tanggal = explode('-', $times[0]);
    $bulan = getBulan($tanggal[1]);
    return number_format($tanggal[2]) . ' ' . $bulan . ' ' . $tanggal[0] . ' ' . $times[1];
  }

  function getBulan($bln){
    switch ($bln){
      case 1: return "Januari";
        break;
      case 2: return "Februari";
        break;
      case 3: return "Maret";
        break;
      case 4: return "April";
        break;
      case 5: return "Mei";
        break;
      case 6: return "Juni";
        break;
      case 7: return "Juli";
        break;
      case 8: return "Agustus";
        break;
      case 9: return "September";
        break;
      case 10: return "Oktober";
        break;
      case 11: return "November";
        break;
      case 12: return "Desember";
        break;    
    }
  }
}
?>