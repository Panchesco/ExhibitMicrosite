/**
 * Hide the ExhibitMicrosite blocks if plugin not active or
 * selected Exhibit theme is not Water.
 */
document.addEventListener("DOMContentLoaded", () => {
  if (typeof exhibitMicrosite !== "undefined") {
    if (exhibitMicrosite.theme_name == "water") {
      const exhibitMicrositeBlocks =
        document.querySelectorAll('[id^="water-"]');
      exhibitMicrositeBlocks.forEach((block) => {
        block.style.display = "inline-block";
        block.style.backgroundColor = "#E0EFFA";
      });
    }
  }
});
