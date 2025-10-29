// assets/js/script.js

// Simple alert saat tombol bayar ditekan
document.addEventListener("DOMContentLoaded", function () {
  const forms = document.querySelectorAll("form");
  forms.forEach(f => {
    f.addEventListener("submit", function () {
      if (f.querySelector("button[name='bayar']")) {
        alert("Pembayaran sedang diproses...");
      }
    });
  });
});
