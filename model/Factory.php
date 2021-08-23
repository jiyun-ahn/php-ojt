<?php

require_once 'post/Post.php';
require_once 'post/Label.php';
require_once 'post/AttachFile.php';
require_once 'user/User.php';

/**
 * 객체를 생성하고 반환하는 클래스
 */
class Factory {
	
	// 클래스명
	const POST = 'post';
	const LABEL = 'label';
	const ATTACHFILE = 'attachFile';
	const USER = 'user';
	
	// 에러 메세지
	const ERROR_MSG_1 = 'Class name not found';
	
	/**
	 * 클래스명으로 객체 생성 및 반환
	 * @param string $className
	 * @return Post|Label|AttachFile|User
	 */
	static public function getInstance($className) {
		switch ($className) {
			case self::POST :
				return new Post();
				break;
			case self::LABEL :
				return new Label();
				break;
			case self::ATTACHFILE :
				return new AttachFile();
				break;
			case self::USER :
				return new User();
				break;
			default :
				echo self::ERROR_MSG_1;
		}
	}
	
}