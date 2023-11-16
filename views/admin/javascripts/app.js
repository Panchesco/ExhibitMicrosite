/**
 * Hide the Bootstrap Flex blocks if plugin not active.
 */
document.addEventListener("DOMContentLoaded", () => {
  if (typeof microsite !== "undefined") {
    if (microsite.theme_name == "water") {
      const micrositeBlocks = document.querySelectorAll('[id^="flex-"]');
      micrositeBlocks.forEach((block) => {
        block.style.display = "inline-block";
        block.style.backgroundColor = "#E0EFFA";
      });
    }
  }
});
