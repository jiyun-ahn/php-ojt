<?php
require_once 'model/post/Post.php';
require_once 'dao/PDOFactory.php';

class PostDao{
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
	 * 게시물 상세정보 조회하는 함수
	 * @param int $postSeq
	 * @return array
	 */
	public function selectPostDetail($postSeq) {
		$result = array();
		
		$query = "
			SELECT
				p.seq AS seq,
			    p.title AS title,
			    p.content AS content,
			    p.createDate AS createDate,
			    p.importantYN AS importantYN,
			    a.seq AS authorSeq,
			    a.name AS authorName,
				l.seq AS labelSeq,
			    l.name AS labelName,
			    c.viewCount AS viewCount,
			    c.likeCount AS likeCount
			FROM
				tPost p
			    INNER JOIN tAccount a ON (p.authorSeq = a.seq)
			    INNER JOIN tLabel l ON (p.labelSeq = l.seq)
			    INNER JOIN tPostCount c ON (p.seq = c.postSeq)
			WHERE
				p.seq = :postSeq";
		
		try {
			$stmt = $this->ojtConn->prepare($query);
			$stmt->bindParam(':postSeq', $postSeq, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
		return $result;
	}
	
	/**
	 * 게시물 목록 조회하는 함수
	 * @param int $label
	 * @param int $page
	 * @param int $limit
	 * @return mixed
	 */
	public function selectPostList($labelSeq, $page, $limit) {
		$result = array();
		
		$where = (0 < $labelSeq ? 'WHERE p.labelSeq = ' . $labelSeq : "");

		$query = "
			SELECT
				p.seq AS seq,
			    p.title AS title,
			    p.createDate AS createDate,
				p.importantYN AS importantYN,
			    a.name AS authorName,
			    c.viewCount AS viewCount,
				l.name AS labelName
			FROM
				tPost p
			    INNER JOIN tAccount a ON (p.authorSeq = a.seq)
			    INNER JOIN tPostCount c ON (p.seq = c.postSeq)
				INNER JOIN tLabel l ON (p.labelSeq = l.seq)
			" . $where . "
			ORDER BY
				p.importantYN desc, p.createDate desc
			LIMIT :page, :limit";
		
		try {
			$stmt = $this->ojtConn->prepare($query);
			$stmt->bindParam(':page', $page, PDO::PARAM_INT);
			$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
			
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		
		return $result;
	}
	
	
	/**
	 * 게시물 삭제
	 * @param int $postSeqs
	 * @return number
	 */
	public function deletePost($postSeqs) {
		$query = "
				DELETE
				FROM
					tPost
				WHERE
					seq IN ({$postSeqs})";
		try {
			$stmt = $this->ojtConn->prepare($query);
			$result = $stmt->execute();
			
		} catch(Exception $e) {
			echo $e->getMessage();
			$result = false;
		}
		return $result;
	}
	
	
	/**
	 * 게시물 카운트 정보 삭제
	 * @param int $postSeq
	 * @return number
	 */
	public function deletePostCount($postSeq) {
		$result = false;
		$query = "
				DELETE
				FROM
					tPostCount
				WHERE
					postSeq IN ($postSeq)";
		
		$result = $this->ojtConn->exec($query);
		if ($result == false) {
			echo $this->ojtConn->errorInfo();
		}
		return $result;
	}
	
	
	/**
	 * 게시물 첨부파일 정보 삭제
	 * @param int $postSeq
	 * @return number
	 */
	public function deleteAttachFile($postSeqs) {
		$result = false;
		$query = "
				DELETE FROM
					tAttachFile
				WHERE
					postSeq IN ({$postSeqs})";
		
		try {
			$stmt = $this->ojtConn->prepare($query);
			$stmt->execute();
			$result = true;
			
		} catch(Exception $e) {
			echo $e->getMessage();
		}
		return $result;
	}
	
	/**
	 * 게시물 등록
	 * @param Post $post
	 */
	public function insertPost($post) {
		$result = false;
		$query = "
			INSERT INTO tPost
				(`seq`, `title`, `content`, `createDate`, `importantYN`, `authorSeq`, `labelSeq`)
			VALUES (
				:seq,
				:title,
				:content,
				now(),
				'N',
				:authorSeq,
				:labelSeq )";
		
		$postSeq = $post->getSeq();
		$title = $post->getTitle();
		$content = $post->getContent();
		$authorSeq = $post->getAuthorSeq();
		$labelSeq = $post->getLabelSeq();
		
		try {
			$stmt = $this->ojtConn->prepare($query);
			$stmt->bindParam(':seq', $postSeq, PDO::PARAM_STR);
			$stmt->bindParam(':title', $title, PDO::PARAM_STR);
			$stmt->bindParam(':content', $content, PDO::PARAM_STR);
			$stmt->bindParam(':authorSeq', $authorSeq, PDO::PARAM_STR);
			$stmt->bindParam(':labelSeq', $labelSeq, PDO::PARAM_INT);
	
			$result = $stmt->execute();
		} catch(Exception $e) {
			echo $e->getMessage();
		}
		return $result;
	}
	
	/**
	 * 게시물 카운트 정보 등록 (최초)
	 * @param int $postSeq
	 */
	public function insertPostCount($postSeq) {
		$result = false;
		$query = "
			INSERT INTO tPostCount
				(`postSeq`, `viewCount`, `likeCount`)
			VALUES
				(:postSeq, 0, 0)";
		
		$stmt = $this->ojtConn->prepare($query);
		$stmt->bindParam(':postSeq', $postSeq, PDO::PARAM_STR);
		
		$result = $stmt->execute();
		if ($result == false) {
			echo $stmt->errorInfo();
		}
		
		return $result;
	}
	
	/**
	 * 게시물 첨부파일 등록
	 * @param int $postSeq
	 * @param array $attachFile
	 */
	public function insertAttachFile($postSeq, $attachFile) {
		$result = false;
		$query = "
			INSERT INTO tAttachFile
				(`seq`, `name`, `extension`, `registDate`, `postSeq`)
			VALUES (
				UPPER(REPLACE(UUID(),'-','')),
				:name,
				:extension,
				:registDate
				:postSeq);
			";
		
		$stmt = $this->ojtConn->prepare($query);
		$stmt->bindParam(':name', $attachFile->getName(), PDO::PARAM_STR);
		$stmt->bindParam(':extension', $attachFile->getExtension(), PDO::PARAM_STR);
		$stmt->bindParam(':registDate', $attachFile->getRegistDate(), PDO::PARAM_STR);
		$stmt->bindParam(':postSeq', $attachFile->getPostSeq(), PDO::PARAM_STR);
		
		$result = $stmt->execute();
		if (false == $result) {
			echo $stmt->errorInfo();
		}
		
		return $result;
	}
	
	/**
	 * 게시물 일괄 수정
	 * @param Post $post
	 */
	public function updatePosts($postSeqs, $type, $value) {
		$result = false;
		
		$setValue = "";
		switch ($type) {
			case 'important':
				$setValue = "importantYN = :updateValue";
				break;
			case 'label':
				$setValue = "labelSeq = :updateValue";
				break;
		}
		
		$query = "
			UPDATE
				tPost
			SET
				" . $setValue . "
			WHERE
				seq IN ({$postSeqs})";
		
		try {
			$stmt = $this->ojtConn->prepare($query);
			$stmt->bindParam(':updateValue', $value, PDO::PARAM_STR);
			$stmt->execute();
			$result = true;
			
		} catch(Exception $e) {
			echo $e->getMessage();
		}
		return $result;
	}
	
	
	/**
	 * 게시물 수정
	 * @param Post $post
	 */
	public function updatePost($post) {
		$result = false;
		
		$setValues = "";
		if ($post->getTitle()) {
			$setValues .= ", title = :title";
		}
		if ($post->getContent()) {
			$setValues .= ", content = :content";
		}
		if ($post->getLabelSeq()) {
			$setValues .= ", labelSeq = :labelSeq";
		}
		
		// 변경값이 없으면 false 리턴
		if (0 == strlen($setValues)) {
			echo "Not exists values to update.";
			return false;
		}
		
		$query = "
			UPDATE
				tPost
			SET
				". substr($setValues, 1) . "
			WHERE
				seq = :postSeq";
		
		$postSeq = $post->getSeq();
		$title = $post->getTitle();
		$content = $post->getContent();
		$labelSeq = $post->getLabelSeq();
		
		try {
			$stmt = $this->ojtConn->prepare($query);
			$stmt->bindParam(':title', $title, PDO::PARAM_STR);
			$stmt->bindParam(':content', $content, PDO::PARAM_STR);
			$stmt->bindParam(':labelSeq', $labelSeq, PDO::PARAM_INT);
			$stmt->bindParam(':postSeq', $postSeq, PDO::PARAM_STR);
		
			$result = $stmt->execute();
			$result = true;
		} catch(Exception $e) {
			echo $e->getMessage();
		}
		return $result;
	}
	
	/**
	 * 게시물 카운트 정보 변경 (증가)
	 * @param int $postSeq
	 */
	public function updatePostCount($postSeq) {
		$result = false;
		$query = "
			UPDATE
				tPostCount
			SET
				viewCount = viewCount + 1
			WHERE
				postSeq = :postSeq";
		
		try {
			$stmt = $this->ojtConn->prepare($query);
			$stmt->bindParam(':postSeq', $postSeq, PDO::PARAM_STR);
			
			$result = $stmt->execute();
			$result = true;
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	return $result;
	}
	
}