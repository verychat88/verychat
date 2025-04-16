/**
 * 슬라이더(배너) 관련 스크립트
 */
const slides = document.querySelectorAll('.slide');
let currentSlide = 0;
let slideInterval; // 자동 슬라이드 인터벌 변수
const intervalTime = 4000; // 4초마다 슬라이드 전환

const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');
const pauseBtn = document.getElementById('pauseBtn');

function showSlide(index) {
  slides.forEach((slide, i) => {
    slide.classList.remove('active');
    if (i === index) {
      slide.classList.add('active');
    }
  });
}

function nextSlide() {
  currentSlide++;
  if (currentSlide >= slides.length) {
    currentSlide = 0;
  }
  showSlide(currentSlide);
}
function prevSlide() {
  currentSlide--;
  if (currentSlide < 0) {
    currentSlide = slides.length - 1;
  }
  showSlide(currentSlide);
}

// 자동 재생 시작
function startSlide() {
  slideInterval = setInterval(nextSlide, intervalTime);
}
// 자동 재생 정지
function stopSlide() {
  clearInterval(slideInterval);
}

// 초기 슬라이드 설정
showSlide(currentSlide);
startSlide();

// 버튼 이벤트
nextBtn.addEventListener('click', () => {
  stopSlide();
  nextSlide();
  startSlide();
});
prevBtn.addEventListener('click', () => {
  stopSlide();
  prevSlide();
  startSlide();
});
pauseBtn.addEventListener('click', () => {
  // 일시정지/재생 기능 토글
  const icon = pauseBtn.querySelector('i');
  if (icon.classList.contains('fa-pause')) {
    stopSlide();
    icon.classList.remove('fa-pause');
    icon.classList.add('fa-play');
  } else {
    startSlide();
    icon.classList.remove('fa-play');
    icon.classList.add('fa-pause');
  }
});

/**
 * 모바일 햄버거 메뉴
 */
const hamburger = document.getElementById('hamburger');
const navbar = document.getElementById('navbar');
hamburger.addEventListener('click', () => {
  navbar.classList.toggle('show');
});

/**
 * 문의 폼 제출 이벤트
 */
const contactForm = document.getElementById('contactForm');
contactForm.addEventListener('submit', (e) => {
  e.preventDefault();
  alert('문의가 정상적으로 접수되었습니다. 빠른 시일 내에 연락 드리겠습니다.');
  contactForm.reset();
});
