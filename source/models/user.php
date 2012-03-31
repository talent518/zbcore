<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelUser extends ModelBase{
	protected $table='user';
	protected $priKey='uid';
	protected $order='`uid` DESC';
	protected $rules=array(
		'gid'=>array(
			'required'=>true,
			'integer'=>true,
			'min'=>1
		),
		'username'=>array(
			'required'=>true,
			'rangelength'=>'3,25',
			'username'=>true,
			'query'=>'user'
		),
		'password'=>array(
			'required'=>true,
			'minlength'=>6,
			'password'=>true,
		),
		'email'=>array(
			'required'=>true,
			'email'=>true,
			'query'=>'user'
		),
	);
	protected $messages=array(
		'gid'=>array(
			'required'=>'用户组不能为空',
			'integer'=>'用户组不是一个整数',
			'min'=>'请选择用户组'
		),
		'username'=>array(
			'required'=>'用户名不能为空',
			'rangelength'=>'用户名的长度只能在{0}和{1}之间',
			'query'=>'用户名“{0}”已经存在'
		),
		'password'=>array(
			'required'=>'密码不能为空',
			'minlength'=>'密码长度不能少于{0}字',
		),
		'email'=>array(
			'required'=>'电子邮件不能为空',
			'query'=>'电子邮件“{0}”已经存在'
		),
	);
	private $cookie='auth',$timeout=1440;
	var $MEMBER=array();
	function register(&$data){
		$data['gid']=M('setup')->get('user','gid');
		if($this->add($data)){
			$this->login($data['username'],$data['password']);
			return true;
		}
		return false;
	}

	function add(&$data){
		if($this->check($data)){
			extract($data);
			$data['salt']=L('string')->rand(12,STRING_RAND_BOTH);
			$data['password']=md5(md5($password).$data['salt']);
			$data['regip']=CLIENT_IP;
			$data['regtime']=TIMESTAMP;
			DB()->insert('user',$data);
			$uid=DB()->insert_id();
			$code=encodestr(sprintf("%d\t%s\t%d",$uid,$email,TIMESTAMP));
			M('mail')->send(
				array('email'=>$email,'name'=>$username),
				"注册帐户，已成功！验证邮件！",
				array(
					'register',
					array(
						'uid'=>$uid,
						'username'=>$username,
						'password'=>$password,
						'email'=>$email,
						'code'=>$code
					)
				)
			);
			return true;
		}
		return false;
	}
	function edit($id,&$data){
		if(!$user=$this->get_by_uid($id)){
			$this->error='编辑的用户不存在！';
			return false;
		}

		$this->rules['username']['query']=array('user','uid<>'.$id.' AND `username`=\''.$data['username'].'\'');
		unset($this->rules['password']['required']);
		$this->rules['email']['query']=array('user','uid<>'.$id.' AND `email`=\''.$data['email'].'\'');

		if($id==1){
			$data['gid']=$data['ismanage']=$data['protected']=1;
		}

		if($this->check($data)){
			if(empty($data['password'])){
				if(isset($data['password']))
					unset($data['password']);
			}else{
				$data['salt']=L('string')->rand(12,STRING_RAND_BOTH);
				$data['password']=md5(md5($data['password']).$data['salt']);
			}
			if($user['email']!=$data['email'] && M('setup')->get('user','verifyemail')){
				$data['verifyemail']=0;
				$this->MEMBER=array_merge($this->MEMBER,$data);
				$this->svmail();
			}
			DB()->update('user',$data,'uid='.$id);
			return true;
		}
		return false;
	}
	function drop($id=0){
		if($id==1){
			$this->error='受保护用户不可删除！';
			return false;
		}elseif($user=$this->get($id)){
			if($user['protected']){
				$this->error='受保护用户不可删除！';
				return false;
			}
			DB()->delete('user','uid='.$id);
			DB()->delete('user_datum','uid='.$id);
			return true;
		}else{
			$this->error='用户不存在！';
			return false;
		}
	}

	function login($username,$password,$ismanage=0){
		$user=(L('validate')->email($username)?$this->get_by_email($username):$this->get_by_username($username));

		if($user['uid'] && (!$ismanage || $user['ismanage']==$ismanage)){
			if($user['password']==md5(md5($password).$user['salt'])){
				$datas=array(
					'lastloginip'=>CLIENT_IP,
					'lastlogintime'=>TIMESTAMP
				);
				DB()->update('user',$datas,'uid='.$user['uid']);
				$this->MEMBER=$user;
				L('cookie')->set($this->cookie.$ismanage,"{$user[uid]}\t{$user[password]}",$this->timeout);
				return 1;
			}else{
				return -1;
			}
		}else{
			L('cookie')->drop($this->cookie.$ismanage);
			return 0;
		}
	}
	function logout($ismanage=0){
		L('cookie')->drop($this->cookie.$ismanage);
	}
	function checklogin($ismanage=0){
		static $checked;
		if(!$checked)
			$checked=array();
		if(!isset($checked[$ismanage])){
			$checked[$ismanage]=false;
			@list($uid,$password)=explode("\t",L('cookie')->get($this->cookie.$ismanage));
			$uid=intval($uid);
			$password=saddslashes($password);
			if($uid && $password){
				$sqls=array(
					'table'=>'user',
					'field'=>'*',
					'where'=>"uid=$uid AND password='$password'",
					'limit'=>1
				);
				if($this->MEMBER=M('user')->get_by_where("uid=$uid AND password='$password'")){
					$checked[$ismanage]=true;
					$datas=array(
						'lastactivetime'=>TIMESTAMP
					);
					DB()->update('user',$datas,'uid='.$uid);
				}else
					L('cookie')->drop($this->cookie.$ismanage);
			}else{
				L('cookie')->drop($this->cookie.$ismanage);
				if(!$ismanage)
					L('cookie')->drop($this->cookie.'1');
			}
		}
		return $checked[$ismanage];
	}

	function get_by_uid($uid=0){
		return $this->get($uid);
	}
	function get_by_username($username){
		$username=addslashes($username);
		return $this->get_by_where("username='$username'");
	}
	function get_by_email($email){
		if(!L('validate')->email($email))
			return false;
		$email=addslashes($email);
		return $this->get_by_where("email='$email'");
	}

	function get_name2id($usernames) {
		$usernames = simplode(saddslashes($usernames));
		return DB()->select(array(
			'table'=>'user',
			'field'=>'uid',
			'where'=>"username IN($usernames)"
		),1);
	}

	function vmail($code){
		if($data=decodestr($code)){
			@list($uid,$email,$time)=explode("\t",$data);
			if($uid>0 && $time>0 && ($user=$this->get_by_uid($uid))){
				if($time+86400<TIMESTAMP)
					$this->error='Email验证超时！请在一天内进行验证！';
				elseif(L('validate')->email($email) && $user['email']==$email){
					DB()->update('user',array('email'=>$email,'verifyemail'=>1),'uid='.$uid);
					return true;
				}else
					$this->error='错的厉害呀，好厉害的呀！';
			}
		}else
			$this->error='验证码错误！';
		return false;
	}

	function svmail(){
		$code=encodestr(sprintf("%d\t%s\t%d",$this->MEMBER['uid'],$this->MEMBER['email'],TIMESTAMP));
		$return=M('mail')->send(
			array('email'=>$this->MEMBER['email'],'name'=>$this->MEMBER['username']),
			"验证邮件！",
			array(
				'vmail',
				array(
					'code'=>$code
				)
			)
		);
		$this->error=M('mail')->error;
		return $return;
	}
}
