<?php

/**
 * 게시물의 라벨 클래스
 */
class Label {
    private $seq;       // 라벨 번호
    private $name;      // 라벨명

    public function __construct() {
    	$this->seq = 0;
    	$this->name = '';
    }
    
    /**
     * 라벨 번호
     */
	public function getSeq() {
		return $this->seq;
	}

	public function setSeq($seq) {
		$this->seq = $seq;
	}

	/**
	 * 라벨명
	 */
	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}
    
}
