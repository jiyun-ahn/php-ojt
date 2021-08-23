<?php
require_once 'dao/PDOFactory.php';

class UserDao {
	private $ojtConn;
	
	public function __construct() {
		try {
			$this->ojtConn = PDOFactory::getConnection(PDOFactory::OJTDB);
			
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
	}
	
	/**
	 * 트랜잭션 시작
	 */
	public function beginTransaction() {
		$this->ojtConn->beginTransaction();
	}
	
	/**
	 * 트랜잭션 종료
	 * @param bool $result
	 */
	public function finishTransaction($result) {
		if ($result) {
			$this->ojtConn->commit();
		} else {
			$this->ojtConn->rollback();
		}
	}
	
	/**
	 * 사용자 정보 조회하는 함수
	 * @param int $id
	 * @return array
	 */
	public function selectUserInfo($id) {
		$result = array();
		
		$query = "
			SELECT
				seq,
				id,
				name,
				authLevel
			FROM
				tAccount
			WHERE
				id = :id";
		
		try {
			$stmt = $this->ojtConn->prepare($query);
			$stmt->bindParam(':id', $id, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
		return $result;
	}
}