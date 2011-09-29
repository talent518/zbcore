<?
if(!defined('IN_SITE'))
	exit('Access Denied');

function imagebmp(&$im, $filename = '', $bit = 8, $compression = 0){
	if (!in_array($bit, array(1, 4, 8, 16, 24, 32))){
		$bit = 8;
	}elseif($bit == 32){ // todo:32 bit
		$bit = 24;
	}

	$bits = pow(2, $bit);

	// 调整调色板
	imagetruecolortopalette($im, true, $bits);
	$width  = imagesx($im);
	$height = imagesy($im);
	$colors_num = imagecolorstotal($im);

	if ($bit <= 8){
		// 颜色索引
		$rgb_quad = '';
		for ($i = 0; $i < $colors_num; $i ++){
			$colors = imagecolorsforindex($im, $i);
			$rgb_quad .= chr($colors['blue']) . chr($colors['green']) . chr($colors['red']) . "\0";
		}

		// 位图数据
		$bmp_data = '';

		// 非压缩
		if ($compression == 0 || $bit < 8){
			if (!in_array($bit, array(1, 4, 8))){
				$bit = 8;
			}

			$compression = 0;

			// 每行字节数必须为4的倍数，补齐。
			$extra = '';
			$padding = 4 - ceil($width / (8 / $bit)) % 4;
			if ($padding % 4 != 0){
				$extra = str_repeat("\0", $padding);
			}

			for ($j = $height - 1; $j >= 0; $j --){
				$i = 0;
				while ($i < $width){
					$bin = 0;
					$limit = $width - $i < 8 / $bit ? (8 / $bit - $width + $i) * $bit : 0;

					for ($k = 8 - $bit; $k >= $limit; $k -= $bit){
						$index = imagecolorat($im, $i, $j);
						$bin |= $index << $k;
						$i ++;
					}

					$bmp_data .= chr($bin);
				}

				$bmp_data .= $extra; 
			}
		}else if ($compression == 1 && $bit == 8){// RLE8 压缩
			for ($j = $height - 1; $j >= 0; $j --){
				$last_index = "\0";
				$same_num   = 0;
				for ($i = 0; $i <= $width; $i ++){
					$index = imagecolorat($im, $i, $j);
					if ($index !== $last_index || $same_num > 255){
						if ($same_num != 0){
							$bmp_data .= chr($same_num) . chr($last_index);
						}

						$last_index = $index;
						$same_num = 1;
					}else{
						$same_num ++;
					}
				}

				$bmp_data .= "\0\0";
			}

			$bmp_data .= "\0\1";
		}

		$size_quad = strlen($rgb_quad);
		$size_data = strlen($bmp_data);
	}else{
		// 每行字节数必须为4的倍数，补齐。
		$extra = '';
		$padding = 4 - ($width * ($bit / 8)) % 4;
		if ($padding % 4 != 0){
			$extra = str_repeat("\0", $padding);
		}

		// 位图数据
		$bmp_data = '';

		for ($j = $height - 1; $j >= 0; $j --){
			for ($i = 0; $i < $width; $i ++){
				$index  = imagecolorat($im, $i, $j);
				$colors = imagecolorsforindex($im, $index);

				if ($bit == 16){
					$bin = 0 << $bit;

					$bin |= ($colors['red'] >> 3) << 10;
					$bin |= ($colors['green'] >> 3) << 5;
					$bin |= $colors['blue'] >> 3;

					$bmp_data .= pack("v", $bin);
				}else{
					$bmp_data .= pack("c*", $colors['blue'], $colors['green'], $colors['red']);
				}

				// todo: 32bit; 
			}

			$bmp_data .= $extra;
		}

		$size_quad = 0;
		$size_data = strlen($bmp_data);
		$colors_num = 0;
	}

	// 位图文件头
	$file_header = "BM" . pack("V3", 54 + $size_quad + $size_data, 0, 54 + $size_quad);

	// 位图信息头
	$info_header = pack("V3v2V*", 0x28, $width, $height, 1, $bit, $compression, $size_data, 0, 0, $colors_num, 0);

	// 写入文件
	if ($filename != ''){
		$fp = fopen($filename, "wb");

		fwrite($fp, $file_header);
		fwrite($fp, $info_header);
		fwrite($fp, $rgb_quad);
		fwrite($fp, $bmp_data);
		fclose($fp);

		return 1;
	}

	// 浏览器输出
	header("Content-Type: image/bmp");
	echo $file_header . $info_header;
	echo $rgb_quad;
	echo $bmp_data;

	return 1;
}

//第二个就类似于 imagecreatefrompng 等等的:
function imagecreatefrombmp($p_sFile){
	//    Load the image into a string
	$file    =    fopen($p_sFile,"rb");
	$read    =    fread($file,10);
	while(!feof($file)&&($read<>""))
		$read    .=    fread($file,1024);

	$temp    =    unpack("H*",$read);
	$hex    =    $temp[1];
	$header    =    substr($hex,0,108);

	//    Process the header
	//    Structure: http://www.fastgraph.com/help/bmp_header_format.html
	if (substr($header,0,4)=="424d"){
		//    Cut it in parts of 2 bytes
		$header_parts    =    str_split($header,2);

		//    Get the width        4 bytes
		$width            =    hexdec($header_parts[19].$header_parts[18]);

		//    Get the height        4 bytes
		$height            =    hexdec($header_parts[23].$header_parts[22]);

		//    Unset the header params
		unset($header_parts);
	}

	//    Define starting X and Y
	$x                =    0;
	$y                =    1;

	//    Create newimage
	$image            =    imagecreatetruecolor($width,$height);

	//    Grab the body from the image
	$body            =    substr($hex,108);

	//    Calculate if padding at the end-line is needed
	//    Divided by two to keep overview.
	//    1 byte = 2 HEX-chars
	$body_size        =    (strlen($body)/2);
	$header_size    =    ($width*$height);

	//    Use end-line padding? Only when needed
	$usePadding        =    ($body_size>($header_size*3)+4);

	//    Using a for-loop with index-calculation instaid of str_split to avoid large memory consumption
	//    Calculate the next DWORD-position in the body
	for ($i=0;$i<$body_size;$i+=3){
	//    Calculate line-ending and padding
	if ($x>=$width){
		//    If padding needed, ignore image-padding
		//    Shift i to the ending of the current 32-bit-block
		if ($usePadding)
			$i    +=    $width%4;

		//    Reset horizontal position
		$x    =    0;

		//    Raise the height-position (bottom-up)
		$y++;

		//    Reached the image-height? Break the for-loop
		if ($y>$height)
			break;
		}

		//    Calculation of the RGB-pixel (defined as BGR in image-data)
		//    Define $i_pos as absolute position in the body
		$i_pos    =    $i*2;
		$r        =    hexdec($body[$i_pos+4].$body[$i_pos+5]);
		$g        =    hexdec($body[$i_pos+2].$body[$i_pos+3]);
		$b        =    hexdec($body[$i_pos].$body[$i_pos+1]);

		//    Calculate and draw the pixel
		$color    =    imagecolorallocate($image,$r,$g,$b);
		imagesetpixel($image,$x,$height-$y,$color);

		//    Raise the horizontal position
		$x++;
	}

	//    Unset the body / free the memory
	unset($body);

	//    Return image-object
	return $image;
}
