(function($) {
/* ------------------------------------------------------------------------
	LazyForm 1.0
	Copyright (c) 2009, ZhangPeng Chen, peng1988@gmail.com
------------------------------------------------------------------------- */
$.lazyform = $.lazyform || {};
$.extend($.lazyform, {
	_inputs : null,
	_selects: null,
	
	init: function() {
		_inputs = $('input[type=checkbox], input[type=radio]');
		_inputs.each($.lazyform._initInput);
		
		_selects = $('select');
		_selects.each($.lazyform._initSelect);
		
		$(document).click(function() {
			$('div.select div.open').removeClass().next('ul').hide();					 
		});
	},	
	
	_initInput: function() {
		var $self = $(this);
		var self = this;
		var radio = $self.is(':radio');
		
		var id = radio ? (self.type + '-' + self.name + '-' + self.id) : (self.type + '-' + self.id);
		var className = self.type + (self.checked ? '-checked' : '');
		var hover = false;
		
		var $span = $('<span />')
			.attr({
				'id': id,
				'class': className
			})
			.bind('mouseover mouseout', function() {
				hover = !hover;
				$span.attr('class', self.type + (self.checked ? '-checked' : '') + (hover ? '-hover' : ''));
			})
			.bind('click', function() {
				if(radio) {
					$('input[name=' + self.name + ']').each(function() {
						$('#' + self.type + '-' + self.name + '-' + this.id).attr('class', self.type);
					})
				}

				self.click();					
				$span.attr('class', self.type + (self.checked ? '-checked' : ''));
			});
		
		$self.addClass('hidden').before($span);
	},
	
	_$openSelect: null,
	_initSelect: function() {
		var $self = $(this);
		var self = this;
		
		var selectWidth = $self.width();
		var selectUlWidth = $self.width() - 2;
		
		var $select = $('<div />').attr('id', 'select-' + self.id).width(selectWidth).addClass('select');
		var $selectItem = $('<div />').append('<span />');
		var $selectItemText = $selectItem.children('span');
		var $selectUl = $('<ul />').width(selectUlWidth).hide();
		var $selectLi = null;
		var $hoverLi = null;
		
		$self.children().each(function() {
			var $tempLi = $('<li />').append(this.text);
			if(this.selected) {
				$tempLi.addClass('hover');
				$selectItemText.text(this.text);
				
				$selectLi = $tempLi;
				$hoverLi = $tempLi;
			}
			$selectUl.append($tempLi);
			
			$tempLi
				.bind('mouseover', function() {
					$hoverLi.removeClass();
					$tempLi.addClass('hover');
					$hoverLi = $tempLi;
				})
				.bind('click', function() {
					$self.children().each(function() {
						if($hoverLi && this.text == $hoverLi.text()) {
							$tempLi.addClass('hover');
							this.selected = true;
							
							$selectLi = $tempLi;
							$hoverLi = $tempLi;
						}						
					});					
					
					$selectItem.removeClass();
					$selectItemText.text($selectLi.text());
					$selectUl.hide();
				});
		});

		$selectItem.click(function(e) {
			if($.lazyform._$openSelect && $.lazyform._$openSelect != $select) {
				$('#' + $.lazyform._$openSelect.attr('id') + ' > div.open').removeClass().next('ul').hide();
			}			   								   
			$.lazyform._$openSelect = $select;
			
			$selectItem.toggleClass('open');
			if($selectItem.attr('class') == 'open') {
				if($hoverLi != $selectLi) {
					$hoverLi.removeClass();
					$selectLi.attr('class', 'hover');
					$hoverLi = $selectLi;
				}
				$selectUl.show();	
			} else {
				$selectUl.hide();	
			}

			e.stopPropagation();								
		});

		$select.append($selectItem);
		$select.append($selectUl);
		
		$self.hide().before($select);				
	}
});

$(document).ready(function() {
	$.lazyform.init();
});
})(jQuery);