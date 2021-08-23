<?php

/**
 * 사용자 정보
 */
class User {
	private $seq;		// 사용자 고유번호
	private $id;		// 사용자 아이디
	private $password;	// 사용자 비밀번호
	private $name;		// 사용자 이름
	private $level;		// 사용자 권한 등급
	
	/**
	 * 사용자 고유번호
	 */
	public function getSeq() {
		return $this->seq;
	}

	public function setSeq($seq) {
		$this->seq = $seq;
	}

	/**
	 * 사용자 아이디
	 */
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * 사용자 비밀번호
	 */
	public function getPassword() {
		return $this->password;
	}
	
	public function setPassword($password) {
		$this->password = $password;
	}
	
	/**
	 * 사용자 이름
	 */
	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * 사용자 권한 등급
	 */
	public function getLevel() {
		return $this->level;
	}

	public function setLevel($level) {
		$this->level = $level;
	}
	
	
}