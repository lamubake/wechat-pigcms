<?php
/**
 * UCenter Ӧ�ó��򿪷� Example
 *
 * Ӧ�ó��������ݿ⣬�û�ע��� Example ����
 * ʹ�õ��Ľӿں�����
 * uc_user_register()	���룬ע���û�����
 * uc_authcode()	��ѡ�������û����ĵĺ����ӽ��� Cookie
 */

if(empty($_POST['submit'])) {
	//ע���
	echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?example=register">';
	echo 'ע��:';
	echo '<dl><dt>�û���</dt><dd><input name="username"></dd>';
	echo '<dt>����</dt><dd><input name="password"></dd>';
	echo '<dt>Email</dt><dd><input name="email"></dd></dl>';
	echo '<input name="submit" type="submit">';
	echo '</form>';
} else {
	//��UCenterע���û���Ϣ
	$uid = uc_user_register($_POST['username'], $_POST['password'], $_POST['email']);
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
	} else {
		//ע��ɹ������� Cookie������ֱ���� uc_authcode �������û�ʹ���Լ��ĺ���
		setcookie('Example_auth', uc_authcode($uid."\t".$_POST['username'], 'ENCODE'));
		echo 'ע��ɹ�<br><a href="'.$_SERVER['PHP_SELF'].'">����</a>';
	}
}

?>