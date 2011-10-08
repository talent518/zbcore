<?php
if(!defined('IN_SITE'))
exit('Access Denied');

class ModelMail extends ModelBase{
	var $cfg,$error;
	function getBody($tpl,$data){
		static $_tpl;
		if(!$_tpl){
			$_tpl=clone L('template');
			$_tpl->tpldir=TPL_DIR.'mail'.DIR_SEP;
			$_tpl->cachedir=TPL_CACHE_DIR.'mail'.DIR_SEP;
			$_tpl->datadir='mail'.DIR_SEP;
			$_tpl->setCall();
			//define('MAIL_TPL_URL',SITE_URL.TPL_URL.'mail/');
			define('MAIL_SKIN_URL',SITE_URL.TPL_URL.'mail/skin/');
		}
		foreach($data as $key=>$value)
			$_tpl->setVar($key,$value);
		$_tpl->display($tpl);
		$body=ob_get_contents();ob_end_clean();ob_start();
		return $body;
	}

	function send($email,$subject,$body,$attachments=array()){
		return $this->sends(array($email),$subject,$body,$attachments);
	}
	function sends($emails,$subject,$body,$attachments=array()){
		$cfg=M('setup')->get('email');
		$mail=L('net.smtp');
		$mail->CharSet=CFG()->charset;
		$mail->Encoding='base64';
		$mail->Mailer=$cfg['type']; // 使用SMTP
		$mail->SMTPSecure = $cfg['secure'];
		$mail->Host = $cfg['host'];
		$mail->Port = $cfg['port'];
		$mail->SMTPAuth = $cfg['auth'];
		$mail->Username = $cfg['username'];
		$mail->Password = $cfg['password'];
		$mail->From = $cfg['from'];
		$mail->FromName = $cfg['fromname'];
		foreach($emails as $email){
			if(is_array($email))
				extract($email);
			else
				$name='';
			$mail->AddAddress($email,$name);//收件人email和名字
		}
		if($cfg['reply'])
			$mail->AddReplyTo($cfg['reply'],$cfg['replyname']);
		$mail->WordWrap = 50; // 设定 word wrap
		foreach($attachments as $attachment)
			$mail->AddAttachment($attachment);//附件
		$mail->IsHTML(true); // 以HTML发送
		$mail->Subject = $subject;
		$mail->Body = $body; //HTML Body
		$mail->AltBody = "This is the body when user views in plain text format"; //纯文字时的Body
		if(!$mail->Send()){
			$this->error=$mail->ErrorInfo;
			return false;
		}else{
			$this->error='';
			return true;
		}
	}
	function queue(){
	}
}
