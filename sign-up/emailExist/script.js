document.addEventListener('DOMContentLoaded', function() {
    swal({
        title: "Email sudah terdaftar!",
        text: "Klik di mana saja untuk melanjutkan",
        icon: "error",
        buttons: false,
        closeOnClickOutside: false
    });

    // Mengarahkan pengguna ke halaman lain saat notifikasi Swal di klik
    document.querySelector('.swal-modal').addEventListener('click', function() {
      window.location.href = "../index.html"; // Ganti dengan URL halaman yang ingin Anda tuju
    });
});
