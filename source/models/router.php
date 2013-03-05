<?php
if(!defined('IN_ZBC'))
exit('Access Denied');

class ModelRouter extends ModelBase{
	protected $table='router';
	protected $priKey='rid';
	protected $order='`order` DESC,src DESC';

	protected $rules=array(
		'src'=>array(
			'required'=>true,
			'minlength'=>2,
			'query'=>'router'
		),
		'dest'=>array(
			'required'=>true,
			'minlength'=>2,
		)
	);
	protected $messages=array(
		'src'=>array(
			'required'=>'源规则不能为空',
			'minlength'=>'源规则太短',
		),
		'dest'=>array(
			'required'=>'目标规则不能为空',
			'minlength'=>'目标规则太短',
		)
	);

	private function drop_cache(){
		$cache=L('cache');
		$cache->dir='';
		$cache->name='router';
		$cache->drop();
	}

	function add($data,$isCheck=true,$isReplace=false){
		if(parent::add($data,$isCheck,$isReplace)){
			$this->drop_cache();
			return true;
		}
		return false;
	}
	function edit($id,$data,$isCheck=true,$isString=true){
		$this->rules['src']['query']=array($this->table,$this->priKey.'<>'.$id.' AND src=\''.$data['src'].'\'');
		if(parent::edit($id,$data,$isCheck,$isString)){
			$this->drop_cache();
			return true;
		}
		return false;
	}
	function drop($id){
		if(parent::drop($id)){
			$this->drop_cache();
			return true;
		}
		return false;
	}

	private function get_array_by_key(){
		$cache=L('cache');
		$cache->dir='';
		$cache->name='router';
		$cache->callback=array(DB(),'select',array(
			array(
				'table'=>$this->table,
				'field'=>'src,dest',
				'order'=>$this->order
			),
			SQL_SELECT_LIST,
			'src',
			'dest'
		));
		return $cache->get();
	}

	function encode($pcm,&$args=array()){
		static $rts;
		if(!is_array($rts)){
			$rts=$this->get_array_by_key();
		}
		foreach($rts as $src=>$dest){
			// 别名与参数规则
			if($src==$pcm){
				foreach($args as $k=>$v){
					$_dest=$dest;
					$dest=str_replace("[{$k}]",$v,$dest);
					if($dest!=$_dest){
						unset($args[$k]);
					}
				}
				return preg_replace("/\/\[[^\]]+\]/",'',$dest);
			}
			// *号规则
			$len=strlen($src);
			if($src{$len-1}=='*' && substr($src,0,$len-1)==substr($pcm,0,$len-1)){
				if(substr($dest,-1)=='*'){
					$url=substr($pcm,$len-1);
					foreach($args as $k=>$v){
						$url.=(empty($url)?'':'/').$k.'/'.urlencode($v);
					}
					return str_replace('*',$url,$dest);
				}
				list($args['proj'],$args['ctrl'],$args['method'])=explode('/',substr($pcm,1));
				foreach($args as $k=>$v){
					$_dest=$dest;
					$dest=str_replace("[{$k}]",$v,$dest);
					if($dest!=$_dest){
						unset($args[$k]);
					}
				}
				$src_arg_len=count(explode('/',$src))-1;
				switch($src_arg_len){
					case 1:
						unset($args['proj']);
						break;
					case 2:
						unset($args['proj'],$args['ctrl']);
						break;
					case 3:
						unset($args['proj'],$args['ctrl'],$args['method']);
						break;
				}
				return preg_replace("/\/\[[^\]]+\]/",'',$dest);
			}
		}
		return false;
	}

	function decode($router){
		static $rts;
		if(!is_array($rts)){
			$rts=$this->get_array_by_key();
		}
		for($i=0;$i<2;$i++){
			$_router=($i==0?substr($router,strpos($router,'/')):$router);
			foreach($rts as $src=>$dest){
				// 别名规则
				if($dest==$_router){
					return $src;
				}
				// *规则
				$len=strlen($dest);
				if($dest{$len-1}=='*' && substr($dest,0,$len-2)==substr($_router,0,$len-2)){
					if(substr($src,-1)=='*'){
						$url=substr($_router,$len-1);
						return str_replace('*',$url,$src);
					}
					return $src.substr($_router,$len-1);
				}
				if(strpos($dest,'[')!==false){
					$reg=preg_replace('`\[[^\]]+\]`','([^\/]+)',preg_quote($dest));
					$reg=str_replace('/\(','/(',$reg);
					if(preg_match('`'.$reg.'$`',$router,$vmatches)){
						preg_match_all("/\[([^\]]+)\]/",$dest,$kmatches);
						$args=array();
						foreach($kmatches[1] as $n=>$k){
							$args[$k]=$vmatches[$n+1];
						}
						if(substr($src,-2)=='/*'){
							$src=substr($src,0,-2);
						}
						if($args['proj']){
							$src.='/'.$args['proj'];
						}
						if($args['ctrl']){
							$src.='/'.$args['ctrl'];
						}
						if($args['method']){
							$src.='/'.$args['method'];
						}
						unset($args['proj'],$args['ctrl'],$args['method']);
						foreach($args as $k=>$v){
							$src.='/'.$k.'/'.$v;
						}
						/*ob_clean();
						var_dump($dest,$src);
						exit;*/
						return $src;
					}
				}
			}
		}
		return false;
	}
}
