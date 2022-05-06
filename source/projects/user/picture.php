<?php
if(! defined('IN_ZBC'))
	exit('Access Denied');

class CtrlPicture extends CtrlBase {

	var $id = 0;

	function __construct() {
		parent::__construct();
		$this->id = intval('0' . GET('id'));
		$this->setVar('id', $this->id);
		$this->mod = M('user.picture');
		if(! $this->MEMBER['iscorp']) {
			$this->message('无权使用此功能');
		}
	}

	function onIndex() {
		if($this->is_submit('list')) {
			$idata = array();
			foreach($_POST['remarkes'] as $k => $v) {
				$idata['remark'] = $v;
				$idata['order'] = $_POST['orderes'][$k];
				M('user.picture')->edit($k, $idata, false);
			}
			$this->message('提交成功', URL(array(
				'ctrl' => 'picture',
				'method' => 'index'
			)), true);
		} else {
			$this->setVar('list', $this->mod->get_list_by_where($this->MEMBER['uid'], 20));
			$this->setVar('listhash', $this->formhash('list'));
			$this->display('picture');
		}
	}

	function onUpload() {
		if(L('upload')->saveImage($_FILES['Filedata'], 'user_picture/' . $this->MEMBER['uid'])) {
			$data = array(
				'uid' => $this->MEMBER['uid'],
				'url' => L('upload')->url,
				'size' => $_FILES['Filedata']['size'],
				// 'type'=>$_FILES['Filedata']['type'],
				'remark' => $_FILES['Filedata']['name']
			);
			M('user.picture')->add($data);
			$data['src'] = RES_UPLOAD_URL . $data['url'];
			$data['img_id'] = DB()->insert_id();
			$this->echoJson($data);
		} else {
			$this->echoJson(L('upload')->error ? L('upload')->error : '没有文件被上传');
		}
	}

	function onDrop() {
		$id = GET('id') + 0;
		$this->echoJson(M('user.picture')->drop($id, $this->MEMBER['uid']));
	}
}
