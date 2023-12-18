class emsFlyouts {
  constructor(config = {}) {
    this.heights = [];
    this.triggers = [];
    this.targetIds = [];
    this.headerSelector = "header";
    this.flyoutsSelector = ".ems-flyout";
    this.currentClass = ".ems-current";
    this.triggerSelector = ".ems-trigger";
    this.closerSelector = ".ems-closer";

    // Overwrite default props with any passed in from config.
    for (const prop in config) {
      console.log(prop);
      this[prop] = config[prop];
    }

    this.init();
  }

  init() {
    this.header = document.querySelector(this.headerSelector);
    this.headerHeight = this.header.getBoundingClientRect().height;
    this.flyouts = document.querySelectorAll(this.flyoutsSelector);
    this.setFlyoutHeights();
    this.positionFlyouts();
    this.setTriggers();
    this.setTargetIds();
    this.eventsHandler();
  }

  setFlyoutHeights() {
    this.flyouts.forEach((item, i) => {
      this.heights.push(item.getBoundingClientRect().height);
    });
  }

  positionFlyouts() {
    this.flyouts.forEach((item, i) => {
      item.style.position = "absolute";
      item.style.left = 0;
      item.style.top = "-" + this.heights[i] + "px";
      item.style.transition = ".3s ease-in-out";
      item.style.opacity = 1;
    });
  }

  setTriggers() {
    this.triggers = document.querySelectorAll(this.triggerSelector);
  }

  /**
   * Creates an array of target Ids by first looking for a data-hash
   * property and if that doesn't exist, looking for the hash in
   * the link url
   */
  setTargetIds() {
    this.triggers.forEach((trigger, i) => {
      if (trigger.dataset.target) {
        this.targetIds.push(trigger.dataset.target);
      } else if (trigger.hash) {
        this.targetIds.push(trigger.hash);
      }
    });
  }

  /**
   * Adds click event handlers for triggers.
   */
  eventsHandler() {
    // Flyout openers
    this.triggers.forEach((trigger, i) => {
      trigger.addEventListener("click", (e) => {
        e.preventDefault();
        this.resetActiveTrigger(i);
      });
    });
    //Flyout closers
    document.querySelectorAll(this.closerSelector).forEach((closer) => {
      closer.addEventListener("click", (e) => {
        e.preventDefault();
        this.resetActiveTrigger();
      });
    });
  }

  /**
   * Hides a flyout above the header outside of the browser window.
   * @param integer index
   */
  hide(index) {
    const flyout = document.querySelector(this.targetIds[index]);
    flyout.style.top = "-" + this.heights[index] + "px";
  }

  /**
   * Repositions a flyout below the header.
   * @param integer index
   */
  show(index) {
    const flyout = document.querySelector(this.targetIds[index]);
    flyout.style.top = "" + this.headerHeight + "px";
  }

  /**
   * Reveals a trigger target if hidden. Hides trigger target if shown.
   * @param integer index
   */
  toggleView(index) {
    if (this.triggers[index].classList.contains(this.currentClass)) {
      this.show(index);
    } else {
      this.hide(index);
    }
  }

  /**
   * Update the current class of triggers. Toggles view of trigger target.
   * @param  mixed integer/null index
   */
  resetActiveTrigger(index = null) {
    this.triggers.forEach((trigger, i) => {
      this.hide(i);
      if (index === i && !trigger.classList.contains(this.currentClass)) {
        this.triggers[i].classList.add(this.currentClass);
      } else {
        this.triggers[i].classList.remove(this.currentClass);
      }
    });
    if (index !== null) {
      this.toggleView(index);
    }
  }
} // End class
