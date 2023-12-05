document.addEventListener("DOMContentLoaded", () => {
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
      this.getFullSize();
      this.setActiveIndex();
      this.nextButtonHandler();
      this.prevButtonHandler();
    }

    setActiveIndex() {
      if (this.thumbnails.length > 0) {
        this.activeIndex = 0;
      }
    }

    prevButtonHandler() {
      this.prevBtn = this.gallery.querySelector(".ems-gallery-prev");
      this.prevBtn.addEventListener("click", (e) => {
        e.preventDefault();
        if (this.activeIndex > 0) {
          this.activeIndex--;
        } else {
          this.activeIndex = this.thumbnails.length - 1;
        }
        this.updateActive();
        this.moveToZero();
      });
    }

    nextButtonHandler() {
      this.nextBtn = this.gallery.querySelector(".ems-gallery-next");
      this.nextBtn.addEventListener("click", (e) => {
        e.preventDefault();
        if (this.activeIndex < this.thumbnails.length - 1) {
          this.activeIndex++;
        } else {
          this.activeIndex = 0;
        }
        this.updateActive();
        this.moveToZero();
      });
    }

    itemsPositionData() {
      this.positions = [];

      let v = 0;

      let left = parseInt(this.galleryWidth);

      this.galleryItems.forEach((item, i) => {
        left = left - this.galleryItems[i].getBoundingClientRect().width;
        left = left / 2;
        this.positions[i] = { zero: v, left: left };
        v += item.getBoundingClientRect().width;
      });
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
          this.activeIndex = i;
          this.updateActive(i);
          this.resetSlide();
          this.moveToZero(i);
        });
      });
    }

    updateActive(index = this.activeIndex) {
      // calculate how much to translateX the active gallery item
      this.thumbnails.forEach((thumb, i) => {
        thumb.classList.remove("active");
      });
      this.thumbnails[index].classList.add("active");
    }

    moveToZero(index = this.activeIndex) {
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

    moveToMiddle(index = this.activeIndex) {
      this.galleryInner.style.paddingLeft =
        (this.gallery.getBoundingClientRect().width -
          this.galleryItems[index].getBoundingClientRect().width) /
          2 +
        "px";
    }

    getFullSize() {
      this.galleryItems.forEach((item, i) => {
        const img = document.createElement("img");
        img.addEventListener("load", () => {
          this.galleryItems[i]
            .querySelector("img")
            .setAttribute("src", img.src);

          this.galleryItems[i]
            .querySelector(".ems-gallery-item")
            .classList.add("fade-in");
        });

        const sml = item.querySelector("img");
        img.src = sml.src.replace("thumbnails", "fullsize");
      });
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
