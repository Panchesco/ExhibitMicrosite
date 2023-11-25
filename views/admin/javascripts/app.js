/**
 * Hide the ExhibitMicrosite blocks if plugin not active or
 * selected Exhibit theme is not Water.
 */
document.addEventListener("DOMContentLoaded", () => {
  if (typeof exhibitMicrosite !== "undefined") {
    if (exhibitMicrosite.theme_name == "water") {
      const exhibitMicrositeBlocks = document.querySelectorAll('[id^="flex-"]');
      exhibitMicrositeBlocks.forEach((block) => {
        block.style.display = "inline-block";
        block.style.backgroundColor = "#E0EFFA";
      });
    }
  }

  //   const microsite_inputs = document.querySelectorAll(
  //     ".exhibit-microsite-options"
  //   );
  //
  //   const microsite_options = document.getElementById("exhibit-microsite-name");
  //
  //   function buildOptionsString(exhibitId) {
  //     const collections = document.querySelectorAll(
  //       'input[name="collection[]"]:checked'
  //     );
  //     const cIDs = [];
  //     collections.forEach((col) => {
  //       cIDs.push(col.value);
  //     });
  //
  //     const options = { exhibit_id: exhibitId, collection_ids: cIDs };
  //     microsite_options.name = "exhibit_microsite[" + exhibitId + "]";
  //     microsite_options.setAttribute("value", JSON.stringify(options));
  //   }
  //
  //   microsite_inputs.forEach((elem, i) => {
  //     elem.addEventListener("input", (e) => {
  //       const exhibitId = document.getElementById(
  //         "exhibit-microsite-exhibit"
  //       ).value;
  //       if (exhibitId != 0) {
  //         buildOptionsString(exhibitId);
  //       } else {
  //         microsite_options.value = "";
  //       }
  //     });
  //   });
});
