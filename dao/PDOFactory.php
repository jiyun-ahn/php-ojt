<?php

/**
 * PDO 클래스를 이용하여 DB 연결 객체를 관리하는 클래스
 */
class PDOFactory extends PDO{

	// ojtdb
	const OJTDB = "ojtdb";
	const OJTDB_DSN = "mysql:dbname=ojt;host=localhost;charset=utf8";
	const OJTDB_USERNAME = 'danawa';
	const OJTDB_PASSWD = 'ekskdhk';
	const OJTDB_OPTIONS = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION		// 에러 발생 시 Exception throw 시킨다.
	);
	
	/**
	 * PDO 클래스 생성 및 반환
	 * @param string $dbname
	 * @return PDO
	 */
	static public function getConnection($dbname) {
		switch ($dbname) {
			case self::OJTDB :
				return new PDO(self::OJTDB_DSN, self::OJTDB_USERNAME, self::OJTDB_PASSWD, self::OJTDB_OPTIONS);
			default :
				return new PDO(self::OJTDB_DSN, self::OJTDB_USERNAME, self::OJTDB_PASSWD, self::OJTDB_OPTIONS);
		}
	}

}