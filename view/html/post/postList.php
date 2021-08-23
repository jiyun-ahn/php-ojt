<?php
require_once 'view/html/base/header.php';
?>

<link href="/view/css/base/normal.css" rel="stylesheet" type="text/css" />
<link href="/view/css/post.css" rel="stylesheet" type="text/css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="/view/js/post.js"></script>

<?php
require_once 'service/post/PostService.php';
require_once 'service/post/LabelService.php';

$labelSeq = 0;
$page = 0;
$limit = 10;

if ($_REQUEST) {
	$labelSeq = $_REQUEST['labelSeq'];
	$page = $_REQUEST['page'];
	$limit = $_REQUEST['limit'];
}

$labelService = new LabelService();
$labelList = $labelService->selectLabelList();

$postService = new PostService();
$postList =  $postService->selectPostList($labelSeq, $page, $limit);

echo "<input id=\"labelSeq\" type=\"hidden\" value=\"{$labelSeq}\">";
echo "<input id=\"page\" type=\"hidden\" value=\"{$page}\">";
echo "<input id=\"limit\" type=\"hidden\" value=\"{$limit}\">";

?>
<input id="changeLabel" type="hidden" value="0">

<div class="post_list_main">
	<hr>
	<div class="post_list_menubar">
		<div class="post_list_menubar_labels">
			<select id="labelList" class="post_list_menubar_label" onChange="changeLabel(this);">
				<option value="0">전체</option>
				<?php 
				// 선택 가능한 라벨 목록
				foreach($labelList as $label) {
					if ($_REQUEST['labelSeq'] == $label["seq"]) {
						echo "<option value=\"" . $label["seq"] . "\" selected>" . $label["name"] . "</option>";
					} else {
						echo "<option value=\"" . $label["seq"] . "\">" . $label["name"] . "</option>";
					}
				}
				?>
			</select>
		</div>
		<div class="post_list_menubar_btns">
			<input id="btnImportant" type="button" value="중요 표시" class="post_list_menu_btn_2" onclick="modifyPost('important', 'Y');">
			<input id="btnImportant" type="button" value="중요 해제" class="post_list_menu_btn_2" onclick="modifyPost('important', 'N');">
			<input id="btnDelete" type="button" value="일괄삭제" class="post_list_menu_btn_2" onclick="deletePost();">
			<input id="btnModify" type="button" value="라벨변경" class="post_list_menu_btn_2" onclick="modifyPost('label');">
			<input id="btnWrite" type="button" value="글쓰기" class="post_list_menu_btn_1" onclick="writePost();">
		</div>
	</div>
	<div class="post_list_header">
		<div class="post_list_header_element" style="width: 3%">&nbsp;</div>
		<div class="post_list_header_element" style="width: 10%">라벨</div>
		<div class="post_list_header_element" style="width: 55%">제목</div>
		<div class="post_list_header_element" style="width: 12%">작성일</div>
		<div class="post_list_header_element" style="width: 10%">작성자</div>
		<div class="post_list_header_element" style="width: 10%">조회수</div>
	</div>
	<div class="post_list_content">
		<?php 
		// 게시물 리스트 출력
		foreach($postList as $post) {
			$important_mark = "";
			if ("Y" == $post["importantYN"]) {
				$important_mark = "<span class=\"important_mark\">★&nbsp;</span>";
			}
			
			echo "<div class=\"post_list_content_element\" style=\"width: 3%\">"
				."<input id=\"postSeq\" type=\"checkbox\" value=\"" . $post["seq"] . "\"></div>"
				."<div class=\"post_list_content_element\" style=\"width: 10%\">" . $post["labelName"] . "</div>"
				."<div class=\"post_list_content_element post_title\" style=\"width: 55%\" onclick=\"postDetailView('" . $post["seq"] . "')\">" . $important_mark . $post["title"] . "</div>"
				."<div class=\"post_list_content_element\" style=\"width: 10%\">" . $post["createDate"] . "</div>"
				."<div class=\"post_list_content_element\" style=\"width: 10%\">" . $post["authorName"] . "</div>"
				."<div class=\"post_list_content_element\" style=\"width: 10%\">" . $post["viewCount"] . "</div>";
		}
		?>
	</div>
	<div class="post_list_nav">
	</div>
	<div id="selectLabel" class="post_list_select_label">
		<b>선택한 게시물을 아래의 라벨로 변경합니다.</b>
		<hr>
		<?php
		// 선택 가능한 라벨 버튼
		foreach($labelList as $label) {
			echo "<input type=\"button\" value=\"" . $label["name"] . "\" onclick=\"selectLabel(" . $label["seq"] . ")\">";
		}
		?>
	</div>
</div>

<?php 
require_once 'view/html/base/footer.php';
?>