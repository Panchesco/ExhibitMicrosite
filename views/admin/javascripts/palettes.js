document.addEventListener("DOMContentLoaded", () => {
  class blockColors {
    constructor() {
      this.elementSelector = ".palettes-preview-wrapper";
      this.setProps = ["backgroundColor", "color"];
      // We need some default colors for when the color picker inputs are reset.
      this.defaultBackgroundColor = "#ffffff";
      this.defaultColor = "#222222";
    }

    init(confi) {
      this.palettes = document.querySelectorAll(this.elementSelector);
      this.palettes.forEach((pal, i) => {
        this.palettes[i].tileSets = pal.querySelectorAll(".tiles");
      });
      this.setPreviewContainers();
      this.setColors();
      this.buildTiles();
      this.setInputs();
      this.setColorPickers();
      this.setCheckboxes();
      this.setTileEventHandler();
      this.setCheckboxes();
      this.setCheckboxHandlers();
    }

    setColors() {
      if (typeof exhibitMicrosite.palette !== "undefined") {
        this.colors = exhibitMicrosite.palette;
      } else {
        this.colors = [];
      }
    }

    buildTiles() {
      this.palettes.forEach((pal, i) => {
        this.palettes[i].tileSets.forEach((set, c) => {
          for (const key in this.colors) {
            const tile = document.createElement("div");
            const jsonData = {
              prop: this.setProps[c],
              pal: i,
              set: c,
              hex: this.colors[key],
            };
            const jsonDataStr = JSON.stringify(jsonData);
            tile.classList.add("color");
            tile.setAttribute("data-data", jsonDataStr);
            tile.setAttribute("title", this.colors[key]);
            tile.style.backgroundColor = this.colors[key];
            this.palettes[i].tileSets[c].append(tile);
          }
        });
      });
    }

    setInputs() {
      this.palettes.forEach((pal, i) => {
        this.palettes[i].inputs = pal.querySelectorAll(".color-choice");
      });
    }

    setTileEventHandler() {
      this.palettes.forEach((pal, i) => {
        this.palettes[i].tileSets.forEach((set, c) => {
          const tiles = set.querySelectorAll(".color");
          tiles.forEach((tile, t) => {
            tile.addEventListener("click", (e) => {
              const data = JSON.parse(e.target.dataset.data);
              // Update color choice
              this.updateColorChoice(data);
            });
          });
        });
      });
    }

    updateColorChoice(data) {
      // Update the preview
      console.log(data);
      this.updateStyle(data);
    }

    updateStyle(data) {
      this.palettes[data.pal].preview.style[data.prop] = data.hex;
    }

    updateValue(data) {
      this.palettes[data.pal].inputs[data.set].setAttribute("value", data.hex);
    }

    updateInput(name, value) {
      document
        .querySelector('[name="' + name + '"]')
        .setAttribute("value", value);
    }

    setColorPickers() {
      this.palettes.forEach((pal, i) => {
        this.palettes[i].colorPicker = pal.querySelector('input[type="color"]');
      });
    }

    setCheckboxes() {
      this.palettes.forEach((pal, i) => {
        this.palettes[i].checkboxes = pal.querySelectorAll(".no-inline");
      });
    }

    setPreviewContainers() {
      this.palettes.forEach((pal, i) => {
        this.palettes[i].preview = pal.querySelector(".preview");
      });
    }

    setCheckboxHandlers() {
      this.palettes.forEach((pal, i) => {
        this.palettes[i].checkboxes.forEach((box, b) => {
          box.addEventListener("input", (e) => {
            const data = {};
            if (e.target.checked) {
              // Set the input value to "inherit";
              //this.palettes[i].
            } else {
              console.log("not checked");
            }
          });
        });
      });
    }
  } // End class.

  const palettes = new blockColors();

  palettes.init({
    elementSelector: ".palette",
    palette: exhibitMicrosite.palette,
  });
});
