<?php
if(!defined('IN_ZBC'))
	exit('Access Denied');

?><style type="text/css">
	.debug-detail{margin:10px 0 0;}
	.debug-detail table {width:100%;border:0;margin:10px 0 0;text-align:left;width:100%;border:0;border-collapse:collapse;background:#FFF;}
	.debug-detail table:first-child{margin:0;}
	.debug-detail th, .debug-detail td {border:1px solid #000;background:#CCC;padding:3px;font-family: Courier New, Arial;font-size:12px;}
	.debug-detail td {background:#FFFCCC;}
	.debug-detail .tclass2 th {background:#D5EAEA;}
	.debug-detail .tclass2 td {background:#FFFFFF;}
	.debug-detail td.t {white-space:nowrap;}
	.debug-detail .bold {font-weight:bold;}
	.debug-detail pre{word-break:break-all;white-space:pre-wrap;}
</style>
<div class="detail debug-detail" style="display:none;"><?php
$class = 'tclass2';
foreach (DB()->querys as $dkey => $debug) {
	$class = ($class == 'tclass'?'tclass2':'tclass');
	echo '<table cellspacing="0" class="'.$class.'"><tr><th rowspan="2" class="t" width="20">'.($dkey+1).'</th><td class="t" width="60">'.$debug['time'].' ms</td><td class="bold">'.shtmlspecialchars($debug['sql']).'</td></tr>';
	if(!empty($debug['info'])) {
		echo '<tr><td>Info</th><td>'.$debug['info'].'</td></tr>';
	}
	if(!empty($debug['explain'])) {
		echo '<tr><td class="t">Explain</td><td><table cellspacing="0"><tr><th width="5%">id</td><th width="10%">select_type</td><th width="12%">table</td><th width="5%">type</td><th width="20%">possible_keys</td><th width="10%">key</td><th width="8%">key_len</td><th width="5%">ref</td><th width="5%">rows</td><th width="20%">Extra</td></tr><tr>';
		foreach ($debug['explain'] as $explain) {
			if(empty($explain)) $explain = '-';
			echo '<td>'.$explain.'</td>';
		}
		echo '</tr></table></td></tr>';
	}
	echo '</table>';
}
if($_COOKIE) {
	$class = ($class == 'tclass'?'tclass2':'tclass');
	$i = 1;
	echo '<table class="'.$class.'">';
		foreach ($_COOKIE as $k => $v) {
			echo '<tr><th width="20" class="t">'.$i.'</th><td width="250">$_COOKIE[\''.$k.'\']</td><td>'.$v.'</td></tr>';
			$i++;
		}
	echo '</table>';
}
if($files = get_included_files()) {
	$class = ($class == 'tclass'?'tclass2':'tclass');
	echo '<table class="'.$class.'">';
		foreach ($files as $fkey => $file) {
			echo '<tr><th width="20" class="t">'.($fkey+1).'</th><td>'.$file.'</td></tr>';
		}
	echo '</table>';
}
if($_SERVER) {
	$class = ($class == 'tclass'?'tclass2':'tclass');
	$i = 1;
	echo '<table class="'.$class.' tlast">';
		foreach ($_SERVER as $k => $v) {
			echo '<tr><th width="20" class="t">'.$i.'</th><td width="250" class="t">$_SERVER[\''.$k.'\']</td><td><pre>'.(is_array($v)?var_export($v,true):$v).'</pre></td></tr>';
			$i++;
		}
	echo '</table>';
}
echo '</div>';
