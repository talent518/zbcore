;(function($){

$.multiSelect={};

//初始化
$.multiSelect.init=function(){
	$(document.body).append('<div id="multiSelect-mask"></div><div id="multiSelect"><div id="multiSelect-header"><div id="multiSelect-close">关闭</div><div id="multiSelect-title"></div></div><div id="multiSelect-content"></div><div id="multiSelect-btn"><input name="empty" type="button" value="清空"/><input name="reset" type="button" value="还原"/><input name="ok" type="button" value="确定"/><input name="cancel" type="button" value="取消"/></div></div>');
	$('#multiSelect').hide().draggable({handle:'#multiSelect-header'});
	$('#multiSelect-close').click($.multiSelect.close);
	$('#multiSelect-mask').hide().fadeTo(0,0).mousedown(function(){
		$('#multiSelect').hide();
	}).mouseup(function(){
		$('#multiSelect').show();
	});
	$(window).resize(function(event){
		if($('#multiSelect-mask').is(':visible')){
			$('#multiSelect').float('center');
			$('#multiSelect-mask').css({width:$(window).width(),height:$(window).height()}).float('center');
		}
	});
	if(isIE6){
		$(window).scroll(function(){
			$('#multiSelect-mask,#multiSelect').float('center');
		});
	}
};

//打开窗口
$.multiSelect.open = function(options,callback){
	var $this={};
	$.scroll(false);
	$('#multiSelect').width(options.width);
	$('#multiSelect').height(options.height);
	$('#multiSelect-title').html(options.title);
	$('#multiSelect-mask').width($(window).width()).height($(window).height()).show().fadeTo(0,0.0).fadeTo(200,0.50);
	if(options.data){
		var n=0;
		var selected=$('<div class="selected"><h4>已选择</h4></div>').appendTo('#multiSelect-content');
		var ul=$('<ul><h4>'+options.title+'</h4></ul>').appendTo('#multiSelect-content'),li,p,w=parseInt((options.width-20)/options.multiRows);
		$this.click=function(){
			if($(this).is(':checked') && !$(this).is(':disabled')){
				if(selected.find('input[value='+$(this).val()+']').size()==0){
					if($(this).attr('name')=='parent'){
						selected.children('p').remove();
					}
					if(selected.children('p').size()==5){
						$(this).attr('checked',false);
						alert('最多只能选择五项');
						return;
					}
					var $input=$(this).parent().clone().width(w).removeClass().appendTo(selected).click(function(){
						var $i=$(this).children('input');
						ul.find('input[value='+$i.val()+']').attr('checked',false).change().parent().click();
						$(this).remove();
					}).children('input');
					$input.attr('oldname',$input.attr('name')).attr('name','selected');
				}else{
					$(this).attr('checked',true);
				}
			}else{
				selected.find('input[value='+this.value+']').parent().remove();
			}
		};
		ul.css({width:options.width-16,margin:'10px auto'});
		selected.css({width:options.width-16,margin:'10px auto'});
		for(var i in (options.grade==1?options.data:options.data[0])){
			if(n%options.multiRows==0){
				li=$('<li></li>').width(options.width-20).appendTo(ul);
			}
			p=$("<p><input name=parent type=radio value='"+options.data[0][i].id+"'/><label>"+options.data[0][i].cntitle+"</label></p>");
			p.appendTo(li).width(w).find('input').click($this.click);
			if(selected.find('input[value='+options.data[0][i].id+']').size()>0){
				p.children('input').attr('checked',true);
			}
			p.click(function(){
				var $input=$(this).find('input'),parent=selected.find('input[oldname=parent]'),k=parseInt($input.val());
				if(parent.size()>0 && parent.val()!=k){
					return alert('您只能选择一项父项！');
				}
				var active=$('<li class="active"></li>').width(options.width-18);
				ul.find('li.active').remove();
				ul.find('p.active').removeClass('active').width(w);
				$(this).width(w-2);
				$(this).addClass('active').parent('li').after(active);
				var $selval=selected.find('input').map(function(){return this.value});
				for(var j in options.data[k]){
					$("<p><input name=children type=checkbox value='"+options.data[k][j].id+"'"+($input.attr('checked')==true?' checked disabled':($.inArray(options.data[k][j].id,$selval)>-1?' checked':''))+"/><label>"+options.data[k][j].cntitle+"</label></p>").appendTo(active).width(w);
				}
				active.find('input[type=checkbox]').click($this.click).change($this.click).change();
				$('#multiSelect').float('center');
			});
			n++;
		}
		$('#multiSelect').float('center');
		setTimeout(function(){
			var loc={};
			loc.left=$('#multiSelect').css('left');
			loc.top=$('#multiSelect').css('top');
			loc.width=$('#multiSelect').width();
			loc.height=$('#multiSelect').height();
			$('#multiSelect').show().css({left:$(window).width()/2,top:$(window).height()/2,width:0,height:0}).animate(loc,200,function(){
				$('#multiSelect').height(options.height);
			});
		},20);
	}
	$('#multiSelect-btn input[name=empty]').unbind('click').bind('click',function(){
		selected.children('p').remove();
		ul.find('input').attr('checked',false);
		ul.find('p.active').click();
	});
	$('#multiSelect-btn input[name=reset]').unbind('click').bind('click',function(){
		selected.children('p').remove();
		var $load=$.multiSelect.get(options),$i;
		for(var i in $load){
			$i=ul.find('input[value='+$load[i].id+']');
			p=$('<p><input oldname="'+$i.attr('name')+'" name="selected" type="'+($i.size()>0?'radio':'checkbox')+'" value="'+$load[i].id+'" checked/><label>'+$load[i].cntitle+'</label></p>');
			p.width(w).appendTo(selected).click(function(){
				var $i=$(this).children('input');
				ul.find('input[value='+$i.val()+']').attr('checked',false).change().parent().click();
				$(this).remove();
			});
			$i.attr('checked',true).parent('p').click();
		}
	}).click();
	$('#multiSelect-btn input[name=ok]').unbind('click').bind('click',function(){
		if(selected){
			var $selval=[],$seltxt=[];
			selected.find('p').each(function(){
				$selval.push($(this).children('input').val());
				$seltxt.push($(this).children('label').text());
			});
			callback($selval,$seltxt);
		}else{
			callback();
		}
		$.multiSelect.close();
	});
	$('#multiSelect-btn input[name=cancel]').unbind('click').bind('click',$.multiSelect.close);
};

//关闭窗口
$.multiSelect.close = function(){
	$.scroll(true);
	var loc={};
	loc.left=$('#multiSelect').css('left');
	loc.top=$('#multiSelect').css('top');
	loc.width=$('#multiSelect').width();
	loc.height=$('#multiSelect').height();
	$('#multiSelect').animate({left:$(window).width()/2,top:$(window).height()/2,width:0,height:0},200,'',function(){
		$('#multiSelect-mask').fadeOut(200);
		$('#multiSelect').css(loc).hide();
		$('#multiSelect-content').html('');
		$('#multiSelect-title').html('');
	});
};

$.multiSelect.get=function(options){
	var data=options.data,value=options.value,$load=[];
	switch(options.grade){
		case(1):{
			for(var i in data){
			}
			break;
		}case(2):{
			var k=0;
			for(var i in data[0]){
				k=data[0][i].id;
				if($.inArray(k,value)>-1 && $load.length<5){
					$load.push(data[0][i]);
				}
				for(var j in data[k]){
					if($.inArray(data[k][j].id,value)>-1 && $load.length<5){
						$load.push(data[k][j]);
					}
				}
			}
			break;
		}case(3):{
			break;
		}default:{
			break;
		}
	}
	return $load;
};

$.fn.multiSelect=function(options){
	var types={};
	types.place={width:500,height:'auto',title:'请选择地区',grade:2,multiRows:6};
	types.jobstype={width:800,height:'auto',title:'请选择职位类别',grade:2,multiRows:5};

	if(typeof(options)=='string'){
		options=$.extend(types[options],{type:options});
	}else if(typeof(options)=='object'){
		options=$.extend(types[options.type],options);
	}else{
		return this;
	}
	return this.each(function(){
		var settings=$.extend(options,{value:$(this).val().split(',')});
		var $this=$('<span class="multiSelect-source '+$(this).attr('class')+'" style="'+$(this).attr('css')+'"></span>');
		var $input=$('<input id="'+$(this).attr('id')+'" name="'+$(this).attr('name')+'" type="hidden" value="'+$(this).val()+'" style="display:none"/>');
		var $label=$('<label>'+settings.title+'</label>').width('auto').css('cursor','pointer');
		$this.append($label).append($input).css('cursor','pointer');
		$(this).replaceWith($this);
		$.ajax({
			type:'GET',
			dataType:'json',
			data:{},
			url:'personal.php?do=json&op='+settings.type,
			success:function(data,textStatus){
				settings.data=data;
				$this.click(function(){
					settings.value=$input.val().split(',');
					$.multiSelect.open(settings,function($selval,$seltxt){
						if($selval.length>0 && $seltxt.length>0){
							$input.val($selval.join(','));
							$label.text($seltxt.join(','));
						}else{
							$input.val('');
							$label.text(settings.title);
						}
					});
				});
				var $load=$.multiSelect.get(settings),$txt=[];
				for(var i in $load){
					$txt.push($load[i].cntitle);
				}
				if($load.length>0){
					$label.text($txt.join(',')).width('auto');
				}else{
					$label.text(settings.title);
				}
			},error:function(){
				$label.text('请求错误！').css('cursor','not-allowed');
				$this.css('cursor','not-allowed');
			}
		});
	});
};

$($.multiSelect.init);

})(jQuery);