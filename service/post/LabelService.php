<?php

require_once 'dao/post/LabelDao.php';

class LabelService {
	private $labelDao;
	
	public function __construct() {
		$this->labelDao = new LabelDao();
	}
	
	/**
	 * 라벨 이름 목록 조회
	 * @return array
	 */
	public function selectLabelList() {
		return $this->labelDao->selectLabelList();
	}
}