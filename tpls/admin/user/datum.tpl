<form class="formtable">
	<table cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<th>用户名：</th>
				<td>{$user.username|html}</td>
			</tr>
			<tr>
				<th>邮箱：</th>
				<td>{$user.email}</td>
			</tr>
			<tr>
				<th>注册时间：</th>
				<td>{date 'Y-m-d',$user.regtime}</td>
			</tr>
			<tr>
				<th>注册IP：</th>
				<td>{$user.regip}</td>
			</tr>
			<tr>
				<th>最后登录时间：</th>
				<td>{date 'Y-m-d',$user.lastlogintime}</td>
			</tr>
			<tr>
				<th>最后登录IP：</th>
				<td>{$user.lastloginip|html}</td>
			</tr>

			<tr>
				<th>公司名：</th>
				<td>{$datum.corpname|html}</td>
			</tr>
			<tr>
				<th>联系人：</th>
				<td>{$datum.linkman|html}</td>
			</tr>
			<tr>
				<th>通信地址：</th>
				<td>{$datum.address|html}</td>
			</tr>
			<tr>
				<th>QQ号：</th>
				<td>{$datum.qq}</td>
			</tr>
			<tr>
				<th>MSN：</th>
				<td>{$datum.msn}</td>
			</tr>
			<tr>
				<th>手机号：</th>
				<td>{$datum.mobile}</td>
			</tr>
			<tr>
				<th>固定电话：</th>
				<td>{$datum.phone}</td>
			</tr>
		</tbody>
	</table>
</form>