document.addEventListener("DOMContentLoaded", () => {
  console.log("ready");

  class emsGallery {
    constructor(gallery) {
      this.gallery = document.getElementById(gallery.id);
      this.galleryWidth = this.gallery.getBoundingClientRect().width;
      this.galleryInner = this.gallery.querySelector(".ems-gallery-inner");

      this.innerEdge = parseInt(this.galleryInner.getBoundingClientRect().x);
      this.setItems();
      // Load the thumbnails versions onto the stage when the page loads
      //  so we can set the dimensions of each item.
      this.startersLoaded().then(() => {
        this.itemsPositionData();
        this.init();
      });
    }

    init() {
      this.setThumbnails();
      this.setThumbsHandler();
      this.resetSlide();
    }

    itemsPositionData() {
      this.positions = [];

      let v = 0;
      console.log(this.galleryWidth);
      let left = parseInt(this.galleryWidth);

      this.galleryItems.forEach((item, i) => {
        left = left - this.galleryItems[i].getBoundingClientRect().width;
        left = left / 2;
        this.positions[i] = { zero: v, left: left };
        v += item.getBoundingClientRect().width;
      });
      console.log(this.positions);
    }

    setThumbnails() {
      this.thumbnails = this.gallery
        .querySelector(".ems-gallery-thumbnails")
        .querySelectorAll("a");
    }

    setThumbsHandler() {
      this.thumbnails.forEach((thumb, i) => {
        this.thumbnails[i].addEventListener("click", (e) => {
          e.preventDefault();
          this.activePosition = this.positions[i].left;
          this.updateActive(i);
          this.resetSlide();
          this.moveToZero(i);
        });
      });
    }

    updateActive(index) {
      // calculate how much to translateX the active gallery item
      this.thumbnails.forEach((thumb, i) => {
        thumb.classList.remove("active");
      });
      this.thumbnails[index].classList.add("active");
    }

    moveToZero(index) {
      return new Promise((resolve) => {
        const x = -1 * this.positions[index].zero;
        this.galleryInner.setAttribute(
          "style",
          "transform:translateX(" + x + "px)"
        );
        resolve();
      }).then(() => {
        this.moveToMiddle(index);
      });
    }

    moveToMiddle(index) {
      this.galleryInner.style.paddingLeft =
        (this.gallery.getBoundingClientRect().width -
          this.galleryItems[index].getBoundingClientRect().width) /
          2 +
        "px";
    }

    resetSlide() {
      this.moveToZero(0);
    }

    calculateTranslation(index) {
      let i = 0;
      let val = this.positions[0].left;

      return val;
    }

    setItems() {
      this.galleryItems = document.querySelectorAll(
        ".ems-gallery-item-wrapper"
      );
    }
    // We need to know that the thumbs have loaded and dimensions set
    // before we make any calculations.
    startersLoaded() {
      return new Promise((resolve) => {
        let c = 0;
        this.galleryItems.forEach((item, i) => {
          const starter = document.createElement("img");
          //console.log(thumb);
          starter.addEventListener("load", (e) => {
            this.galleryItems[i].center =
              item.getBoundingClientRect().width / 2;
            c++;
            if (c == this.galleryItems.length) {
              resolve();
            }
          });

          starter.src = item.querySelector("img").src;
        });
      });
    }
    updateInnerEdge() {}
  } // End class.

  const emsGalleries = document.querySelectorAll(".ems-gallery");
  emsGalleries.forEach((gallery) => {
    new emsGallery(gallery);
  });
});
