import Swiper from 'swiper/bundle';

import 'swiper/css/bundle';


console.log(document.querySelector('.servicesCards'))


const swiper = new Swiper(".servicesCards", {
    pagination: {
      el: ".swiper-pagination",
      clickable: true
    },
  });