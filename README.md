说明
---
<br>为每一位ss-panel-v3用户提供一个专属订阅地址

简介
---
<br>订阅地址的初衷可能是为多人提供快捷的添加方式，同时能够保持有效、快捷的更新。但对于ss panel v3用户来说，只需获取自己的配置即可，而当每次新加服务器或删除服务器时，或当用户在用户中心修改了自己的连接密码、协议、加密、混淆后，为能够使用变更过的配置，仍需手动通过导入配置文件，或通过剪切板添加、扫描二维码添加、手动更新，并不算方便。本项目通过php实现针对ss panel v3个人用户订阅地址的更新，支持Windows，mac osx，ios，Android四大平台。<br>

使用方法
---
1.填写 ss panel v3 登录地址

    $ss_panel_login_address = "";

2.填写 ss panel v3 数据库信息

    $db_host = 'localhost';$db_user = 'root';$db_pw = 'root';$db_name = 'sspanel';
3.填写 Group 名称

    $group_name = '';//Group名称
   
  4.为所有服务器命名，并填写所有服务器的ID
  推荐命名规则：server_服务器所在国家地区简拼_该地区服务器编号
  例：server_jp_1，名称不能重复，服务器id不能填错，多台服务器添加多行
  

    $server_jp = '3';
    $server_hk = '4';
    $server_sg = '5';
5.配置每台服务器的生成链接
注:只需修改下方的变量名：`$server_jp_url`为你在第4步为服务器命的名，然后在最后加上`_url`，以便区分，多台服务器添加多行

    $server_jp_url = get_ssr_url("$server_jp","$username","$db_host","$db_user","$db_pw","$db_name","$group_name","$group_name_base64","$after_obfs","$after_server_name","$after_group","$after_ssr_url");

6.最后一步，在下方添加你在第5步中修改后的变量名，第5步的例子中是`$server_jp_url`，因此下方用`$server_jp_url`演示
当服务器只有一台时

    $array = array("$server_jp_url");

当有多台服务器时，应按照如下配置

    $array = array("$server_jp_url","\r\n","$server_hk_url","\r\n","$server_sg_url");

注：`"\r\n"`是为ssr://链接换行,只需在倒数第二个服务器变量名后添加，如果这一步配置错误，会导致无法生成链接表现为空白页，或500、503错误
7.配置完成，访问api.php，假如 ss panel v3 前端地址为`ssr.domain.com`，我们访问`ssr.domain.com/api.php?user=用户名&passwd=密码`即可
当以上配置正确时，您可看到一串长链接，当账号密码错误时，您可看到错误提示：登录失败，用户名或密码不正确。

Windows平台订阅
---
右键小飞机，服务器订阅，SSR服务器订阅设置，在网址栏输入订阅地址（参加第7步），确定
右键小飞机，服务器订阅，更新SSR服务器订阅
注：当“更新SSR服务器订阅”提示失败时，可尝试“更新SSR服务器订阅（不通过代理）”

mac osx
---
等待添加...

ios平台订阅
---
打开Shadowsocket，点击右上方的+号，类型选择Subscribe，url输入订阅地址，备注自定义即可，点完成，Shadowsocket会自动获取配置
可在“设置-其他-服务器订阅”中启用更多选项

Android平台订阅
---
点击ShadowSocksR，点击右下角的+，添加/升级 SSR订阅，自动更新开关随意，点添加订阅地址，输入订阅地址，确认，确定并升级

感谢
---
感谢 [@ysc3839](https://github.com/ysc3839) 在 [windows下ssr4.6.1 蜜汁订阅问题](https://github.com/shadowsocksr/shadowsocksr-csharp/issues/279#issuecomment-317194631) 指出Windows下订阅失败的问题所在（需要 urlsafe base64）
