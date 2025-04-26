import "./bootstrap";
import "./product-search.js";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("form.delete-form").forEach((form) => {
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            const msg =
                form.dataset.message ||
                "Apakah Anda yakin ingin menghapus item ini?";
            Swal.fire({
                title: msg,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        const notification = document.getElementById("notification-success");
        if (notification) {
            // Tambahkan tombol close jika belum ada
            if (!notification.querySelector(".close-btn")) {
                const closeBtn = document.createElement("button");
                closeBtn.classList.add("close-btn");
                closeBtn.innerHTML = "&times;"; // Karakter 'x'
                closeBtn.onclick = function () {
                    notification.style.animation =
                        "fadeOut 0.5s ease-in forwards";
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                };
                notification.appendChild(closeBtn);
            }

            // Otomatis menghilang setelah 5 detik (atur sesuai kebutuhan)
            setTimeout(function () {
                notification.style.animation = "fadeOut 0.5s ease-in forwards";
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 5000); // 5000 ms = 5 detik
        }
    });

    /* Animasi menghilang */
});
