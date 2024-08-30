/*~~~~~~~~~~~~~~~ TOGGLE BUTTON ~~~~~~~~~~~~~~~*/
const NavMenu = document.getElementById("nav-menu");
const NavLink = document.querySelectorAll(".nav-link");
const Hamburger = document.getElementById("hamburger");

Hamburger.addEventListener("click", () => {
    NavMenu.classList.toggle("left-[0]");
    Hamburger.classList.toggle("ri-close-large-line");
})

NavLink.forEach(Links => {
    Links.addEventListener("click", () => {
        NavMenu.classList.toggle("left-[0]");
        Hamburger.classList.toggle("ri-close-large-line");
    })
})

/*~~~~~~~~~~~~~~~ SHOW SCROLL UP ~~~~~~~~~~~~~~~*/


/*~~~~~~~~~~~~~~~ CHANGE BACKGROUND HEADER ~~~~~~~~~~~~~~~*/


/*~~~~~~~~~~~~~~~ SWIPER ~~~~~~~~~~~~~~~*/
const swiper = new Swiper('.swiper', {
    // Optional parameters
    speed: 500,
    spaceBetween: 40,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false
    },
  
    // If we need pagination
    pagination: {
        el: '.swiper-pagination',
        clickable: true
    },

    grabCursor: true,
    breakpoints: {
        640: { slidesPerView: 1 },
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 }
    }
});

/*~~~~~~~~~~~~~~~ SCROLL SECTIONS ACTIVE LINK ~~~~~~~~~~~~~~~*/


/*~~~~~~~~~~~~~~~ SCROLL REVEAL ANIMATION ~~~~~~~~~~~~~~~*/