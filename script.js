// 모바일 햄버거 메뉴 토글
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('nav-menu');

hamburger.addEventListener('click', () => {
  navMenu.classList.toggle('active');
});

// 간단한 문의 폼 제출 후 알림 (실제 백엔드 연동 필요 시 교체)
document.getElementById('contactForm').addEventListener('submit', function(event) {
  event.preventDefault();
  alert('문의가 접수되었습니다. 빠른 시일 내에 연락드리겠습니다.');
  this.reset();
});
