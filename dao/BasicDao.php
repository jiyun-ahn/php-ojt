<?php

class BasicDao {
	protected $ojtConn;
	
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
	 * 커넥션 테스트
	 */
	public function testConnection() {
		$query = "SELECT 1 FROM DUAL";
		$stmt = $this->ojtConn->prepare($query);
		$result = $stmt->execute();
		
		if (!$result) {
			echo 'Success!';
		} else {
			echo 'Fail!';
		}
	}
}

$basicDao = new BasicDao();
$basicDao->testConnection();
