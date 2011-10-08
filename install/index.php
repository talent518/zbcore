<?php
if(!defined('IN_SITE'))
	exit('Access Denied');

class CtrlIndex extends CtrlBase{
	var $lockfile;
	function __construct(){
		parent::__construct();
		$this->lockfile=CTRL_DIR.'install.lock';
		if(file_exists($this->lockfile))
			$this->message('请删除文件“'.$this->lockfile.'”，然后重试安装！');
	}
	function onIndex(){
		$this->display('index');
	}
	function onChkenv(){
		$this->display('chkenv');
	}
	function onCfg(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$mct=$_POST['db']['pconnect']?'mysql_pconnect':'mysql_connect';

			$db=L('db.'.$_POST['db']['type']);

			$db->charset=preg_replace("/[^a-z0-8]+/","",CFG()->charset);
			foreach($_POST['db'] as $k=>$v)

				$db->$k=$v;
			if(!$db->connect(true))
				$this->message('mysql连接失败！');
			if(!$db->sdb($_POST['db']['name']) && !$db->cdb($_POST['db']['name']))
				$this->message('数据库不存在！');
			$c=L('io.file')->read(SRC_DIR.'config.php');
			foreach($_POST['db'] as $k=>$v){
				$v=eregi("^TRUE|FALSE$",$v)?"{$v}":"'{$v}'";
				$c=preg_replace("/(var\s+\\\${$k}\s*\=\s*)[^\;]+\;/ie","'\\1'.\$v.';'",$c);
			}
			foreach($_POST['cfg'] as $k=>$v){
				CFG()->$k=$v;
				$v=eregi("^([0-9]+)|TRUE|FALSE$",$v)?"{$v}":"'{$v}'";
				$c=preg_replace("/(var\s+\\\${$k}\s*\=\s*)[^\;]+\;/ie","'\\1'.\$v.';'",$c);
			}
			CFG()->urlFormat='base';
			if(L('io.file')->write(SRC_DIR.'config.php',$c))
				$this->message('配置成功!',URL(array('method'=>'db','adm[email]'=>$_POST['adm']['email'],'adm[password]'=>$_POST['adm']['password'])),TRUE);
			else
				$this->message('配置失败，可能是文件(/source/config.php)不可写!');
		}else{
			$this->display('cfg');
		}
	}
	function onDb(){
		if(GET('begin')){
			header('Content-Type:text/html; charset='.CFG()->charset);
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=CFG()->charset?>" /><style type="text/css">
html,body{font-size:12px;}
b{margin-left:1em;color:green;font-weight:normal;padding-left:1em;background:transparent url(<?=SKIN_URL?>images/right.gif) left center no-repeat;}
strong{margin-left:1em;color:red;font-weight:normal;padding-left:1em;background:transparent url(<?=SKIN_URL?>images/wrong.gif) left center no-repeat;}
</style>
<?php
			M('db')->import(CTRL_DIR.'db.sql',TRUE);
			$adm=GET('adm');
			$user=array('gid'=>1,'username'=>'admin','email'=>$adm['email'],'password'=>$adm['password']);
			echo '<br/>管理设置';
			echo M('user')->edit(1,$user)?'<b>成功!</b>':'<strong>失败!</strong>';
			echo '<script>parent.document.getElementById("nextStep").disabled=false;</script>';
			ob_flush();
			flush();
		}else{
			$this->setVar('adm',GET('adm'));
			$this->display('db');
		}
	}
	function onFinish(){
		@touch($this->lockfile);
		$this->display('finish');
	}
}
