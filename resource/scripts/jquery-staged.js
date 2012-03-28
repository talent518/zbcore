/*
功能:select的多级选择
作者:BaoCai Zhang
邮箱:talent518@live.cn
更新时间:2012年3月1日 17:24:29

实例:


例如：
	<select name="id" names="ids" multiple="false"></select>
	<script type="text/javascript">
	$('select[name=cat_id]').staged('json.php?do=test',{val:1,not:3,isStaged:false,isMultiple:false,keyName:'text',change:function(){}});//val为当前选择的id，not排除的id,isStaged是否为多级，isStaged的值会自动识别，keyName当前选项值为一个object时使用，change添加选择时的事件
	</script>
说明：
	json格式多级：{"k0":{"k1":"A","k2":"B","k3":"C","k4":"D"},"k1":{"k5":"E","k6":"F"}}

	json格式单级：{"k1":"A","k2":"B","k3":"C","k4":"D"}

	加k前缀的作用，保持原顺序

	select的names属性，会自动创建隐藏的input且name为names的值，意思为所选择的多级目录id列表（1,5）

	目前多选择功能，只能选择同级下的选择多个

	要想实现多级多选功能
	也可以通过用$('select[name=cat_id]').val();获取选择的值，进行保存等操作，即可实现。

*/


(function($){
	$.fn.staged=function(url,setting){
		if($(this).data('staged') && !url){
			return $(this).data('staged');
		}
		var $this=$(this);
		$.getJson(url,function(data){
			$this.each(function(){
				staged=$(this).data('staged');
				if(staged){
					staged.setData(data);
				}else{
					staged=new $.staged(this,data,setting);
				}
				staged.init();
			});
		});
		return this;
	};
	$.staged=function(select,data,setting){
		$(select).data('staged',this);
		this.sels=[select];

		this.defaults={keyName:'text'};
		this.defaults.isMultiple=$(select).attr('multiple');
		this.defaults.isStaged=(data['k'+0]?true:false);
		this.defaults.val=parseInt($(select).attr('val'));;
		this.defaults.not=parseInt($(select).attr('not'));;
		this.defaults.cls=$(select).attr('class');
		this.defaults.css=$(select).attr('style')?$(select).attr('style'):{};
		this.setting=$.extend({},this.defaults,setting);

		if(select.name)
			this.setting.name=select.name;
		if($(select).attr('names'))
			this.seled=$('<input name="'+$(select).attr('names')+'" type="hidden" value=""/>').insertBefore(select);
		this.setData(data);
	};
	$.staged.prototype={
		setData:function(data){
			this.data=data?(this.setting.isStaged?data:{k0:data}):{k0:{}};
		},
		init:function(){
			if(this.sels.length>1){
				return;
			}
			$(this.sels[0]).change(this.change);
			if(this.setting.isStaged){
				var grades=this.getGrades(0);
				if(grades.length){
					var grade=0,pid=grades.pop(),val;
					while(grades.length>0){
						val=grades.pop();
						this.setOption(grade,pid,val);
						pid=val;
						grade++;
					}
					if(grade==0)
						this.setOption(0,0,pid);
				}else{
					this.setOption(0,0,0);
				}
			}else{
				this.setOption(0,0,this.setting.val);
			}
		},
		getGrades:function(pid){
			var grades=[];
			for(var i in this.data['k'+pid]){
				i=parseInt(/\d+/.exec(i));
				if(this.setting.val==i)
					return [i];
				grades=this.getGrades(i);
				if(grades.length>0){
					grades.push(i);
					break;
				}
			}
			if(pid==0)
				grades.push(pid);
			return grades;
		},
		setOption:function(grade,pid,sid){
			if(grade!=0 && !this.isUsable(pid))
				return;
			if(!this.sels[grade]){
				this.sels[grade]=$('<select/>').data('staged',this).change(this.change).insertAfter(this.sels[this.sels.length-1]).get(0);
				if(this.setting.isStaged)
					this.sels[grade].onchange=this.change;
				this.sels[grade].multiple=this.setting.isMultiple;
				$(this.sels[grade]).addClass(this.setting.cls);
				if(typeof(this.setting.css)=='object')
					$(this.sels[grade]).css(this.setting.css);
				else
					this.sels[grade].style=this.setting.css;
			}
			var sel=$(this.sels[grade]),val,text;
			sel.data('grade',grade).data('pid',pid).empty();
			sel.append('<option value="0">'+(this.setting.isStaged?'无(作为'+(grade>0?'上':'一')+'级)':'请选择')+'</option>');
			for(val in this.data['k'+pid]){
				text=this.data['k'+pid][val];
				val=parseInt(/\d+/.exec(val));
				if(val!=this.setting.not)
					sel.append(sprintf('<option value="%s" %s>%s</option>',val,val==sid?'selected':'',typeof(text)=='object'?text[this.setting.keyName]:text));
			}
			if(this.setting.isStaged)
				sel.change();
		},
		isUsable:function(pid){
			if(!this.data['k'+pid])
				return false;
			var len=0;
			for(var val in this.data['k'+pid])
				if('k'+this.setting.not==val)
					return false;
			return true;
		},
		change:function(){
			var val=parseInt($(this).val()),grade=$(this).data('grade'),pid=$(this).data('pid'),staged=$(this).data('staged');
			$(staged.sels.slice(grade+1,staged.sels.length)).remove();
			staged.sels=staged.sels.slice(0,grade+1);
			if(val<=0){
				if(grade-1>=0)
					$(staged.sels).removeAttr('name').eq(grade-1).attr('name',staged.setting.name);
				else
					$(staged.sels).removeAttr('name').eq(grade).attr('name',staged.setting.name);
			}else{
				$(staged.sels).removeAttr('name').eq(grade).attr('name',staged.setting.name);
				staged.setOption(grade+1,val);
			}
			if(staged.seled){
				var sels=[];
				$(staged.sels).each(function(){
					var $val=parseInt($(this).val());
					if($val>0)
						sels.push(/\d+/.exec($val));
				});
				$(staged.seled).val(sels.join(','));
			}
			if($.isFunction(staged.setting.change)){
				staged.setting.change.call(this,staged.data['k'+pid]['k'+val]);
			}
		}
	};
})(jQuery);
