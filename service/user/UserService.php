<?php

require_once 'dao/user/UserDao.php';

class UserService {
	private $userDao;
	
	public function __construct() {
		$this->userDao = new UserDao();
	}
	
	/**
	 * 사용자 정보 조회하는 함수
	 * @param string $id
	 * @return string
	 */
	public function selectUserSeq($id) {
		$user = $this->userDao->selectUserInfo($id);
		return $user['seq'];
	}
}