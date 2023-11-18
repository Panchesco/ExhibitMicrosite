//document.addEventListener("DOMContentLoaded", () => {
function exhibitOptionsDisplay() {
  const options_form = document.getElementById("water-options-wrapper");
  const options_thumb = document.getElementById("water-exhibit-page-options");
  if (options_form) {
    options_thumb.style.display = "none";
  }
}
exhibitOptionsDisplay();
document.addEventListener("DOMContentLoaded", () => {
  exhibitOptionsDisplay();
});

//});
