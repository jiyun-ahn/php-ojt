<?php
require_once 'dao/PDOFactory.php';

class LabelDao {
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
	 * 라벨 이름 목록 조회
	 * @return array
	 */
	public function selectLabelList() {
		$result = array();
		$query = "
				SELECT
					seq,
					name
				FROM
					tLabel
				ORDER BY
					seq ASC";
		
		$stmt = $this->ojtConn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
	
	/**
	 * 해당하는 라벨 이름 조회
	 * @param int $labelSeq
	 * @return string
	 */
	public function selectLabelName($labelSeq) {
		$query = "
				SELECT
					name AS labelName
				FROM
					tLabel
				WHERE
					seq = :seq";
		
		$stmt = $this->ojtConn->prepare($query);
		$stmt->bindParam(':seq', $labelSeq, PDO::PARAM_INT);
		
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result) {
			return $result['labelName'];
		} else {
			return null;
		}
		
	}
}