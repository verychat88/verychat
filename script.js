/**
 * 슬라이더 관련 스크립트
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
