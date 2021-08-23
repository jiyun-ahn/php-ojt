<?php

require_once 'model/post/Post.php';
require_once 'model/post/AttachFile.php';
require_once 'model/post/Label.php';

require_once 'dao/post/PostDao.php';
require_once 'service/user/UserService.php';

require_once 'util/util.php';

class PostService {
	private $postDao;
	private $userService;
	
	public function __construct() {
		$this->postDao = new PostDao();
		$this->userService = new UserService();
	}
	
	/**
	 * 게시물 상세정보 조회
	 * @param int $postSeq
	 * @return array
	 */
	public function selectPostDetail($postSeq) {
		return $this->postDao->selectPostDetail($postSeq);
	}
	
	/**
	 * 게시물 목록 조회
	 * @param int $labelSeq
	 * @param int $page
	 * @param int $limit
	 * @return array
	 */
	public function selectPostList($labelSeq, $page, $limit) {
		return $this->postDao->selectPostList($labelSeq, $page, $limit);
	}
	
	/**
	 * 게시물 등록
	 * @param Post $Post
	 */
	public function insertPost($post) {
		$result = false;
		
		// 사용자 고유번호 조회
		$authorSeq = $this->userService->selectUserSeq($post->getAuthorId());
		$post->setAuthorSeq($authorSeq);
		$post->setSeq(uuidgen());	// UUID 생성
		
		$this->postDao->beginTransaction();
		
		// 게시물 등록
		$result = $this->postDao->insertPost($post);
		
		// 게시물 카운트 정보 등록
		$result = $this->postDao->insertPostCount($post->getSeq());
		
		if ($post->getAttachFiles()) {
			// 첨부파일 등록
			$result = $this->postDao->insertAttachFile($post->getSeq(), $post->getAttachFiles());
		}
		
		$this->postDao->finishTransaction($result);
		
		return $result;
	}
	
	/**
	 * 게시물 수정
	 * @param Post $post
	 */
	public function updatePost($post) {
		$result = false;
		
		$this->postDao->beginTransaction();
		
		// 게시물 수정
		$result = $this->postDao->updatePost($post);
		
// 		if ($attachFile) {
// 			// 첨부파일 수정
// 			$result = $this->postDao->updateAttachFile($post->getSeq(), $post->getAttachFiles());
// 		}
		
		$this->postDao->finishTransaction($result);
		
		return $result;
	}
	
	/**
	 * 게시물 일괄 수정
	 * @param string $postSeqs
	 * @param string $type
	 * @param unknown $value
	 */
	public function updatePosts($postSeqs, $type, $value) {
		$result = false;
		
		$postSeqs = explode("|", $postSeqs);
		$paramSeqs = "";
		foreach ($postSeqs as $seq) {
			$paramSeqs .= ",'" . $seq . "'";
		}
		
		$this->postDao->beginTransaction();
		
		// 게시물 일괄 수정
		$result = $this->postDao->updatePosts(substr($paramSeqs, 1), $type, $value);
		
		$this->postDao->finishTransaction($result);
		
		return $result;
	}
	
	/**
	 * 게시물 삭제
	 * @param string $postSeqs
	 */
	public function deletePost($postSeqs) {
		$postSeqs = explode("|", $postSeqs);
		$paramSeqs = "";
		foreach ($postSeqs as $seq) {
			$paramSeqs .= ",'" . $seq . "'"; 
		}

		$this->postDao->beginTransaction();

		// 게시물 첨부파일 삭제
		$result = $this->postDao->deleteAttachFile(substr($paramSeqs, 1));
		
		// 게시물 카운트 정보 삭제
		$result = $this->postDao->deletePostCount(substr($paramSeqs, 1));

		// 게시물 삭제
		$result = $this->postDao->deletePost(substr($paramSeqs, 1));
		
		// 위 작업 중 1개라도 실패 시 롤백
		$this->postDao->finishTransaction($result);
		
		return $result;
	}
	
	/**
	 * 조회수 증가
	 * @param int $postSeq
	 */
	public function incrPostCount($postSeq) {
		$result = false;
		$this->postDao->beginTransaction();
		
		// 조회수 증가
		$result = $this->postDao->updatePostCount($postSeq);
		
		$this->postDao->finishTransaction($result);
		
		return $result;
	}
	
	/**
	 * 게시물 중요 표시/해제 값 변경
	 * 중요표시된 게시물 -> 중요 표시 해제
	 * 중요표시되지 않은 게시물 -> 중요 표시됨
	 * @param int $postSeq
	 */
	public function toggleImportantPost($postSeq) {
		$result = false;
		$this->postDao->beginTransaction();
		
		// 게시물 기존 정보 조회
		$post = $this->postDao->selectPost($postSeq);
		if ($post) {
			$updateValue = array();
			
			// 중요표시값 변경
			if ('Y' == $post->getImportantYN) {
// 				$updateValue['importantYN'] = 'N';
			} else {
// 				$updateValue['importantYN'] = 'Y';
			}
			$updateValue['seq'] = $postSeq;
			$result = $this->postDao->updatePost($updateValue);
		} 
		$this->postDao->finishTransaction($result);
	}
	
	
}