/**
 * 게시물 상세보기/신규작성/수정 기능 스크립트
 */
$(document).ready(function() {
	// 게시물 내용 작성용 에디터 설정
	$('#postContent').summernote({
		height: 300,				// 에디터 높이
		minHeight: null,			// 최소 높이
		maxHeight: null,			// 최대 높이
		focus: true,				// 에디터 로딩 후 포커스 여부
		lang: "ko-KR",				// 한글 설정
		placeholder: '최대 2048자까지 작성할 수 있습니다.'		// placeholder 설정
	 });
});


/**
 * 게시물 수정 페이지로 이동
 */
function modifyPost(postSeq) {
	let url = '/view/html/post/postDetail.php';
	let param = '?mode=modify&postSeq=' + $('input#postSeq').val();
	location.href = url + param;
}