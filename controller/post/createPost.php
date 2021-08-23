<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'model/Factory.php';
require_once 'service/post/PostService.php';

// 변경을 위해 필요한 파라미터
$title = $_POST['title'];
$content = $_POST['content'];
$labelSeq = $_POST['labelSeq'];

$post = Factory::getInstance(Factory::POST);
$post->setTitle($title);
$post->setContent($content);
$post->setLabelSeq($labelSeq);
$post->setAuthorId('admin');

// 게시물 생성
$postService = new PostService();
$result = $postService->insertPost($post);

if (!$result) {
	echo "<script>alert('저장 도중 오류가 발생하였습니다.');</script>";	
} else {
	echo "<script>alert('저장 완료하였습니다.');</script>";
}

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
	//목록 페이지로 돌아간다.
	location.href= "/";
</script>