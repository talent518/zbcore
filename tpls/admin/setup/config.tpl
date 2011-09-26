<h1 class="head">
	<a href="{link ctrl=setup method=site.key}">更新网站安全密钥</a>
	网站设置
</h1>
<form id="configform" class="formtable" action="{link ctrl=setup method=config}" method="post">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>网站标题：</th>
				<td><input name="config[sitetitle]" type="text" value="{$config.sitetitle}" size="50" /></td>
			</tr>
			<tr>
				<th>网站关键词：</th>
				<td><input name="config[sitekeywords]" type="text" value="{$config.sitekeywords}" size="30" /></td>
			</tr>
			<tr>
				<th>网站描述：</th>
				<td><textarea name="config[sitedescription]" cols="80" rows="3">{$config.sitedescription}</textarea></td>
			</tr>
			<tr>
				<th>时区：</th>
				<td>
					<select name="config[timeoffset]">
						<option value="-12"{if $config.timeoffset==-12} selected{/if}>(GMT -12:00) Eniwetok, Kwajalein</option>
						<option value="-11"{if $config.timeoffset==-11} selected{/if}>(GMT -11:00) Midway Island, Samoa</option>
						<option value="-10"{if $config.timeoffset==-10} selected{/if}>(GMT -10:00) Hawaii</option>
						<option value="-9"{if $config.timeoffset==-9} selected{/if}>(GMT -09:00) Alaska</option>
						<option value="-8"{if $config.timeoffset==-8} selected{/if}>(GMT -08:00) Pacific Time (US &amp; Canada), Tijuana</option>
						<option value="-7"{if $config.timeoffset==-7} selected{/if}>(GMT -07:00) Mountain Time (US &amp; Canada), Arizona</option>
						<option value="-6"{if $config.timeoffset==-6} selected{/if}>(GMT -06:00) Central Time (US &amp; Canada), Mexico City</option>
						<option value="-5"{if $config.timeoffset==-5} selected{/if}>(GMT -05:00) Eastern Time (US &amp; Canada), Bogota, Lima, Quito</option>
						<option value="-4"{if $config.timeoffset==-4} selected{/if}>(GMT -04:00) Atlantic Time (Canada), Caracas, La Paz</option>
						<option value="-3.5"{if $config.timeoffset==-3.5} selected{/if}>(GMT -03:30) Newfoundland</option>
						<option value="-3"{if $config.timeoffset==-3} selected{/if}>(GMT -03:00) Brassila, Buenos Aires, Georgetown, Falkland Is</option>
						<option value="-2"{if $config.timeoffset==-2} selected{/if}>(GMT -02:00) Mid-Atlantic, Ascension Is., St. Helena</option>
						<option value="-1"{if $config.timeoffset==-1} selected{/if}>(GMT -01:00) Azores, Cape Verde Islands</option>
						<option value="0"{if $config.timeoffset==0} selected{/if}>(GMT) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia</option>
						<option value="1"{if $config.timeoffset==1} selected{/if}>(GMT +01:00) Amsterdam, Berlin, Brussels, Madrid, Paris, Rome</option>
						<option value="2"{if $config.timeoffset==2} selected{/if}>(GMT +02:00) Cairo, Helsinki, Kaliningrad, South Africa</option>
						<option value="3"{if $config.timeoffset==3} selected{/if}>(GMT +03:00) Baghdad, Riyadh, Moscow, Nairobi</option>
						<option value="3.5"{if $config.timeoffset==3.5} selected{/if}>(GMT +03:30) Tehran</option>
						<option value="4"{if $config.timeoffset==4} selected{/if}>(GMT +04:00) Abu Dhabi, Baku, Muscat, Tbilisi</option>
						<option value="4.5"{if $config.timeoffset==4.5} selected{/if}>(GMT +04:30) Kabul</option>
						<option value="5"{if $config.timeoffset==5} selected{/if}>(GMT +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
						<option value="5.5"{if $config.timeoffset==5.5} selected{/if}>(GMT +05:30) Bombay, Calcutta, Madras, New Delhi</option>
						<option value="5.75"{if $config.timeoffset==5.75} selected{/if}>(GMT +05:45) Katmandu</option>
						<option value="6"{if $config.timeoffset==6} selected{/if}>(GMT +06:00) Almaty, Colombo, Dhaka, Novosibirsk</option>
						<option value="6.5"{if $config.timeoffset==6.5} selected{/if}>(GMT +06:30) Rangoon</option>
						<option value="7"{if $config.timeoffset==7} selected{/if}>(GMT +07:00) Bangkok, Hanoi, Jakarta</option>
						<option value="8"{if $config.timeoffset==8} selected{/if}>(GMT +08:00) 中国(北京/香港/上海), Perth, Singapore, Taipei</option>
						<option value="9"{if $config.timeoffset==9} selected{/if}>(GMT +09:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk</option>
						<option value="9.5"{if $config.timeoffset==9.5} selected{/if}>(GMT +09:30) Adelaide, Darwin</option>
						<option value="10"{if $config.timeoffset==10} selected{/if}>(GMT +10:00) Canberra, Guam, Melbourne, Sydney, Vladivostok</option>
						<option value="11"{if $config.timeoffset==11} selected{/if}>(GMT +11:00) Magadan, New Caledonia, Solomon Islands</option>
						<option value="12"{if $config.timeoffset==12} selected{/if}>(GMT +12:00) Auckland, Wellington, Fiji, Marshall Island</option>
						<option value="13"{if $config.timeoffset==13} selected{/if}>(GMT+13:00）Tongatapu/Enderbury </option>
						<option value="14"{if $config.timeoffset==14} selected{/if}>(GMT+14:00）Kiritimati</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>显示调试信息：</th>
				<td>
					<input name="config[isshowdebug]" type="radio" value="1"{if $config.isshowdebug} checked{/if}/>是&nbsp;
					<input name="config[isshowdebug]" type="radio" value="0"{if !$config.isshowdebug} checked{/if}/>否
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th class="hide">&nbsp;</th>
				<td><input name="configsubmit" type="submit" value="提交"/><input name="confighash" type="hidden" value="$confighash"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$('#configform').validate();
</script>