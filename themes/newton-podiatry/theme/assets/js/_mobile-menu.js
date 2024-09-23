export default class MobileMenu {
  constructor() {
    this.menuButton = document.querySelector(".js-mobile-menu-toggle");
    this.menu = document.querySelector(".js-mobile-nav");

    this.bindClicks = this.bindClicks.bind(this);

    this.bindClicks();
  }

  closeAllSubMenus() {
    this.menu.querySelectorAll(".js-dropdown-go-back").forEach((item) => {
      const parent = item.parentElement.parentElement;
      parent.classList.add("hidden");
      parent.classList.remove("flex");

      parent.parentElement
        .querySelector(":scope > svg")
        .classList.remove("rotate-180");
    });
  }

  toggleMenu() {
    const icon = this.menuButton.querySelector(":scope > span:not(.hidden)");

    if (icon.classList.contains("js-mobile-menu-open")) {
      //Open menu
      icon.classList.add("hidden");
      icon.nextElementSibling.classList.remove("hidden");
      this.menu.classList.remove("hidden");
      this.menu.classList.add("flex");
    } else {
      //Close menu
      this.closeAllSubMenus.bind(this)();
      icon.classList.add("hidden");
      icon.previousElementSibling.classList.remove("hidden");
      this.menu.classList.add("hidden");
      this.menu.classList.remove("flex");
    }
  }
  bindClicks() {
    this.menuButton.addEventListener("click", this.toggleMenu.bind(this));

    this.menu.querySelectorAll(".js-has-dropdown").forEach((item) => {
      item.querySelector(":scope > svg").addEventListener("click", (e) => {
        e.preventDefault();
        item.querySelector(":scope > nav").classList.toggle("hidden");
        item.querySelector(":scope > nav").classList.toggle("flex");

        const svg = item.querySelector(":scope > svg");
        if (svg.classList.contains("rotate-180")) {
          svg.classList.remove("rotate-180");
        } else {
          svg.classList.add("rotate-180");
        }
        // item.querySelector(':scope > svg').

        if (window.innerWidth >= 768) {
          const header = item.closest("header");
          if (!header) return;
          item.querySelector(":scope > nav").style.top = `${
            header.offsetTop + header.offsetHeight + 35
          }px`;
        } else {
          item.querySelector(":scope > nav").removeAttribute("style");
        }
      });
    });

    this.menu.querySelectorAll(".js-dropdown-go-back").forEach((item) => {
      item.addEventListener("click", (e) => {
        e.preventDefault();
        item.parentElement.parentElement.classList.add("hidden");
        item.parentElement.parentElement.classList.remove("flex");
      });
    });

    document.querySelector("body").addEventListener("click", (e) => {
      if (
        !e.target.closest(".js-mobile-nav") &&
        !e.target.closest(".js-mobile-menu-toggle")
      ) {
        this.closeAllSubMenus();
      }
    });
  }
}

new MobileMenu();
