<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('encryptPass'))
{
    function encryptPass($password,$username) 
    {
        $one = $username;
        $two = $password;
        $thr = $one . $two;
        $fou = $thr . $one;
        $fiv = $fou . $one;
        $six = $thr . $thr;
        $sev = $one . $two . $thr . $one;
        $md1 = md5( $sev . $two . md5($one . $fiv . md5($sev . strrev( $sev))));
        $md2 = md5( $md1 . md5( $one . $thr . $fou . md5( $sev . $md1)));
        $md3 = md5( $md2 . md5($md1));
        $md4 = md5( $md3 . $md1 . $md2 . md5($sev));
        return $md2 . $md1. $md4 . md5($md3 . $md2);
    }
}
/* End of file */