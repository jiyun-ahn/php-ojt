<?php
require_once 'view/html/base/header.php';

require_once 'service/post/LabelService.php';

$labelService = new LabelService();
$labelList = $labelService->selectLabelList();

$mode = $_REQUEST['mode'];

if ('view' == $mode || 'modify' == $mode) {
	require_once 'service/post/PostService.php';
	
	// 게시물 상세정보 조회
	$postService = new PostService();
	$post = $postService->selectPostDetail($_REQUEST['postSeq']);
}

if ('view' == $mode) {
	// 조회수 증가
	$result = $postService->incrPostCount($_REQUEST['postSeq']);
	if (!$result) {
		echo "<script>alert(\"조회 오류 발생\");</script>";
	}
}
?>

<html>
	<head>
		<link href="/view/css/base/normal.css" rel="stylesheet" type="text/css" />
		<link href="/view/css/post_detail.css" rel="stylesheet" type="text/css" />
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script type="text/javascript" src="/view/js/postDetail.js"></script>
		
		<!-- summernote 는 부트스트랩과 jQuery를 기본으로 사용함 -->
		<link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> 
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> 
		
		<!-- include summernote css/js-->
		<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
		<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>
		
		<!-- summernote는 코드미러를 지원한다. -->
		<!-- CodeMirror란? 각종 프로그램 코드를 웹상에서 쾌적한 환경으로 편집할 수 있는 JavaScript library -->
	</head>
	
	<body>
		<hr class="post_detail_hr">
		<div class="post_detail_main">
			<?php
			if ('view' == $mode){
				$important_mark = "";
				if ("Y" == $post["importantYN"]) {
					$important_mark = "<span class=\"important_mark\">&nbsp;★</span>";
				}
				
				// hidden postSeq
				echo "<input id=\"postSeq\" name=\"postSeq\" type=\"hidden\" value=\"{$_REQUEST['postSeq']}\">";
			?>
				<div class="post_detail_view">
					<div class="post_detail_view_info">
						<!-- 라벨 -->
						<div class="post_detail_name">&nbsp;&nbsp;label&nbsp;&nbsp;</div>
						<?php echo "<span class=\"post_detail_value\">" . $post['labelName'] . "</span>"; ?>
						<br>
						
						<!-- 제목 -->
						<div class="post_detail_name">subject</div>
						<?php echo "<span class=\"post_detail_value\">" . $post['title'] . "</span>" . $important_mark; ?>
						<br>
						
						<!-- 작성자 -->
						<div class="post_detail_name">&nbsp;author&nbsp;</div>
						<?php echo "<span class=\"post_detail_value\">" . $post['authorName'] . "</span>"; ?>
						<br>
					</div>
					<div class="post_detail_view_info">
						<!-- 등록일 -->
						<div class="post_detail_name sub_content">date created</div>
						<?php echo "<span class=\"post_detail_value\">" . $post['createDate'] . "</span>"; ?>
						<br>
						
						<!-- 조회수 -->
						<div class="post_detail_name sub_content">&nbsp;view count&nbsp;&nbsp;</div>
						<?php echo "<span class=\"post_detail_value\">" . $post['viewCount'] . "</span>"; ?>
						<br>
						
						<!-- 좋아요수 -->
						<div class="post_detail_name sub_content">&nbsp;&nbsp;like count&nbsp;&nbsp;</div>
						<?php echo "<span class=\"post_detail_value\">" . $post['likeCount'] . "</span>"; ?>
						<br>
					</div>
					<div class="post_detail_view_info_content">
						<!-- 내용 -->
						<div class="post_detail_content_box">
						<?php echo $post['content']; ?>
						</div>
					</div>
				</div>
				<div class="post_detail_submit">
					<input type="button" value="modify" class="post_detail_btn" onclick="modifyPost();">
					<input type="button" value="back" class="post_detail_btn" onclick="window.history.back();">
				</div>
			<?php
			} else if ('create' == $mode) {
			?>
				<form method="post" action="/controller/post/createPost.php">
					<div class="post_detail_write">
						<div class="post_detail_name">&nbsp;&nbsp;label&nbsp;&nbsp;</div>
						<div class="post_detail_labels">
							<select id="labelList" name="labelSeq" class="post_detail_label" onChange="changeLabel(this);">
								<?php 
								// 선택 가능한 라벨 목록
								foreach($labelList as $label) {
									echo "<option value=\"" . $label["seq"] . "\">" . $label["name"] . "</option>";
								}
								?>
							</select>
						</div>
						<br>
						<div class="post_detail_name">subject</div>
						<input type="text" id="postTitle" name="title" size="100" class="post_detail_input_title" required="required"><br>
						<textarea id="postContent" name="content" required="required"></textarea>
					</div>
					<div class="post_detail_submit">
						<input type="submit" value="submit" class="post_detail_btn">
						<input type="button" value="back" class="post_detail_btn" onclick="window.history.back();">
					</div>
				</form>
			<?php
			} else if ('modify' == $mode) {
			?>
				<form method="post" action="/controller/post/modifyPost.php">
					<div class="post_detail_write">
						<!-- 라벨 -->
						<div class="post_detail_name">&nbsp;&nbsp;label&nbsp;&nbsp;</div>
						<div class="post_detail_labels">
							<select id="labelList" name="labelSeq" class="post_detail_label">
								<?php 
								// 선택 가능한 라벨 목록
								foreach($labelList as $label) {
									if ($post['labelSeq'] == $label["seq"]) {
										echo "<option value=\"" . $label["seq"] . "\" selected>" . $label["name"] . "</option>";
									} else {
										echo "<option value=\"" . $label["seq"] . "\">" . $label["name"] . "</option>";
									}
								}
								?>
							</select>
						</div>
						<br>
						<!-- 제목 -->
						<div class="post_detail_name">subject</div>
						<input type="text" id="postTitle" name="title" size="100" class="post_detail_input_title" required="required" value="<?php echo $post["title"];?>"><br>
						
						<!-- 내용 -->
						<textarea id="postContent" name="content" required="required">
						<?php echo $post["content"]; ?>
						</textarea>
						
						<?php 
						// hidden postSeq
						echo "<input type=\"hidden\" id=\"postSeq\" name=\"postSeq\" value=\"{$_REQUEST['postSeq']}\">";
						?>
					</div>
					<div class="post_detail_submit">
						<input type="submit" value="submit" class="post_detail_btn">
						<input type="button" value="back" class="post_detail_btn" onclick="window.history.back();">
					</div>
				</form>
			<?php
			} else {
				echo '페이지를 찾을 수 없습니다.';
			}
			?>
		</div>
			
		<?php
		require_once 'view/html/base/footer.php';
		?>
	</body>
</html>