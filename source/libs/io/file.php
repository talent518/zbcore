<?php
if(! defined('IN_ZBC'))
	exit('Access Denied');

class LibIoFile {

	// 得到文件名.
	function ext($filename) {
		return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	}

	function move($source, $target) {
		if(@rename($source, $target)) {
			@chmod($target, 0777);
			return true;
		} elseif(function_exists('move_uploaded_file') && @move_uploaded_file($source, $target)) {
			@chmod($target, 0777);
			return true;
		} elseif(@copy($source, $target)) {
			@chmod($target, 0777);
			@unlink($source);
			return true;
		} else {
			return false;
		}
	}

	function read($file) {
		return file_get_contents($file);
	}

	function write($file, $content) {
		$path = dirname($file);
		L('io.dir')->mkdirs($path, 0777, true);

		return @file_put_contents($file, $content) !== false;
	}
}