function:
	M	Model
	V	View=>LibTemplate
	C	Controller
	L	Library
	R	Router=>LibUrl
	CFG	Config
	DB	DbConfig
	GD	getdir
	GN	getname

	saddslashes
	sstripslashes
	iimplode
	simplode
	timediff
	smallesttimediff
	sgmdate
	strcut
	formatsize

目录:
	cache
	resource
	source
		ctrls
			admin
			front
		models
		libs
		plugins
		widgets
	tpls
		admin
		front
		mail

模板(model,sql,pages,diy,widget)：
	模块：{model:modelname|method arg1,arg2,...}{/model}
	查询：{sql argname1=argvalue1,argname2=argvalue2,...}{/sql}
	分页：{pages:template linkkey1=linkvalue1,linkkey2=linkvalue2,...}
	自定义(配置保存到数据库)：{diy id=diyid,widget=widgetname class=classvalue}(<div id="diyid" class="classvalue">{content}</div>)
	挂件(...)：后台设置{widget id=widgetid,name=widgetname title=titlevalue},调用{widget:widgetname arg1,arg2,arg3}

lib.db.mysql.pages()
	return model.base;

xml change json

db.backup,db.restore:大数据库支持

sncore.display:执行后不退出

urlrewrite:shtml

url:相对与绝对路径

js:压缩或需要时加载

data cache

framework

静态页生成

静态页缓存方式(shopex)
1.通过echoPage缓存
2.缓存页URL和实际URL不统一问题，像phpcms的URL规则一样

js+css多文件合并压缩及缓存

前后台菜单导航
Model代码优化
Widget架构
Plugin架构