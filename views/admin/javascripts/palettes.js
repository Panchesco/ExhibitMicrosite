document.addEventListener("DOMContentLoaded", () => {
  class blockColors {
    constructor() {
      this.elementSelector = ".palettes-preview-wrapper";
    }

    init(confi) {
      this.palettes = document.querySelectorAll(this.elementSelector);
      this.palettes.forEach((elem, i) => {
        this.palettes[i].tileSets = elem.querySelectorAll(".tiles");
      });
      this.setColors();
      this.buildTiles();
      this.setColorPickers();
      console.log(this.colors);
    }

    setColors() {
      if (typeof exhibitMicrosite.palette !== "undefined") {
        this.colors = exhibitMicrosite.palette;
      } else {
        this.colors = [];
      }
    }

    buildTiles() {
      this.palettes.forEach((elem, i) => {
        this.palettes[i].tileSets.forEach((set, c) => {
          for (const key in this.colors) {
            const tile = document.createElement("div");
            tile.classList.add("color");
            tile.setAttribute("data-hex", this.colors[key]);
            tile.setAttribute("title", this.colors[key]);
            tile.style.backgroundColor = this.colors[key];
            this.palettes[i].tileSets[c].append(tile);
          }
        });
      });
    }

    setColorPickers() {
      this.palettes.forEach((elem, i) => {
        this.palettes[i].colorPicker = elem.querySelector(
          'input[type="color"]'
        );
      });
    }
  }

  const palettes = new blockColors();

  palettes.init({
    elementSelector: ".palette",
    palette: exhibitMicrosite.palette,
  });
});
