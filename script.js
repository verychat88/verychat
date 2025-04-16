// 간단한 폼 제출 후 안내용 스크립트 예시
document.getElementById('contactForm').addEventListener('submit', function(event) {
  event.preventDefault();
  alert('문의가 접수되었습니다. 빠른 시일 내에 연락드리겠습니다.');
  // 실제 운영시에는 AJAX 요청이나 백엔드 연동 후 제출하는 코드를 넣으세요.
  this.reset();
});
