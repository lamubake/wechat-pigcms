<?php
/**
 * UCenter Ӧ�ó��򿪷� Example
 *
 * UCenter ����Ӧ�ó���Ӧ�ó������Լ����û���
 * ʹ�õ��Ľӿں�����
 * uc_authcode()	��ѡ�������û����ĵĺ����ӽ��� Cookie
 * uc_pm_checknew()	��ѡ������ȫ���ж��Ƿ����¶���Ϣ������ $newpm ����
 */

//include './config.inc.php';
//���ݿ�����IP,���ݿ��û���,���ݿ�����,���ݿ�����,���ݿ��ַ���Ĭ��gbk,���ݿ��ǰ׺ucenter.uc_
//ͨ����Կ��Ҫ��UCenter����һ�£�,UCenter��URL��ַ,UCenter���ַ���Ĭ��gbk

$get_conf_url = "http://" .$_SERVER['SERVER_NAME']. "/index.php?g=home&a=get_conf_uc_db_web"; 
list($conf_db_str, $conf_web_str) = explode('UC_UC', file_get_contents($get_conf_url));//xxx UC_UC xxx

list($uc_db_host, $uc_db_user, $uc_db_pass, $uc_db_name, $uc_db_char, $uc_db_prix) = explode(',', $conf_db_str);
/*$uc_db_host = 'localhost';//localhost
$uc_db_user = 'root';//root
$uc_db_pass = '';
$uc_db_name = 'ucenter';
$uc_db_char = 'gbk';//gbk
$uc_db_prix = 'ucenter.uc_';//ucenter.uc_*/

list($uc_web_key, $uc_web_url, $uc_web_gbk) = explode(',', $conf_web_str);
/*$uc_web_key = '123456789';//123456789
$uc_web_url = 'http://localhost/ucenter/upload';//http://localhost/ucenter/upload
$uc_web_gbk = 'gbk';//gbk*/

define('UC_CONNECT', 'mysql');				// ���� UCenter �ķ�ʽ: mysql/NULL, Ĭ��Ϊ��ʱΪ fscoketopen()
							// mysql ��ֱ�����ӵ����ݿ�, Ϊ��Ч��, ������� mysql

//���ݿ���� (mysql ����ʱ, ����û������ UC_DBLINK ʱ, ��Ҫ�������±���)
define('UC_DBHOST', $uc_db_host);			// UCenter ���ݿ�����
define('UC_DBUSER', $uc_db_user);				// UCenter ���ݿ��û���
define('UC_DBPW', $uc_db_pass);					// UCenter ���ݿ�����
define('UC_DBNAME', $uc_db_name);				// UCenter ���ݿ�����
define('UC_DBCHARSET', $uc_db_char);				// UCenter ���ݿ��ַ���
define('UC_DBTABLEPRE', $uc_db_prix);			// UCenter ���ݿ��ǰ׺

//ͨ�����
define('UC_KEY', $uc_web_key);				// �� UCenter ��ͨ����Կ, Ҫ�� UCenter ����һ��
define('UC_API', $uc_web_url);	// UCenter �� URL ��ַ, �ڵ���ͷ��ʱ�����˳���
define('UC_CHARSET', $uc_web_gbk);				// UCenter ���ַ���
define('UC_IP', '');					// UCenter �� IP, �� UC_CONNECT Ϊ�� mysql ��ʽʱ, ���ҵ�ǰӦ�÷�������������������ʱ, �����ô�ֵ
define('UC_APPID', 1);					// ��ǰӦ�õ� ID

//ucexample_2.php �õ���Ӧ�ó������ݿ����Ӳ���
$dbhost = 'localhost';			// ���ݿ������
$dbuser = 'root';			// ���ݿ��û���
$dbpw = '';				// ���ݿ�����
$dbname = 'ucenter';			// ���ݿ���
$pconnect = 0;				// ���ݿ�־����� 0=�ر�, 1=��
$tablepre = $uc_db_prix;//'example_';   		// ����ǰ׺, ͬһ���ݿⰲװ�����̳���޸Ĵ˴�
$dbcharset = 'gbk';			// MySQL �ַ���, ��ѡ 'gbk', 'big5', 'utf8', 'latin1', ����Ϊ������̳�ַ����趨

//ͬ����¼ Cookie ����
$cookiedomain = ''; 			// cookie ������
$cookiepath = '/';			// cookie ����·��


/**
 * �������ݿ�

 �û�������
 CREATE TABLE `example_members` (
   `uid` int(11) NOT NULL COMMENT 'UID',
   `username` char(15) default NULL COMMENT '�û���',
   `admin` tinyint(1) default NULL COMMENT '�Ƿ�Ϊ����Ա',
   PRIMARY KEY  (`uid`)
 ) TYPE=MyISAM;

 */

include './include/db_mysql.class.php';
$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

include './uc_client/client.php';

/**
 * ��ȡ��ǰ�û��� UID �� �û���
 * Cookie ����ֱ���� uc_authcode �������û�ʹ���Լ��ĺ���
 */
if(!empty($_COOKIE['Example_auth'])) {
	list($Example_uid, $Example_username) = explode("\t", uc_authcode($_COOKIE['Example_auth'], 'DECODE'));
} else {
	$Example_uid = $Example_username = '';
}

/**
 * ��ȡ���¶���Ϣ
 */
$newpm = uc_pm_checknew($Example_uid);

//����΢��ƽ̨�ĵ���
if ($_GET['from_weixin_url']) {
	$username = $_GET['username'];
	$password = $_GET['password'];
	$email    = $_GET['email'];
	
	$uid = uc_user_register($username, $password, $email);
	if($uid <= 0) {
			if($uid == -1) {
				echo '�û������Ϸ�';
			} elseif($uid == -2) {
				echo '����Ҫ����ע��Ĵ���';
			} elseif($uid == -3) {
				echo '�û����Ѿ�����';
			} elseif($uid == -4) {
				echo 'Email ��ʽ����';
			} elseif($uid == -5) {
				echo 'Email ������ע��';
			} elseif($uid == -6) {
				echo '�� Email �Ѿ���ע��';
			} else {
				echo 'δ����';
			}
			exit;
	}
	$db->query("INSERT INTO {$tablepre}members (uid,username,admin) VALUES ('$uid','$username','0')");
	//ע��ɹ������� Cookie������ֱ���� uc_authcode �������û�ʹ���Լ��ĺ���
	setcookie('Example_auth', uc_authcode($uid."\t".$username, 'ENCODE'));	
	echo 1;exit;
}


/**
 * �������ܵ� Example ����
 */
switch(@$_GET['example']) {
	case 'login':
		//UCenter �û���¼�� Example ����
		include 'code/login_db.php';
	break;
	case 'logout':
		//UCenter �û��˳��� Example ����
		include 'code/logout.php';
	break;
	case 'register':
		//UCenter �û�ע��� Example ����
		include 'code/register_db.php';
	break;
	case 'pmlist':
		//UCenter δ������Ϣ�б�� Example ����
		include 'code/pmlist.php';
	break;
	case 'pmwin':
		//UCenter ����Ϣ���ĵ� Example ����
		include 'code/pmwin.php';
	break;
	case 'friend':
		//UCenter ���ѵ� Example ����
		include 'code/friend.php';
	break;
	case 'avatar':
		//UCenter ����ͷ��� Example ����
		include 'code/avatar.php';
	break;
}

echo '<hr />';
if(!$Example_username) {
	//�û�δ��¼
	echo '<a href="'.$_SERVER['PHP_SELF'].'?example=login">��¼</a> ';
	echo '<a href="'.$_SERVER['PHP_SELF'].'?example=register">ע��</a> ';
} else {
	//�û��ѵ�¼
	echo '<script src="ucexample.js"></script><div id="append_parent"></div>';
	echo $Example_username.' <a href="'.$_SERVER['PHP_SELF'].'?example=logout">�˳�</a> ';
	echo ' <a href="'.$_SERVER['PHP_SELF'].'?example=pmlist">����Ϣ�б�</a> ';
	echo $newpm ? '<font color="red">New!('.$newpm.')</font> ' : NULL;
	echo '<a href="###" onclick="pmwin(\'open\')">�������Ϣ����</a> ';
	echo ' <a href="'.$_SERVER['PHP_SELF'].'?example=friend">����</a> ';
	echo ' <a href="'.$_SERVER['PHP_SELF'].'?example=avatar">ͷ��</a> ';
}

?>