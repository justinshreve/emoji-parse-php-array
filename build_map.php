<?php

	# Forked from https://github.com/iamcal/php-emoji/commit/847e9e7f149a63694dbc4f2657bd07edbe00ac2d
	include('catalog.php');

	$items = $emoji_catalog;


	#
	# build the final maps
	#

	$maps = array();

	$maps['names']		= make_names_map($items);
	$maps['html']	    = make_html_map($items);

	#
	# output
	# we could just use var_dump, but we get 'better' output this way
	#

	echo "<"."?php\n";

	echo "/**\n";
	echo " * WARNING:\n";
	echo " * This code is auto-generated. Do not modify it manually.\n";
	echo " * @see https://github.com/justinshreve/emoji-parse-php-array\n";
	echo "*/";
	echo "\n";

	echo "\$emoji_maps = array(\n";

	echo "\t'names' => array(\n";

	foreach ($maps['names'] as $k => $v){

		$key_enc = format_string($k);
		$name_enc = "'".AddSlashes($v)."'";
		echo "\t\t$key_enc => $name_enc,\n";
	}

	echo "\t),\n";

	foreach ($maps as $k => $v){

		if ($k == 'names') continue;

		echo "\t'$k' => array(\n";

		$count = 0;
		echo "\t\t";
		foreach ($v as $k2 => $v2){
			$count++;
			if ($count % 5 == 0) echo "\n\t\t";
			echo format_string($k2).'=>'.format_string($v2).', ';
		}
		echo "\n";

		echo "\t),\n";
	}

	echo ");\n";


	##########################################################################################

	function make_names_map($map){

		$out = array();
		foreach ($map as $row){

			$bytes = unicode_bytes($row['unicode']);

			$out[$bytes] = $row['char_name']['title'];
		}

		return $out;
	}

	function make_html_map($map){

		$out = array();
		foreach ($map as $row){

			$hex = '';
			foreach ($row['unicode'] as $cp) $hex .= sprintf('%x', $cp);

			$bytes = unicode_bytes($row['unicode']);

			$out[$bytes] = "<span class=\"emoji emoji$hex\"></span>";
		}

		return $out;
	}

	function unicode_bytes($cps){

		$out = '';

		foreach ($cps as $cp){
			$out .= emoji_utf8_bytes($cp);
		}

		return $out;
	}

	function emoji_utf8_bytes($cp){

		if ($cp > 0x10000){
			# 4 bytes
			return	chr(0xF0 | (($cp & 0x1C0000) >> 18)).
				chr(0x80 | (($cp & 0x3F000) >> 12)).
				chr(0x80 | (($cp & 0xFC0) >> 6)).
				chr(0x80 | ($cp & 0x3F));
		}else if ($cp > 0x800){
			# 3 bytes
			return	chr(0xE0 | (($cp & 0xF000) >> 12)).
				chr(0x80 | (($cp & 0xFC0) >> 6)).
				chr(0x80 | ($cp & 0x3F));
		}else if ($cp > 0x80){
			# 2 bytes
			return	chr(0xC0 | (($cp & 0x7C0) >> 6)).
				chr(0x80 | ($cp & 0x3F));
		}else{
			# 1 byte
			return chr($cp);
		}
	}

	function format_string($s){
		$out = ''; 
		for ($i=0; $i<strlen($s); $i++){
			$c = ord(substr($s,$i,1));
			if ($c >= 0x20 && $c < 0x80 && !in_array($c, array(34, 39, 92))){
				$out .= chr($c);
			}else{
				$out .= sprintf('\\x%02x', $c);
			}   
		}   
		return '"'.$out.'"';
	}   
