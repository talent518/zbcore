<?php
define('IN_PROJ','install');
define('CTRL_DIR',dirname(__FILE__).DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR);

define('IN_ZBC',TRUE);
define('IN_AJAX',FALSE);

define('ROOT_DIR',dirname(__FILE__).DIRECTORY_SEPARATOR);
define('ROOT_URL',substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],'/')+1));

define('TPL_DIR',ROOT_DIR.'tpls'.DIRECTORY_SEPARATOR);//模板目录
define('TPL_URL',ROOT_URL.'tpls/');//模板URL

define('CACHE_DIR',ROOT_DIR.'cache'.DIRECTORY_SEPARATOR);//缓存目录

define('RES_DIR',ROOT_DIR.'resource'.DIRECTORY_SEPARATOR);//资源目录
define('RES_URL',ROOT_URL.'resource/');//资源URL

include_once(ROOT_DIR.'source/loader.php');

class CtrlInstall extends ZBCore{
	var $lockfile;
	function __construct(){
		parent::__construct();
		$tpl=L('template');
		$tpl->tpldir=CTRL_DIR.'tpls'.DIR_SEP;
		$tpl->cachedir=CTRL_DIR.'cache'.DIR_SEP;
		$tpl->datadir='install'.DIR_SEP;
		define('SKIN_URL',ROOT_URL.'install/tpls/');

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
			$c=L('io.file')->read(file_exists(SRC_DIR.'config.php')?SRC_DIR.'config.php':SRC_DIR.'config.sample.php');
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
				$this->message('配置成功!','install.php?method=db&adm[email]='.$_POST['adm']['email'].'&adm[password]='.$_POST['adm']['password'],TRUE);
			else
				$this->message('配置失败，可能是文件(/source/config.php)不可写!');
		}else{
			$this->setVar('cfg',CFG());
			$this->display('cfg');
		}
	}
	function onDb(){
		if($_GET['begin']){
			header('Content-Type:text/html; charset='.CFG()->charset);
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=CFG()->charset?>" /><style type="text/css">
html,body{font-size:12px;}
b{margin-left:1em;color:green;font-weight:normal;padding-left:1em;background:transparent url(<?=SKIN_URL?>images/right.gif) left center no-repeat;}
strong{margin-left:1em;color:red;font-weight:normal;padding-left:1em;background:transparent url(<?=SKIN_URL?>images/wrong.gif) left center no-repeat;}
</style>
<?php
			M('db')->import(CTRL_DIR.'db.sql',TRUE);
			$adm=$_GET['adm'];
			$user=array('gid'=>1,'username'=>'admin','email'=>$adm['email'],'password'=>$adm['password']);
			echo '<br/>管理设置';
			echo M('user')->edit(1,$user)?'<b>成功!</b>':'<strong>失败!</strong>';
			echo '<script type="text/javascript">
			parent.document.getElementById("nextStep").disabled=false;
			parent.window.down_move_func=function(){};
			</script>';
			//ob_flush();
			flush();
		}else{
			$this->setVar('adm',$_GET['adm']);
			$this->display('db');
		}
	}
	function onFinish(){
		@touch($this->lockfile);
		$this->display('finish');
	}
}

$ctrl_obj=new CtrlInstall;

$method=$_GET['method']?$_GET['method']:'index';
$method='on'.GN($method);

//ob_start();

if(method_exists($ctrl_obj,$method))
	$ctrl_obj->$method();
else
	exit(IS_DEBUG?"controller <b>'CtrlInstall'</b> no method <b>'$method'</b>!":0);

exit;
