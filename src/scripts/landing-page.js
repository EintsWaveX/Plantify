const images = [
    './../assets/landing/tanaman_e.jpeg',
    './../assets/landing/istockphoto-1380361370-612x612-removebg-preview (1).png',
    './../assets/landing/image-removebg-preview.png',
    './../assets/landing/tanaman_f.jpg'
];

let currentIndex = 0;
const imageElement = document.getElementById('animeImage');

function changeImage() {
    currentIndex = (currentIndex + 1) % images.length;
    imageElement.src = images[currentIndex];
   
}

setInterval(changeImage, 2000); // Ganti gambar setiap 2 detik

function toggleMobileNav() {
    var mobileNav = document.getElementById('mobileNav');
    if (mobileNav.style.display === "flex") {
        mobileNav.style.display = "none";
    } else {
        mobileNav.style.display = "flex";
    }
}

