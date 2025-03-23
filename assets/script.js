
function loadPage(page) {
  fetch('pages/' + page)
    .then(res => res.text())
    .then(html => {
      document.getElementById('content').innerHTML = html;
    });
}
document.querySelectorAll('#menu li[data-page]').forEach(item => {
  item.addEventListener('click', () => {
    loadPage(item.getAttribute('data-page'));
  });
});
document.getElementById('searchInput').addEventListener('input', function() {
  const filter = this.value.toLowerCase();
  document.querySelectorAll('#menu > li').forEach(item => {
    const text = item.textContent.toLowerCase();
    item.style.display = text.includes(filter) ? '' : 'none';
  });
});
function switchLang() {
  alert('다국어 전환 기능은 추후 구현됩니다.');
}
