class HeroWithImage {
  constructor() {
    this.init = this.init.bind(this);
    this.navBar = document.querySelector('[data-block="main-navigation"]');
    this.resizeHero = this.resizeHero.bind(this);

    this.init();
  }

  resizeHero() {
    const hero = document.querySelector('[data-block="hero-with-image"]');
    if (!hero || !this.navBar) return;

    const navHeight = this.navBar.offsetHeight;

    if (
      window.innerWidth > 1024 ||
      window.innerHeight >
        document.querySelector('[data-block="hero-with-image"] .js-intro-text')
          .offsetHeight
    ) {
      hero.style.height = `${window.innerHeight - navHeight - 20}px`;
    } else {
      hero.removeAttribute("style");
    }
  }

  init() {
    document.addEventListener("DOMContentLoaded", () => {
      this.resizeHero();

      window.addEventListener("resize", this.resizeHero);
    });
  }
}

if (document.querySelector('[data-block="hero-with-image"]'))
  new HeroWithImage();
