<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'service/post/PostService.php';

// 변경을 위해 필요한 파라미터
$postSeqs = $_GET["postSeqs"];

$type = $_GET["type"];
$value = $_GET["value"];

// 게시물 삭제
$postService = new PostService();
$result = $postService->updatePosts($postSeqs, $type, $value);

if (!$result) {
	echo "<script>alert('변경 도중 오류가 발생하였습니다.');</script>";	
} else {
	echo "<script>alert('변경 완료하였습니다.');</script>";
}

// 게시물 목록으로 이동하기 위한 파라미터
echo "<input id=\"labelSeq\" type=\"hidden\" value=\"{$_GET["labelSeq"]}\">";
echo "<input id=\"page\" type=\"hidden\" value=\"{$_GET["page"]}\">";
echo "<input id=\"limit\" type=\"hidden\" value=\"{$_GET["limit"]}\">";
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
	//목록 페이지로 돌아간다.
	let url = "/view/html/post/postList.php";
	let param = "?labelSeq=" + $('input#labelSeq').val() +
				"&page=" + $('input#page').val() +
				"&limit=" + $('input#limit').val();
	location.href=url+param;
</script>