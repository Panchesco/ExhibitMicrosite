const blockColors = {
  blockSelector: ".block-form",
  blocks: [],
  elementSelector: null,
  elements: [],
  formstem: null,
  property: null,
  target: null,
  previewPrefix: "preview-",
  tilesSelector: ".color",
  tiles: [],
  hex: "",
  layouts: [],
  selectedLayout: null,
  addLinkButton: null,
  intId: false,
  blocksCount: 0,
  palette: [],
  init: function (config) {
    Object.assign(this, config);
    this.blocks = document.querySelectorAll(this.blockSelector);
    this.blocksCount = this.blocks.length;
    this.setLayouts();
    this.palettes();
    this.doNotSetBgHandler();
    this.doNotSetColorHandler();
  },
  palettes: function () {
    if (this.element !== null) {
      this.blocks.forEach((block) => {
        this.elements = block.querySelectorAll(this.elementSelector);
        this.elements.forEach((el) => {
          this.formstem = el.dataset.formstem;
          this.target = el.dataset.target;
          this.property = el.dataset.property;
          const colorPicker = el.querySelector(
            'input[name="' + this.target + '"]'
          );
          this.buildTiles(el);
          this.setTiles(el);
          this.setTileColors();
          this.tilesClickEventHandler();
          this.colorPickerEventHandler(
            colorPicker,
            this.property,
            this.formstem
          );
        });
      });
    }
  },
  buildTiles: function (el) {
    const wrapper = el.querySelector(".tiles");
    for (const key in this.palette) {
      const tile = document.createElement("div");
      tile.classList.add("color");
      tile.setAttribute("data-hex", this.palette[key]);
      tile.setAttribute("data-property", this.property);
      tile.setAttribute("data-target", el.dataset.target);
      tile.setAttribute("data-formstem", el.dataset.formstem);
      tile.setAttribute("title", this.palette[key]);
      wrapper.append(tile);
    }
  },
  setElements: function () {
    this.elements = document.querySelectorAll(this.elementSelector);
  },
  setTiles: function (el) {
    this.tiles = el.querySelectorAll(this.tilesSelector);
  },
  setTarget: function (val) {
    this.target = val;
  },
  setTileColors: function () {
    this.tiles.forEach((tile) => {
      tile.style.backgroundColor = tile.dataset.hex;
    });
  },
  tilesClickEventHandler: function () {
    this.tiles.forEach((tile) => {
      tile.addEventListener("click", (e) => {
        this.hex = e.target.dataset.hex;
        const property = e.target.dataset.property;
        const target = e.target.dataset.target;
        const formstem = e.target.dataset.formstem;
        this.updateInput(target, this.hex);
        this.setPreview(property, this.hex, formstem);
      });
    });
  },
  colorPickerEventHandler: function (colorPicker, property, formstem) {
    colorPicker.addEventListener("input", (e) => {
      this.setPreview(property, e.target.value, formstem);
      e.target.setAttribute("title", e.target.value);
    });
  },
  updateInput: function (target, value) {
    const elem = document.querySelector('[name="' + target + '"]');

    console.log(elem.id);
    elem.value = value;

    console.log("no-inline-" + elem.id);
    elem.setAttribute("title", value);
    document.getElementById("no-inline-" + elem.id).checked = false;
    document.getElementById("bg-text" + elem.id).value = value;
    document.getElementById("color-text-" + elem.id).value = value;
  },
  setPreview: function (property, value, formstem) {
    const target = this.previewPrefix + formstem;
    property = property.replace("_", "-", property);
    document.getElementById(target).style[property] = value;
  },
  setLayouts: function () {
    this.layouts = document.querySelectorAll(".layout");
    this.newLayoutHandler();
  },
  getSelectedLayout: function () {
    this.selectedLayout = document.querySelector("layout selected");
  },
  newLayoutHandler: function () {
    this.layouts.forEach((layout) => {
      layout.addEventListener("click", () => {
        btn = this.getAddLinkButton();
        btn.addEventListener("click", () => {
          this.intId = setInterval(() => {
            const blocks = document.querySelectorAll(this.blockSelector);
            if (blocks.length > this.blocksCount) {
              this.init({ elementSelector: ".palette" });
              this.blocksCount = blocks.length;
              this.stopInt();
            }
          }, 300);
        });
      });
    });
  },
  doNotSetBgHandler: function () {
    const noBg = document.querySelectorAll(".no-inline-bg > input");
    noBg.forEach((elem, i) => {
      elem.addEventListener("input", (e) => {
        if (e.target.value == "inherit" && e.target.checked) {
          document.getElementById(
            e.target.dataset.preview
          ).style.backgroundColor = "inherit";
        } else {
          document.getElementById(
            e.target.dataset.preview
          ).style.backgroundColor = document.getElementById(
            "bg-" + elem.name
          ).value;
        }
        this.doNotSetBgHandler();
      });
    });
  },
  doNotSetColorHandler: function () {
    const noColor = document.querySelectorAll(".no-inline-color > input");
    noColor.forEach((elem, i) => {
      elem.addEventListener("input", (e) => {
        if (e.target.value == "inherit" && e.target.checked) {
          document.getElementById(e.target.dataset.preview).style.color =
            "inherit";
        } else {
          document.getElementById(e.target.dataset.preview).style.color =
            document.getElementById("color-" + elem.name).value;
        }
        this.doNotSetColorHandler();
      });
    });
  },
  stopInt: function () {
    clearInterval(this.intId);
    this.intId = false;
  },
  getAddLinkButton: function () {
    return document.querySelector(".add-link");
  },
}; // End blockColors

document.addEventListener("DOMContentLoaded", () => {
  blockColors.init({
    elementSelector: ".palette",
    palette: exhibitMicrosite.palette,
  });
});
