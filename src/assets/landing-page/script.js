const images = [
    './img/tanaman_e.jpeg',
    './img/istockphoto-1380361370-612x612-removebg-preview (1).png',
    './img/image-removebg-preview.png',
    './img/tanaman_f.jpg'
];

let currentIndex = 0;
const imageElement = document.getElementById('animeImage');

function changeImage() {
    currentIndex = (currentIndex + 1) % images.length;
    imageElement.src = images[currentIndex];
   
}

setInterval(changeImage, 2000); // Ganti gambar setiap 2 detik
