<?php
//ss panel v3 登录地址
$ss_panel_login_address = "";
//数据库
$db_host = 'localhost';//地址
$db_user = 'root';//用户名
$db_pw = 'root';//密码
$db_name = 'sspanel';//数据库表
//Group
$group_name = '';//Group名称
$group_name_base64 = (base64_encode($group_name));//base64加密后的Group名称

//验证账号密码正误
$username = htmlspecialchars($_GET['user']);//接收用户名
$password = htmlspecialchars($_GET['passwd']);//接收的密码
$two_step_verification_code = htmlspecialchars($_GET['code']);//接收两步验证码
$post_data = array ("email" => "$username","passwd" => "$password","code" => "$two_step_verification_code");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ss_panel_login_address);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$output = curl_exec($ch);
curl_close($ch);
$json = "$output";
$login_results_json = json_decode($json);
$login_code = $login_results_json->{'ret'};
$login_msg = $login_results_json->{'msg'};

switch($login_code){
case '1':
//账号密码正确时
$con = mysqli_connect($db_host, $db_user, $db_pw, $db_name) or die("Unable to connect to the database.".mysqli_error());
//获取连接配置信息
$sql = 'SELECT * FROM `user` WHERE `email` = \'' . $username . '\'';
$user_config = mysqli_fetch_array(mysqli_query($con, $sql));

$user_passwd = $user_config['passwd'];//密码
$user_passwd_base64 = (base64_encode($user_passwd));//base64加密后的ssr连接密码
$user_port = $user_config['port'];//端口
$user_protocol = $user_config['protocol'];//协议
$user_method = $user_config['method'];//加密
$user_obfs = $user_config['obfs'];//混淆
$user_obfs_parameter = $user_config['obfs_param'];//混淆参数
$user_obfs_parameter_base64 = (base64_encode($user_obfs_parameter));//base64加密后的混淆参数

//生成连接前缀参数，请勿修改
$after_obfs = '/?obfsparam=';
$after_server_name = '&remarks=';
$after_group = '&group=';
$after_ssr_url = 'ssr://';

/*如何添加一台提供订阅服务的服务器？
1.为该服务器命名,然后在下方服务器列表添加,并填写服务器ID,服务器ID可在 "管理面板-节点列表" 中找到
推荐服务器命名规则:server_地区简拼,例:
$server_hk = '3';
2.将下面的"Your_server_name"替换为你为服务器的命名
//获取节点信息并生成链接
$sql = 'SELECT * FROM `ss_node` WHERE `id` = \'' . $server_jp . '\'';
$server_Your_server_name_config = mysqli_fetch_array(mysqli_query($con, $sql));
$server_Your_server_name_address = $server_Your_server_name_config['server'];//地址
$server_Your_server_name_name = $server_Your_server_name_config['name'];//名称
$server_Your_server_name_name_base64 = (base64_encode($server_Your_server_name_name));//base64后的名称
$array = array("$server_Your_server_name_address",":","$user_port",":","$user_protocol",":","$user_method",":","$user_obfs",":","$user_passwd_base64","$after_obfs","$user_obfs_parameter_base64","$after_server_name","$server_Your_server_name_name_base64","$after_group","$group_name_base64");
$server_Your_server_name_url_1 = implode($array);//此处得到的是连接配置明文
$server_Your_server_name_url_2 = (base64_encode($server_Your_server_name_url_1));//此处得到的是被base64加密的连接配置明文
$array = array("$after_ssr_url","$server_Your_server_name_url_2");
$server_Your_server_name_url_3 = implode($array);//此处得到完整的ssr://链接
3.将替换后的内容粘贴到下方的"Here"
4.参考"生成最终配置"后的注释,添加需要生成的服务器,注意修改"Your_server_name"
5.大功告成!
注：如需添加多台服务器,重复上述步骤即可,注意都需修改"Your_server_name"为你为服务器的命名
不能重复,建议按照一定规律命名,以防混淆
*/

//服务器列表
//$server_hk = '3';

//Here :)


//生成最终配置
$array = array("$server_jp_url_3","\r\n","$server_hk_url_3","\r\n","$server_sg_url_3");
/*仅一台服务器配置应如下:
$array = array("$server_Your_server_name_url_3");
多台服务器配置应如下：
$array = array("$server_Your_server_name_url_3","\r\n","$server_Your_server_name_url_3","\r\n","$server_Your_server_name_url_3");
注:【"\r\n"】是为ssr://链接换行,只需在倒数第二个服务器后添加
*/
$server_all_url_1 = implode($array);//此处得到的是所有完整的ssr://链接
$server_all_url_2 = (base64_encode($server_all_url_1));//此处得到的是被base64加密后的所有完整的ssr://链接
echo "$server_all_url_2";
break;
//账号密码错误时
case '0':
echo "登录失败，用户名或密码不正确。";
break;
}

?>
