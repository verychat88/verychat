/**
 * 슬라이더(배너) 관련 스크립트
 */
const slides = document.querySelectorAll('.slide');
let currentSlide = 0;
let slideInterval; 
const intervalTime = 4000;

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
  currentSlide = (currentSlide + 1) % slides.length;
  showSlide(currentSlide);
}

function prevSlide() {
  currentSlide = (currentSlide - 1 + slides.length) % slides.length;
  showSlide(currentSlide);
}

function startSlide() {
  slideInterval = setInterval(nextSlide, intervalTime);
}

function stopSlide() {
  clearInterval(slideInterval);
}

showSlide(currentSlide);
startSlide();

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
  const icon = pauseBtn.querySelector('i');
  if (icon.classList.contains('fa-pause')) {
    stopSlide();
    icon.classList.replace('fa-pause', 'fa-play');
  } else {
    startSlide();
    icon.classList.replace('fa-play', 'fa-pause');
  }
});

/**
 * 모바일 햄버거 메뉴 토글
 */
const hamburger = document.getElementById('hamburger');
const navbar = document.getElementById('navbar');
hamburger.addEventListener('click', () => {
  navbar.classList.toggle('show');
});

/**
 * 견적 요청 폼 제출 이벤트
 * (실제 운영 시 Formspree, Netlify Forms 등 서버리스 솔루션으로 연동하여 droes@naver.com으로 전송)
 */
const contactForm = document.getElementById('contactForm');
contactForm.addEventListener('submit', (e) => {
  e.preventDefault();
  // 폼 제출 후 이메일 전송 처리 로직(서비스 연동 필요)
  alert('문의 및 견적 요청이 접수되었습니다.\n빠른 시일 내에 연락드리겠습니다.');
  contactForm.reset();
});
