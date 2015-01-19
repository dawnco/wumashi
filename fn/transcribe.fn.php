<?php

/**
 * 去掉自动转移
 * @author  Dawnc
 * @date    2015-01-06
 */


function transcribe() {
	// magic_quotes_gpc can't be turned off
	if( ! function_exists( 'get_magic_quotes_gpc' ) ) {
		return;
	}
	if(get_magic_quotes_gpc()) {
		for($i = 0, $_SG = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST), $c = count($_SG); $i < $c; ++$i) {
			$_SG[$i] = array_map('stripslashes', $_SG[$i]);
		}
	}
}

transcribe();