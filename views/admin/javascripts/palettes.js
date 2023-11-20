document.addEventListener("DOMContentLoaded", () => {
  class blockColors {
    constructor() {
      this.elementSelector = ".palettes-preview-wrapper";
      this.setProps = ["backgroundColor", "color"];
      // We need some default colors for when the color picker inputs are reset.
      this.defaults = ["#ffffff", "#222222"];
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
      this.setColorPickerHandler();
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
              this.palettes[i].checkboxes[c].checked = false;
            });
          });
        });
      });
    }

    updateColorChoice(data) {
      // Update the preview.
      this.updatePreview(data);
      // Update color picker and input values.
      this.updateValues(data);
    }

    updatePreview(data) {
      this.palettes[data.pal].preview.style[data.prop] = data.hex;
    }

    updateValues(data) {
      this.palettes[data.pal].inputs[data.set].setAttribute("value", data.hex);
      this.palettes[data.pal].colorPickers[data.set].setAttribute(
        "value",
        data.hex
      );
    }

    clearValues(data) {
      this.palettes[data.pal].inputs[data.set].setAttribute("value", "inherit");
      this.palettes[data.pal].colorPickers[data.set].setAttribute(
        "value",
        data.hex
      );
    }

    updateInput(name, value) {
      document
        .querySelector('[name="' + name + '"]')
        .setAttribute("value", value);
    }

    setColorPickers() {
      this.palettes.forEach((pal, i) => {
        this.palettes[i].colorPickers = pal.querySelectorAll(
          'input[type="color"]'
        );
      });
    }

    setColorPickerHandler() {
      this.palettes.forEach((pal, i) => {
        this.palettes[i].colorPickers.forEach((pic, p) => {
          this.palettes[i].colorPickers[p].addEventListener("input", (e) => {
            const data = {
              prop: pic.dataset.prop,
              pal: i,
              set: p,
              hex: pic.value,
            };
            this.updatePreview(data);
            this.updateValues(data);
          });
        });
      });
    }

    setCheckboxes() {
      this.palettes.forEach((pal, i) => {
        this.palettes[i].checkboxes = pal.querySelectorAll(".no-inline");
      });
      this.palettes.forEach((pal, i) => {
        pal.checkboxes.forEach((box, b) => {
          if (this.palettes[i].inputs[b].value == "inherit") {
            box.checked = true;
          } else {
            box.checked = false;
          }
        });
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
              if (b == 0) {
                data.prop = "backgroundColor";
                data.pal = i;
                data.set = b;
                data.hex = this.defaults[b];
                this.updatePreview(data);
                this.clearValues(data);
              } else if (b == 1) {
                data.prop = "color";
                data.pal = i;
                data.set = b;
                data.hex = this.defaults[b];
                this.updatePreview(data);
                this.clearValues(data);
              }
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
