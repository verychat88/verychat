
function toggleSubmenu(id) {
  const el = document.getElementById(id);
  if (el.style.display === "block") {
    el.style.display = "none";
  } else {
    el.style.display = "block";
  }
}
