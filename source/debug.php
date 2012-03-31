<?php
if(!defined('IN_ZBC'))
	exit('Access Denied');

?><style type="text/css">
	.tclass, .tclass2 {
	text-align:left;width:100%;border:0;border-collapse:collapse;margin-bottom:5px;table-layout: fixed; word-wrap: break-word;background:#FFF;}
	.tclass table, .tclass2 table {width:100%;border:0; word-wrap: break-word;}
	.tlast{margin-bottom:0px;}
	.tclass table td, .tclass2 table td {border-bottom:0;border-right:0;border-color: #ADADAD;}
	.tclass th, .tclass2 th {border:1px solid #000;background:#CCC;padding: 2px;font-family: Courier New, Arial;font-size: 11px;}
	.tclass td, .tclass2 td {border:1px solid #000;background:#FFFCCC;padding: 2px;font-family: Courier New, Arial;font-size: 11px;}
	.tclass2 th {background:#D5EAEA;}
	.tclass2 td {background:#FFFFFF;}
	.tclass td.t, .tclass2 td.t {white-space:nowrap;}
	.firsttr td {border-top:0;}
	.firsttd {border-left:none !important;}
	.bold {font-weight:bold;}
</style>
<div class="detail" style="display:none;"><?php
$class = 'tclass2';
foreach (DB()->querys as $dkey => $debug) {
	$class = ($class == 'tclass'?'tclass2':'tclass');
	echo '<table cellspacing="0" class="'.$class.'"><tr><th rowspan="2" class="t" width="20">'.($dkey+1).'</th><td class="t" width="60">'.$debug['time'].' ms</td><td class="bold">'.shtmlspecialchars($debug['sql']).'</td></tr>';
	if(!empty($debug['info'])) {
		echo '<tr><td>Info</th><td>'.$debug['info'].'</td></tr>';
	}
	if(!empty($debug['explain'])) {
		echo '<tr><td class="t">Explain</td><td><table cellspacing="0"><tr class="firsttr"><td width="5%" class="firsttd">id</td><td width="10%">select_type</td><td width="12%">table</td><td width="5%">type</td><td width="20%">possible_keys</td><td width="10%">key</td><td width="8%">key_len</td><td width="5%">ref</td><td width="5%">rows</td><td width="20%">Extra</td></tr><tr>';
		foreach ($debug['explain'] as $ekey => $explain) {
			($ekey == 'id')?$tdclass = ' class="firsttd"':$tdclass='';
			if(empty($explain)) $explain = '-';
			echo '<td'.$tdclass.'>'.$explain.'</td>';
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
