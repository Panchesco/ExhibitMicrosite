// Limit the number of items that can be added to an exhibitMicrosite ExhibitBuilder block.
const exhibitMicrositeLimitItems = {
  limit: 1,
  blocks: [],
  blocksSelectors: [],
  applyAttachmentBtn: null,
  activeBlockIndex: null,
  init: function (config) {
    Object.assign(this, config);
    this.applyAttachmentBtn = document.getElementById("apply-attachment");
    this.applyBtnHandler();
    this.setBlocks();

    this.blocks.forEach((block, i) => {
      this.blocks[i].formstem =
        block.querySelector("[data-formstem]").dataset.formstem;
      this.blocks[i].selectedItems = block.querySelectorAll(".attachment");
      this.blocks[i].addItemBtn = block.querySelector(".add-item");
      this.blocks[i].interval = null;
      this.activeItemIndex = i;
      this.itemHandling(i);
      this.blocks[i].addEventListener("click", (e) => {
        this.itemHandling(i);
      });
    });
  },
  setBlocks: function () {
    this.blocks = document.querySelectorAll(".block-form");
  },
  applyBtnHandler() {
    if (this.applyAttachmentBtn !== null) {
      this.applyAttachmentBtn.addEventListener("click", (e) => {
        // TODO: Create watcher that doesn't rely on setTimeout
        setTimeout(() => {
          this.itemHandling();
        }, 2000);
      });
    }
  },
  itemInterval: function (blocksIndex) {},
  itemHandling: function (i) {
    const items = this.blocks[i].querySelectorAll(".attachment");
  },
  stopInt: function (blocksIndex) {},
};

document.addEventListener("DOMContentLoaded", () => {
  const config = {
    blocksSelectors: ["flex-file"],
  };

  if (typeof document.getElementById("apply-attachment") != "undefined") {
    exhibitMicrositeLimitItems.init(config);
  }
});
