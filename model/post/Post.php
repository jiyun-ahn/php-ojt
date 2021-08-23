<?php

/**
 * 게시물의 정보 클래스
 */
class Post {

	private $seq;           // 게시물 번호
    private $title;         // 제목
    private $content;       // 내용
    private $createDate;    // 등록일
    private $authorSeq;     // 작성자 고유번호
    private $authorId;      // 작성자 아이디
    private $authorName;    // 작성자 이름
    private $viewCount;		// 조회수
    private $likeCount;		// 좋아요 수
    private $importantYN;	// 중요여부
    private $labelSeq;		// 라벨번호
    private $labelName;     // 라벨명
    private $attachFiles;   // 첨부파일 리스트

	/**
     * 게시물 번호
     */
    public function getSeq() {
        return $this->seq;
    }

    public function setSeq($seq) {
        $this->seq = $seq;
    }

    /**
     * 제목
     */
    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * 내용
     */
    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * 등록일
     */
    public function getCreateDate() {
        return $this->createDate;
    }

    public function setCreateDate($createDate) {
        $this->createDate = $createDate;
    }
    
    /**
     * 라벨 번호
     */
    public function getLabelSeq() {
    	return $this->labelSeq;
    }
    
    public function setLabelSeq($labelSeq) {
    	$this->labelSeq = $labelSeq;
    }

    /**
     * 라벨명
     */
    public function getLabelName() {
    	return $this->LabelName;
    }

    public function setLabelName($LabelName) {
    	$this->LabelName = $LabelName;
    }

    /**
     * 첨부파일
     */
    public function getAttachFiles() {
        return $this->attachFiles;
    }

    public function setAttachFiles($attachFiles) {
        $this->attachFiles = $attachFiles;
    }
    
    /**
	 * 작성자 고유번호
     */
	public function getAuthorSeq() {
		return $this->authorSeq;
	}

	public function setAuthorSeq($authorSeq) {
		$this->authorSeq = $authorSeq;
	}

	/**
	 * 작성자 ID
	 */
	public function getAuthorId() {
		return $this->authorId;
	}
	
	public function setAuthorId($authorId) {
		$this->authorId = $authorId;
	}
	
	/**
	 * 작성자 이름
	 */
	public function getAuthorName() {
		return $this->authorName;
	}

	public function setAuthorName($authorName) {
		$this->authorName = $authorName;
	}

	/**
	 * 조회수
	 */
	public function getViewCount() {
		return $this->viewCount;
	}

	public function setViewCount($viewCount) {
		$this->viewCount = $viewCount;
	}

	/**
	 * 좋아요 수
	 */
	public function getLikeCount() {
		return $this->likeCount;
	}

	public function setLikeCount($likeCount) {
		$this->likeCount = $likeCount;
	}

	/**
	 * 중요여부
	 */
	public function getImportantYN() {
		return $this->importantYN;
	}

	public function setImportantYN($importantYN) {
		$this->importantYN = $importantYN;
	}

}