<?php

/**
 * 게시물의 첨부파일 클래스
 */
class AttachFile {
	private $seq;			// 파일번호
	private $name;			// 파일명
	private $extension;		// 확장자
	private $registDate;	// 등록일
	private $postSeq;		// 게시물번호

	/**
	 * 파일 번호
	 */
	public function getSeq() {
		return $this->seq;
	}

	public function setSeq($seq) {
		$this->seq = $seq;
	}

	/**
	 * 파일명
	 */
	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * 확장자
	 */
	public function getExtension() {
		return $this->extension;
	}

	public function setExtension($extension) {
		$this->extension = $extension;
	}

	/**
	 * 등록일
	 */
	public function getRegistDate() {
		return $this->registDate;
	}

	public function setRegistDate($registDate) {
		$this->registDate = $registDate;
	}
	
	/**
	 * 게시물 번호
	 */
	public function getPostSeq() {
		return $this->postSeq;
	}
	
	public function setPostSeq($postSeq) {
		$this->postSeq = $postSeq;
	}
	
}