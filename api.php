<?php
//URL安全的Base64编码
function urlsafe_b64encode($string) {
   $data = base64_encode($string);
   $data = str_replace(array('+','/','='),array('-','_',''),$data);
   return $data;
}

//登录参数
$ss_panel_login_address = "";
$username = htmlspecialchars($_GET['user']);//用户名
$password = htmlspecialchars($_GET['passwd']);//密码
$two_step_verification_code = htmlspecialchars($_GET['code']);//两步验证码
//数据库参数
$db_host = 'localhost';$db_user = 'root';$db_pw = 'root';$db_name = 'sspanel';
//Group名称
$group_name = '';//Group名称
$group_name_base64 = urlsafe_b64encode($group_name);//安全base64加密后的Group名称
//生成连接前缀参数，请勿修改
$after_obfs = '/?obfsparam=';
$after_server_name = '&remarks=';
$after_group = '&group=';
$after_ssr_url = 'ssr://';

//服务器ID设置,服务器ID可在 "管理面板-节点列表" 中找到
//$server_jp = '3';

//验证账号密码正误
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
//当账号密码正确时,$login_code=1
case '1':
//执行生成函数
function get_ssr_url($server_id,$username,$db_host,$db_user,$db_pw,$db_name,$group_name,$group_name_base64,$after_obfs,$after_server_name,$after_group,$after_ssr_url) {
//获取连接配置
$con = mysqli_connect($db_host, $db_user, $db_pw, $db_name) or die("Connection error.".mysqli_error());
$sql = 'SELECT * FROM `user` WHERE `email` = \'' . $username . '\'';
$user_config = mysqli_fetch_array(mysqli_query($con, $sql));
$user_passwd = $user_config['passwd'];//密码
$user_passwd_base64 = urlsafe_b64encode($user_passwd);//安全base64加密后的ssr连接密码
$user_port = $user_config['port'];//端口
$user_protocol = $user_config['protocol'];//协议
$user_method = $user_config['method'];//加密
$user_obfs = $user_config['obfs'];//混淆
$user_obfs_parameter = $user_config['obfs_param'];//混淆参数
$user_obfs_parameter_safe_base64 = urlsafe_b64encode($user_obfs_parameter);//安全base64加密后的混淆参数

//获取服务器配置 
$sql = 'SELECT * FROM `ss_node` WHERE `id` = \'' . $server_id . '\'';
$server_config = mysqli_fetch_array(mysqli_query($con, $sql));
$server_address = $server_config['server'];//地址
$server_name = $server_config['name'];//名称
$server_name_safe_base64 = urlsafe_b64encode($server_name);//安全base64后的服务器名称

//生成
$array = array("$server_address",":","$user_port",":","$user_protocol",":","$user_method",":","$user_obfs",":","$user_passwd_base64","$after_obfs","$user_obfs_parameter_safe_base64","$after_server_name","$server_name_safe_base64","$after_group","$group_name_base64");
$server_url_1 = implode($array);//此处得到的是连接配置明文
$server_url_2 = (base64_encode($server_url_1));//此处得到的是被base64加密的连接配置明文
$array = array("$after_ssr_url","$server_url_2");//将ssr://与被base64加密的连接配置明文拼接
$server_url_3 = implode($array);//此处得到完整的ssr://链接
return $server_url_3;
}

//生成各服务器连接链接
$server_jp_url = get_ssr_url("$server_jp","$username","$db_host","$db_user","$db_pw","$db_name","$group_name","$group_name_base64","$after_obfs","$after_server_name","$after_group","$after_ssr_url");

//拼接各服务器连接链接,生成最终配置,输出
$array = array("$server_jp_url");
$server_all_url_1 = implode($array);//此处得到的是所有完整的ssr://链接
$server_all_url_2 = (base64_encode($server_all_url_1));//此处得到的是被base64加密后的所有完整的ssr://链接
echo "$server_all_url_2";
break;

//当账号密码错误时,$login_code=0
case '0':
echo "登录失败，用户名或密码不正确。";
break;
}

?>
