<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="승강기 전문 솔루션 - 헤더와 슬라이드 일체형 후 스크롤 시 분리되는 효과" />
  <title>승강기 전문 솔루션 - 메인</title>
  <!-- 구글 폰트 및 Font Awesome -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- 공통 스타일 -->
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- 헤더 (초기엔 슬라이드와 겹치며 투명하게 보임) -->
  <header class="header" id="header">
    <div class="container header-container">
      <div class="logo">
        <a href="#">승강기 전문 솔루션</a>
      </div>
      <nav class="nav" id="navbar">
        <ul>
          <li><a href="#">회사소개</a></li>
          <li><a href="#">서비스</a></li>
          <li><a href="#">성공사례</a></li>
          <li><a href="#">문의하기</a></li>
        </ul>
      </nav>
      <div class="hamburger" id="hamburger">
        <i class="fa fa-bars"></i>
      </div>
    </div>
  </header>

  <!-- 메인 슬라이더 (Hero 영역) -->
  <section class="hero" id="hero">
    <div class="slider-container" id="sliderContainer">
      <!-- 첫 번째 슬라이드: 1.png -->
      <div class="slide active" style="background-image: url('https://verychat88.github.io/1.png');">
        <div class="overlay"></div>
        <div class="hero-content">
          <h2>신속한 현장 대응</h2>
          <p>최첨단 유지보수 시스템으로 문제를 빠르게 해결합니다.</p>
        </div>
      </div>
      <!-- 두 번째 슬라이드: 2.png -->
      <div class="slide" style="background-image: url('https://verychat88.github.io/2.png');">
        <div class="overlay"></div>
        <div class="hero-content">
          <h2>전문 기술과 풍부한 경험</h2>
          <p>다년간의 현장 경험으로 안전하고 신뢰할 수 있는 솔루션을 제공합니다.</p>
        </div>
      </div>
    </div>
    <!-- 슬라이드 컨트롤 버튼 (필요 시) -->
    <div class="slider-controls">
      <button id="prevBtn" class="btn-control"><i class="fa fa-chevron-left"></i></button>
      <button id="nextBtn" class="btn-control"><i class="fa fa-chevron-right"></i></button>
      <button id="pauseBtn" class="btn-control"><i class="fa fa-pause"></i></button>
    </div>
  </section>

  <!-- 스크롤 시 나타나는 일반 콘텐츠 (예시) -->
  <section class="content">
    <div class="container">
      <h2>Welcome to Our Service</h2>
      <p>
        스크롤을 내리면 헤더와 슬라이드가 분리되어, 회사 및 서비스에 관한 자세한 정보를 확인할 수 있습니다.
      </p>
    </div>
  </section>

  <!-- 푸터 -->
  <footer class="footer">
    <div class="container footer-container">
      <div class="footer-left">
        <p class="footer-logo">승강기 전문 솔루션</p>
        <p class="footer-desc">&copy; 2025. All rights reserved.</p>
      </div>
      <div class="footer-right">
        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
        <a href="#"><i class="fa-brands fa-youtube"></i></a>
      </div>
    </div>
  </footer>

  <!-- 플로팅 퀵메뉴 (고객센터, 카톡 상담) -->
  <div class="floating-buttons">
    <a href="tel:01058255626" class="floating-btn"><i class="fa-solid fa-phone"></i> 고객센터</a>
    <a href="https://open.kakao.com/o/your_kakao_link" target="_blank" class="floating-btn">
      <i class="fa-brands fa-kakao"></i> 카톡 상담
    </a>
  </div>

  <script src="script.js"></script>
</body>
</html>
