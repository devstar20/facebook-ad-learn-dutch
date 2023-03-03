<?php

	// From data management
	$word = $_POST['word'];
	$word_tran = $_POST['word_tran'];

	$word_type = $_POST['word_type'];
	$word_type_tran = $_POST['word_type_tran'];

	$ex1 = $_POST['ex1'];
	$ex1_tran = $_POST['ex1_tran'];

	$ex2 = $_POST['ex2'];
	$ex2_tran = $_POST['ex2_tran'];
	$inputData = array();
	if(empty($word) || empty($word_tran) || empty($word_type) || empty($word_type_tran)){
		echo '<h1>Please fill word, word_tran, word_type, word_type_trans fields</h1>';
		return;
	}else{
		array_push($inputData, $word);
		array_push($inputData, $word_tran);
		array_push($inputData, $word_type);
		array_push($inputData, $word_type_tran);

		if(!empty($ex1) || !empty($ex1_tran)) {
			if(empty($ex1)){
				echo '<h1>Please fill Ex1 sentence</h1>';
				return;
			}else if(empty($ex1_tran)){
				echo '<h1>Please fill Ex1 sentence translation</h1>';
				return;
			} else {
				array_push($inputData, $ex1);
				array_push($inputData, $ex1_tran);
			}
		}

		if(!empty($ex2) || !empty($ex2_tran)) {
			if(empty($ex2)){
				echo '<h1>Please fill Ex2 sentence</h1>';
				return;
			}else if(empty($ex2_tran)){
				echo '<h1>Please fill Ex2 sentence translation</h1>';
				return;
			} else {
				array_push($inputData, $ex2);
				array_push($inputData, $ex2_tran);
			}
		}
	}

	if(!empty($inputData)){
		GenerateImg($inputData);
	}

	function GenerateImg($textArray) {
		// background image
		$bgImg = imagecreatefrompng('back1.png');

		// Set the text to be displayed
		// word, en_word, word_type, en_type, sample1, trans_sample1, sample2, trans_sample2
		// $textArray = array('vannacht', 'last night', 'bijwoorden', 'adverbs', 'Ik heb Vannacht nauwelijks geslapen', 'I splept very little last night', 'Vannacht gaat de zomertijd in.', 'Summertime starts tonight.');

		// write word and en_word
		// Set the font size and color
		$header_font_size = 108;
		$header_font_color = imagecolorallocate($bgImg, 8, 137, 209);

		$x = 220; 
		$y = 600;
		imagettftext($bgImg, $header_font_size, 0, $x, $y, $header_font_color, 'times_new_roman_bold.ttf', $textArray[0]);

		list($left,, $right) = imageftbbox( $header_font_size, 0, "times_new_roman_bold.ttf", $textArray[0]);
		$x = 220 + ($right - $left) + 40;
		imagettftext($bgImg, 90, 0, $x, $y, $header_font_color, 'times_new_roman_bold_italic.ttf', '('.$textArray[1].')');

		// word_type, en_type
		$type_font_size = 50;
		$type_font_color = imagecolorallocate($bgImg, 0, 0, 0);
		$x = 220;
		$y = 700;
		imagettftext($bgImg, $type_font_size, 0, $x, $y, $type_font_color, 'times_new_roman_italic.ttf', $textArray[2]);

		list($left,, $right) = imageftbbox( $type_font_size, 0, "times_new_roman_italic.ttf", $textArray[2]);
		$x = 220 + ($right - $left) + 10;
		imagettftext($bgImg, $type_font_size, 0, $x, $y, $type_font_color, 'times_new_roman_italic.ttf', '('.$textArray[3].')');

		// Examples:
		$text_font_size = 55;
		$text_font_color = imagecolorallocate($bgImg, 0, 0, 0);
		$x = 220;
		$y = 880;
		imagettftext($bgImg, $text_font_size, 0, $x, $y, $text_font_color, 'times_new_roman.ttf', 'Examples:');

		// extract example sentences
		$example_array = array_slice($textArray, 4);
		$len = sizeof($example_array) / 2;
		$p = 220;
		$q = 990;
		// loop sample sentences: current one has 2 sample sentences
		for($i = 0; $i < $len; $i ++) {
			// first is bold index
			imagettftext($bgImg, $text_font_size, 0, $p, $q, $text_font_color, 'times_new_roman_bold.ttf', (string)($i + 1).'.');
			// check the size of Dutch for checking over of width
			list($left,, $right) = imageftbbox( $text_font_size, 0, "times_new_roman.ttf", $example_array[$i * 2]);
			$len1 = $right - $left;
			
			// break sentence to 2 lines
			if($len1 > 1600) {			
				$ret = parseString($example_array[$i * 2], $text_font_size);

				// print the first line
				printText($ret[0], $textArray[0], $p + 70, $q, $text_font_color, $text_font_size, $bgImg);

				$p = 220;
				$q = $q + 60;

				// print the second line
				printText($ret[1], $textArray[0], $p + 70, $q, $text_font_color, $text_font_size, $bgImg);
			} else {
				printText($example_array[$i * 2], $textArray[0], $p + 70, $q, $text_font_color, $text_font_size, $bgImg);
			}
			$q = $q + 90;

			// sample En sentence
			// check the size of Dutch for checking over of width
			list($left,, $right) = imageftbbox( $text_font_size, 0, "times_new_roman.ttf", $example_array[$i * 2 + 1]);
			$len2 = $right - $left;
			if($len2 > 1600) {
				// break sentence to 2 lines
				$ret = parseString($example_array[$i * 2 + 1], $text_font_size);

				//print the first sample line
				printText('('.$ret[0], $textArray[0], $p, $q, $text_font_color, $text_font_size, $bgImg);

				// imagettftext($bgImg, $text_font_size, 0, $p, $q, $text_font_color, 'arial.ttf', '('.$ret[0]);

				$p = 220;
				$q = $q + 60;

				// imagettftext($bgImg, $text_font_size, 0, $p, $q, $text_font_color, 'arial.ttf', $ret[1].')');
				printText($ret[1].')', $textArray[0], $p, $q, $text_font_color, $text_font_size, $bgImg);
			} else {
				// imagettftext($bgImg, $text_font_size, 0, $p, $q, $text_font_color, 'arial.ttf', '('.$example_array[$i * 2 + 1].')');
				printText('('.$example_array[$i * 2 + 1].')', $textArray[0], $p, $q, $text_font_color, $text_font_size, $bgImg);
			}

			// for next line coordination
			$q = $q + 120;
		}

		// Output the image
		header("Content-type: image/png");

		imagepng($bgImg);

		// Free up memory
		imagedestroy($bgImg);
	}

	

	function printText($textString, $word, $x, $y, $text_font_color, $text_font_size, $bgImg) {
		$pattern = "/\b(?:".$word.")\b/i";
		$s_arr = preg_split($pattern, $textString);
		if(sizeof($s_arr) > 1){
			for($j = 0; $j < sizeof($s_arr) - 1; $j++){
				imagettftext($bgImg, $text_font_size, 0, $x, $y, $text_font_color, 'times_new_roman.ttf', $s_arr[$j]);
				// move x coordination
				list($left,, $right) = imageftbbox( $text_font_size, 0, "times_new_roman.ttf", $s_arr[$j]);
				$dist = $right - $left;
				$x = $x + $dist;
				imagettftext($bgImg, $text_font_size, 0, $x, $y, $text_font_color, 'times_new_roman_bold.ttf', ' '.ucfirst($word).' ');
				list($left,, $right) = imageftbbox( $text_font_size, 0, "times_new_roman_bold.ttf", ucfirst($word).' ');
				$dist = $right - $left;
				$x = $x + $dist;
			}
			imagettftext($bgImg, $text_font_size, 0, $x, $y, $text_font_color, 'times_new_roman.ttf', $s_arr[sizeof($s_arr) - 1]);
		} else {
			imagettftext($bgImg, $text_font_size, 0, $x, $y, $text_font_color, 'times_new_roman.ttf', $textString);
		}
		return;
	}

	function parseString($textString, $fontsize) {
		$string_arr = array();
		// splite words
		$sen_arr = explode(" ", $textString);
		for($i = sizeof($sen_arr) - 1; $i > 0; $i--) {
			$first_arr = array_slice($sen_arr, 0, $i);
			$first_str = implode(" ", $first_arr);
			list($left,, $right) = imageftbbox( $fontsize, 0, "times_new_roman.ttf", $first_str);
			$len = $right - $left;
			if($len < 1600) {
				$second_arr = array_slice($sen_arr, $i);
				$second_str = implode(" ", $second_arr);
				array_push($string_arr, $first_str);
				array_push($string_arr, $second_str);
				break;
			}
		}
		return $string_arr;
	}
?>

