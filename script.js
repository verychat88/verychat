// 슬라이더 기능
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
    if (i === index) slide.classList.add('active');
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

if(nextBtn) {
  nextBtn.addEventListener('click', () => {
    stopSlide();
    nextSlide();
    startSlide();
  });
}
if(prevBtn) {
  prevBtn.addEventListener('click', () => {
    stopSlide();
    prevSlide();
    startSlide();
  });
}
if(pauseBtn) {
  pauseBtn.addEventListener('click', () => {
    const icon = pauseBtn.querySelector('i');
    if(icon.classList.contains('fa-pause')) {
      stopSlide();
      icon.classList.replace('fa-pause','fa-play');
    } else {
      startSlide();
      icon.classList.replace('fa-play','fa-pause');
    }
  });
}

// 모바일 햄버거 메뉴 토글
const hamburger = document.getElementById('hamburger');
const navbar = document.getElementById('navbar');
if(hamburger) {
  hamburger.addEventListener('click', () => {
    navbar.classList.toggle('show');
  });
}

// 모달(승강기 검사 및 법령 상세 내용)
const openLawModalBtn = document.getElementById('openLawModal');
const lawModal = document.getElementById('lawModal');
const closeLawModalBtn = document.getElementById('closeLawModal');

if(openLawModalBtn) {
  openLawModalBtn.addEventListener('click', () => {
    lawModal.style.display = 'flex';
  });
}
if(closeLawModalBtn) {
  closeLawModalBtn.addEventListener('click', () => {
    lawModal.style.display = 'none';
  });
}
window.addEventListener('click', (e) => {
  if(e.target === lawModal) {
    lawModal.style.display = 'none';
  }
});
