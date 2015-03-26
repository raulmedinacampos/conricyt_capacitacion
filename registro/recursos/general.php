<?php
require_once("conf/conf.php");

//Funcion para conectar alabase de datos
function Sdx_ConectaBase() {
    $dbh = mysqli_connect(HOST_DATA_BASE, LOGIN_ACCESO_DATA_BASE, PASS_ACCESO_DATA_BASE, DATA_BASE_NAME);
    return $dbh;
}

function Sdx_TranslateHex($Campo){
  $Campo=preg_replace('/([\x00-\x08])/', '', $Campo);
  $Campo=preg_replace('/([\x0b-\x0c])/', '', $Campo);
  $Campo=preg_replace('/([\x0e-\x19])/', '', $Campo);
  $search='abcdefghijklmnopqrstuvwxyz';
  $search.='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $search.='1234567890!@#$%^&*()';
  $search.='~`";:?+/={}[]-_|\'\\';
  for ($i=0; $i < strlen($search); $i++) {
    $Campo=preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $Campo);
    $Campo=preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $Campo);
  }
  return $Campo;
}

function Sdx_RemoveXSS($Campo) {
  $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
  $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
  $ra = array_merge($ra1, $ra2);
  
  $found = true;
  while ($found == true) {
    $Campo_before = $Campo;
    for ($i = 0; $i < sizeof($ra); $i++) {
      $pattern = '/';
      for ($j = 0; $j < strlen($ra[$i]); $j++) {
	if ($j > 0) {
	  $pattern .= '(';
	  $pattern .= '(&#[xX]0{0,8}([9ab]);)';
	  $pattern .= '|';
	  $pattern .= '|(&#0{0,8}([9|10|13]);)';
	  $pattern .= ')*';
	}
	$pattern .= $ra[$i][$j];
      }
      $pattern .= '/i';
      $replacement = substr($ra[$i], 0, 2).'<span></span>'.substr($ra[$i], 2);
      $Campo = preg_replace($pattern, $replacement, $Campo);
      if ($Campo_before == $Campo) {
	$found = false;
      }
    }
  }
  return $Campo;
}

function Sdx_TextFld2JSON($Campo){
  $Campo=html_entity_decode($Campo);
  $Campo=Sdx_TranslateHex($Campo);
  $Campo=str_replace(array("\r\n", "\n", "\r"), '|', $Campo);
  $Campo=strip_tags($Campo,'<em>');
  $Campo=stripslashes($Campo);
  $Campo=addslashes($Campo);
  //if(Sdx_isUtf8($Campo)){
    //$Campo=iconv("UTF-8", "ISO-8859-1//TRANSLIT", $Campo);
	//$Campo=iconv("ISO-8859-1//TRANSLIT", "UTF-8", $Campo);
  //}
  return $Campo;
}

function Sdx_NormalizaCampos($Campo){
  $Campo=Sdx_RemoveXSS($Campo);
  $Campo=Sdx_TranslateHex($Campo);
  $Campo=strip_tags($Campo);
  $Campo=stripslashes($Campo);
  $Campo=htmlspecialchars($Campo);
  //if(Sdx_isUtf8($Campo)){
  //$Campo=iconv("UTF-8", "ISO-8859-1//TRANSLIT", $Campo);
  //}		
  return $Campo;
}

function createPassword($length) {
    $chars = '234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#@.$%&*';
    $i = 0;
    $password = "";
    while ($i <= $length) {
        $password .= $chars{mt_rand(0, strlen($chars)-1)};
        $i++;
    }
    return $password;
}

?>
