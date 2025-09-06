<?php

function ValidateReCaptcha($captcha){
  $number_a   = session('captcha')->number_a;
  $number_b   = session('captcha')->number_b;
  $operacion  = session('captcha')->operacion;
  $resultado = 0;
  switch ($operacion) {
    case 'mas':
      $resultado = $number_a + $number_b;
      break;
    case 'menos':
      $resultado = $number_a - $number_b;
      break;
    
    default:
      $resultado = $number_a * $number_b;
      break;
  }
  if($resultado == $captcha)
    return true;
  else return false;
}