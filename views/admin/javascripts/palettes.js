document.addEventListener("DOMContentLoaded", () => {
  class blockColors {
    constructor() {
      this.elementSelector = ".palettes-preview-wrapper";
      this.setProps = ["color", "backgroundColor"];
      // We need some default colors for when the color picker inputs are reset.
      this.defaults = ["#222222", "#ffffff"];
      this.blocksCount = document.querySelectorAll(".block-form").length;
      this.addBlockSelector = ".add-link.big.button";
    }

    init(config) {
      this.colors = [];
      this.blocks = document.querySelectorAll(".block-form");
      this.palettes = document.querySelectorAll(this.elementSelector);
      this.paletteBlockCount = this.palettes.length;

      this.palettes.forEach((pal, i) => {
        this.palettes[i].tileSets = pal.querySelectorAll(".tiles");
      });
      this.setPreviewContainers()
        .then(this.setColors())
        .then(this.buildTiles())
        .then(this.setInputs())
        .then(this.setColorPickers())
        .then(this.setColorPickerHandler())
        .then(this.setTileEventHandler())
        .then(this.setCheckboxes())
        .then(this.setCheckboxHandlers())
        .then(this.setActiveTile())
        .then(this.newBlockHandler());
    }

    setPreviewContainers() {
      return new Promise((resolve) => {
        this.palettes.forEach((pal, i) => {
          this.palettes[i].preview = pal.querySelector(".preview");
        });
        resolve();
      });
    }

    setColors() {
      return new Promise((resolve) => {
        if (typeof exhibitMicrosite.palette !== "undefined") {
          this.colors = exhibitMicrosite.palette;
        } else {
          this.colors = [];
        }
        resolve();
      });
    }

    buildTiles() {
      return new Promise((resolve) => {
        this.palettes.forEach((pal, i) => {
          this.palettes[i].tileSets.forEach((set, c) => {
            this.palettes[i].tileSets[c].tiles = [];
            set.innerHTML = "";
            for (const key in this.colors) {
              const tile = document.createElement("div");
              const jsonData = {
                prop: this.setProps[c],
                pal: i,
                set: c,
                hex: this.colors[key],
                inherit: false,
              };
              const jsonDataStr = JSON.stringify(jsonData);
              tile.classList.add("color");
              tile.setAttribute("data-data", jsonDataStr);
              tile.setAttribute("title", this.colors[key]);
              tile.style.backgroundColor = this.colors[key];
              this.palettes[i].tileSets[c].append(tile);
              this.palettes[i].tileSets[c].tiles.push(tile);
            }
          });
        });
      });
      resolve();
    }

    setInputs() {
      this.palettes.forEach((pal, i) => {
        this.palettes[i].inputs = pal.querySelectorAll(".color-choice");
      });
    }

    setColorPickers() {
      return new Promise((resolve) => {
        this.palettes.forEach((pal, i) => {
          this.palettes[i].colorPickers = pal.querySelectorAll(
            'input[type="color"]'
          );
        });
      });
      resolve();
    }

    setColorPickerHandler() {
      return new Promise((resolve) => {
        this.palettes.forEach((pal, i) => {
          this.palettes[i].colorPickers.forEach((pic, p) => {
            this.palettes[i].colorPickers[p].addEventListener("input", (e) => {
              const data = {
                prop: pic.dataset.prop,
                pal: i,
                set: p,
                hex: pic.value,
                inherit: false,
              };
              this.updatePreview(data);
              this.updateValues(data);
              this.setCheckboxes();
            });
          });
        });
        resolve();
      });
    }

    setTileEventHandler() {
      return new Promise((resolve) => {
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
      });
    }

    setCheckboxes() {
      return new Promise((resolve) => {
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
        resolve();
      });
    }

    setCheckboxHandlers() {
      return new Promise((resolve) => {
        this.palettes.forEach((pal, i) => {
          this.palettes[i].checkboxes.forEach((box, b) => {
            this.palettes[i].checkboxes[b].addEventListener("input", (e) => {
              if (e.target.checked) {
                const data = {};
                if (b == 0) {
                  data.prop = "color";
                  data.pal = i;
                  data.set = b;
                  data.hex = this.defaults[b];
                  data.inherit = true;
                } else if (b == 1) {
                  data.prop = "backgroundColor";
                  data.pal = i;
                  data.set = b;
                  data.hex = this.defaults[b];
                  data.inherit = true;
                }
                this.updatePreview(data);
                this.updateValues(data);
                this.setActiveTile();
              }
            });
          });
        });
        resolve();
      });
    }

    newBlockHandler() {
      const count = this.blocksCount;
      document
        .querySelector(this.addBlockSelector)
        .addEventListener("click", (e) => {
          let i = 0;
          let intId = setInterval(() => {
            if (document.querySelectorAll(".block-form").length > count) {
              clearInterval(intId);
              this.blocksCount++;
              setTimeout(() => {
                this.init();
              }, 200);
            }
            i++;
          }, 300);
        });
    }

    updateColorChoice(data) {
      // Update the preview.
      this.updatePreview(data);
      // Update color picker and input values.
      this.updateValues(data);
    }

    updatePreview(data) {
      this.palettes[data.pal].preview.style[data.prop] =
        data.inherit === true ? "inherit" : data.hex;
    }

    updateValues(data) {
      this.palettes[data.pal].inputs[data.set].value = data.hex;
      this.palettes[data.pal].colorPickers[data.set].value = data.hex;
      this.setActiveTile();
    }

    clearValues(data) {
      this.palettes[data.pal].inputs[data.set].setAttribute("value", "inherit");
      this.palettes[data.pal].colorPickers[data.set].setAttribute(
        "value",
        data.hex
      );
    }

    setActiveTile() {
      return new Promise((resolve) => {
        this.palettes.forEach((pal, i) => {
          this.palettes[i].tileSets.forEach((set, s) => {
            const colorChoice = pal.inputs[s].value;
            set.querySelectorAll(".color").forEach((color, c) => {
              if (colorChoice == color.title) {
                color.classList.add("active");
              } else {
                color.classList.remove("active");
              }
            });
          });
        });
        resolve();
      });
    }

    updateInput(name, value) {
      document
        .querySelector('[name="' + name + '"]')
        .setAttribute("value", value);
    }
  } // End class.

  const palettes = new blockColors();

  palettes.init({
    elementSelector: ".palette",
    palette: exhibitMicrosite.palette,
  });
});
