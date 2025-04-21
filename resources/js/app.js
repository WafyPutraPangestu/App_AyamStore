import "./bootstrap";
import "./product-search.js";

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
});
