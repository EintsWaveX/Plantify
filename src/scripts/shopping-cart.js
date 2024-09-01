// Ambil semua input fields dalam form
document
.querySelector(".login-form")
.addEventListener("input", function (event) {
  var nameOnCard = document.getElementById("nameOnCard");
  var cardNumber = document.getElementById("cardNumber");
  var validAccount = document.querySelector(".forgot-password");

  // Validasi Name on Card (tidak kosong)
  if (nameOnCard.value !== "") {
    nameOnCard.classList.add("valid");
  } else {
    nameOnCard.classList.remove("valid");
  }

  // Validasi Card Number (16 angka dengan spasi setiap 4 angka)
  var cardNumberPattern = /^\d{4} \d{4} \d{4} \d{4}$/;
  if (cardNumberPattern.test(cardNumber.value)) {
    cardNumber.classList.add("valid");
  } else {
    cardNumber.classList.remove("valid");
  }

  // Tampilkan "Valid Account!" jika semua input valid
  if (
    nameOnCard.classList.contains("valid") &&
    cardNumber.classList.contains("valid")
  ) {
    validAccount.style.display = "inline-block";
  } else {
    validAccount.style.display = "none";
  }
});

// Format input card number
document
.getElementById("cardNumber")
.addEventListener("input", function (event) {
  var input = event.target;
  var value = input.value.replace(/\D/g, ""); // Hanya angka
  var formattedValue = value.match(/.{1,4}/g)?.join(" ") || ""; // Tambah spasi setiap 4 angka
  input.value = formattedValue;
});


// JavaScript for handling the quantity change and updating prices

// Price data for each product (this could be dynamically fetched or hardcoded as per requirement)
document.addEventListener("DOMContentLoaded", function () {
    const maxQuantity = 5; // Maximum quantity allowed per product
    const shippingCost = 35.99;
    const taxCost = 4.99;
    const insuranceCost = 0.99;

    // Function to update the total amount
    function updateTotalAmount() {
        let totalAmount = shippingCost + taxCost + insuranceCost;

        document.querySelectorAll(".price").forEach(priceElement => {
            const price = parseFloat(priceElement.textContent.replace('$', ''));
            totalAmount += price;
        });

        document.getElementById("total-amount").textContent = `$${totalAmount.toFixed(2)}`;
    }

    // Function to handle quantity change
    function updateQuantity(button, isIncrease) {
        const productId = button.getAttribute("data-product-id");
        const quantityElement = document.querySelector(`.qty[data-qty-id="${productId}"]`);
        const priceElement = document.querySelector(`.price[data-price-id="${productId}"]`);

        let quantity = parseInt(quantityElement.textContent);
        let price = parseFloat(priceElement.textContent.replace('$', ''));

        const unitPrice = price / quantity;

        if (isIncrease && quantity < maxQuantity) {
            quantity++;
        } else if (!isIncrease && quantity > 1) {
            quantity--;
        }

        // Update quantity and price
        quantityElement.textContent = quantity;
        priceElement.textContent = `$${(unitPrice * quantity).toFixed(2)}`;

        // Update total amount
        updateTotalAmount();
    }

    // Attach event listeners to buttons
    document.querySelectorAll(".qty-btn").forEach(button => {
        button.addEventListener("click", function () {
            const isIncrease = button.classList.contains("increase");
            updateQuantity(button, isIncrease);
        });
    });

    // Initial total amount calculation
    updateTotalAmount();
});

// Menampilkan modal notifikasi ketika tombol checkout ditekan
document.querySelector('.checkout-button').addEventListener('click', function() {
  document.getElementById('notification-modal').style.display = 'block';
});

// Menutup modal notifikasi ketika tombol close ditekan
document.getElementById('close-button').addEventListener('click', function() {
  document.getElementById('notification-modal').style.display = 'none';
});

// Menutup modal notifikasi ketika tombol "No" ditekan
document.querySelector('.no-button').addEventListener('click', function() {
  document.getElementById('notification-modal').style.display = 'none';
});

// Menampilkan modal feedback ketika tombol "Yes" ditekan
document.querySelector('.yes-button').addEventListener('click', function() {
  document.getElementById('notification-modal').style.display = 'none';
  document.getElementById('feedback-modal').style.display = 'block';
});

// Menutup modal feedback ketika tombol close ditekan
document.getElementById('close-feedback-button').addEventListener('click', function() {
  document.getElementById('feedback-modal').style.display = 'none';
});

// Mengelola perubahan rating bintang
const stars = document.querySelectorAll('.rating label');
stars.forEach(star => {
  star.addEventListener('click', function() {
      // Reset semua bintang
      stars.forEach(s => s.style.color = '#ccc');
      // Set bintang yang dipilih dan yang lebih besar
      this.style.color = '#ffcc00';
      let previousStar = this.previousElementSibling;
      while (previousStar) {
          if (previousStar.tagName === 'LABEL') {
              previousStar.style.color = '#ffcc00';
          }
          previousStar = previousStar.previousElementSibling;
      }
  });
});

// Mengelola pengiriman form feedback
document.getElementById('feedback-form').addEventListener('submit', function(event) {
  event.preventDefault();

  // Mengambil nilai rating yang dipilih
  const rating = document.querySelector('input[name="rating"]:checked').value;
  console.log('Rating:', rating);  // Hanya untuk debug, bisa dihapus atau diganti

  alert('Thank you for your feedback! Rating: ' + rating);
  document.getElementById('feedback-modal').style.display = 'none';
});



