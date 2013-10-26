<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

define('STRING_RAND_EN',1);
define('STRING_RAND_NUM',2);
define('STRING_RAND_BOTH',3);
define('STRING_RAND_CN',4);

class LibString{
	private $regs=array(
		'utf-8'	=>"/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/",
		'gb2312'=>"/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/",
		'gbk'	=>"/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/",
		'big5'	=>"/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|[\xa1-\xfe])/"
	);

	//�ж��ַ����Ƿ����
	function in_string($haystack,$needle){
		return !(strpos($haystack,$needle)===false);
	}

    //�������UUID
	 function uuid(){
        $charid = md5(uniqid(mt_rand(), true));
        return chr(123)
               .substr($charid, 0, 8).chr(45)
               .substr($charid, 8, 4).chr(45)
               .substr($charid,12, 4).chr(45)
               .substr($charid,16, 4).chr(45)
               .substr($charid,20,12)
               .chr(125);//{26de6401-4629-8303-0611-a3f5d4d88a3e}
	}

	//����Guid����
	function keyGen(){
		return str_replace('-','',substr(com_create_guid(),1,-1));
	}

	//����ַ����Ƿ���UTF8����
	function is_utf8($str){
		$c=0;$b=0;
		$bits=0;
		$len=strlen($str);
		for($i=0; $i<$len; $i++){
			$c=ord($str[$i]);
			if($c > 128){
				if(($c >= 254)) return false;
				elseif($c >= 252) $bits=6;
				elseif($c >= 248) $bits=5;
				elseif($c >= 240) $bits=4;
				elseif($c >= 224) $bits=3;
				elseif($c >= 192) $bits=2;
				else return false;
				if(($i+$bits) > $len) return false;
				while($bits > 1){
					$i++;
					$b=ord($str[$i]);
					if($b < 128 || $b > 191) return false;
					$bits--;
				}
			}
		}
		return true;
	}

	function len($str,$charset="utf-8"){
		if(function_exists("mb_strlen"))
			return mb_strlen($str,$charset);
		elseif(function_exists('iconv_strlen')) {
			return iconv_strlen($str,$charset);
		}
		preg_match_all($this->regs[$charset],$str,$match);
		return count($match[0]);
	}

	function cut($str,$start=0,$length,$charset="utf-8",$suffix='��'){
		if($start==0 && $this->len($str)<=$length)
			return $str;
		if(function_exists("mb_substr"))
			return mb_substr($str, $start, $length, $charset).($start==0?$suffix:'');
		elseif(function_exists('iconv_substr')) {
			return iconv_substr($str,$start,$length,$charset).($start==0?$suffix:'');
		}
		preg_match_all($this->regs[$charset],$str,$match);
		return implode("",array_slice($match[0],$start,$length)).($start==0?$suffix:'');
	}

	function rand($len=6,$type=0,$charset='utf-8'){
		switch($type){
			case STRING_RAND_EN:
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
				break;
			case STRING_RAND_NUM:
				$chars= str_repeat('0123456789',3);
				break;
			case STRING_RAND_BOTH:
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
				break;
			case STRING_RAND_CN:
				$chars = '�����ҵ�������ʱҪ��������һ�ǹ�������巢�ɲ���ɳ��ܷ������˲����д�����������Ϊ����������ѧ�¼��ظ���ͬ����˵�ֹ����ȸ�����Ӻ������С��Ҳ�����߱������������ʵ�Ҷ������ˮ������������������ʮս��ũʹ��ǰ�ȷ���϶�·ͼ�ѽ�������¿���֮��ӵ���Щ�������¶�����������˼�����ȥ�����������ѹԱ��ҵ��ȫ�������ڵ�ƽ��������ëȻ��Ӧ�����������ɶ������ʱ�չ�������û���������ϵ������Ⱥͷ��ֻ���ĵ����ϴ���ͨ�����Ͽ��ֹ�����������ϯλ����������ԭ�ͷ�������ָ��������ںܽ̾��ش˳�ʯǿ�������Ѹ���ֱ��ͳʽת�����о���ȡ������������־�۵���ôɽ�̰ٱ��������汣��ί�ָĹܴ�������֧ʶ�������Ϲ�רʲ���;�ʾ������ÿ�����������Ϲ����ֿƱ�������Ƹ��������������༯������װ����֪���е�ɫ����ٷ�ʷ����������֯�������󴫿ڶϿ��ɾ����Ʒ�вβ�ֹ��������ȷ������״��������Ŀ����Ȩ�Ҷ����֤��Խ�ʰ��Թ�˹��ע�첼�����������ر��̳�������ǧʤϸӰ�ð׸�Ч���ƿ��䵶Ҷ������ѡ���»������ʼƬʩ���ջ�������������ҩ����Ѵ��ʿ���Һ��׼��ǽ�ά�������������״����ƶ˸������ش幹���ݷǸ���ĥ�������ʽ���ֵ��̬���ױ�������������̨���û������ܺ���ݺ����ʼ��������Ͼ��ݼ���ҳ�����Կ�Ӣ��ƻ���Լ�Ͳ�ʡ���������ӵ۽�����ֲ������������ץ���縱����̸Χʳ��Դ�������ȴ����̻����������׳߲��зۼ������濼�̿�������ʧ��ס��֦�־����ܻ���ʦ������Ԫ����ɰ�⻻̫ģƶ�����ｭ��Ķľ����ҽУ���ص�����Ψ�们վ�����ֹĸ�д��΢�Է�������ĳ�����������൹�������ù�Զ���Ƥ����ռ����Ȧΰ��ѵ�ؼ��ҽ��ƻ���������ĸ�����ֶ���˫��������ʴ����˿Ůɢ��������Ժ�䳹����ɢ�����������������Ѫ��ȱ��ò�����ǳ���������������̴���������������Ͷ��ū����ǻӾഥ�����ͻ��˶��ٻ����δͻ�ܿ���ʪƫ�Ƴ�ִ����կ�����ȶ�Ӳ��Ŭ�����Ԥְ������Э�����ֻ���ì������ٸ�������������ͣ����Ӫ�ո���Ǯ��������ɳ�˳��ַ�е�ذ����İ��������۵��յ���ѽ�ʰɿ��ֽ�������������ĩ������ڱ������������������𾪶ټ�����ķ��ɭ��ʥ���մʳٲ��ھؿ��������԰ǻ�����������������ӡ�伱�����˷�¶��Ե�������������Ѹ��������ֽҹ������׼�����ӳ��������ɱ���׼辧�尣ȼ��������ѿ��������̼��������ѿ����б��ŷ��˳������͸˾Σ������Ц��β��׳����������������ţ��Ⱦ�����������Ƽ�ֳ�����ݷô���ͭ��������ٺ�����Դ��ظ���϶¯����úӭ��ճ̽�ٱ�Ѯ�Ƹ�������Ը���������̾䴿������������³�෱�������׶ϣ�ذܴ�����ν�л��ܻ���ڹ��ʾ����ǳ���������Ϣ������������黭�������������躮ϲ��ϴʴ���ɸ���¼������֬ׯ��������ҡ���������������Ű²��ϴ�;�������Ұ�ž�ıŪ�ҿ�����ʢ��Ԯ���Ǽ���������Ħæ�������˽����������������Ʊܷ�������Ƶ�������Ҹ�ŵ����Ũ��Ϯ˭��л�ڽ���Ѷ���鵰�պ������ͽ˽������̹����ù�����ո��伨���ܺ���ʹ�������������ж�����׷���ۺļ���������о�Ѻպ��غ���Ĥƪ��פ������͹�ۼ���ѩ�������������߲��������ڽ������˹�̿������������ǹ���ð������Ͳ���λ�����Ϳζ����Ϻ�½�����𶹰�Ī��ɣ�·쾯���۱�����ɶ���ܼ��Ժ��浤�ɶ��ٻ���ϡ���������ǳӵѨ������ֽ����������Ϸ��������ò�����η��ɰ���������ˢ�ݺ���������©�������Ȼľ��з������Բ����ҳ�����ײ����ȳ����ǵ������������ɨ������оү���ؾ����Ƽ��ڿ��׹��ð��ѭ��ף���Ͼ����������ݴ���ι�������Ź�ó����ǽ���˽�ī������ж����������ƭ�ݽ�';
				break;
			default:
				// Ĭ��ȥ�������׻������ַ�oOLl������01��Ҫ�����ʹ��addChars����
				$chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
				break;
		}
		if($type!=STRING_RAND_CN) {
			$str     =   substr(str_shuffle(str_repeat($chars,ceil($len/strlen($chars)))),0,$len);
		}else{
			// ���������
			$str ='';
			for($i=0;$i<$len;$i++){
			  $str.= $this->cut($chars,floor(mt_rand(0,$this->len($chars,$charset)-1)),1);
			}
		}
		return $str;
	}
}
