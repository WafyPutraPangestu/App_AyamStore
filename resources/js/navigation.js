{
    {
        --resources / js / navigation - effects.js--;
    }
}
// Tambahkan file ini dan import di layout utama jika ingin efek scroll yang lebih halus

document.addEventListener("DOMContentLoaded", () => {
    // Efek hover 3D untuk tombol dan link navigasi
    const navLinks = document.querySelectorAll(".nav-link-3d");

    navLinks.forEach((link) => {
        link.addEventListener("mousemove", (e) => {
            const rect = link.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const xPercent = (x / rect.width) * 100;
            const yPercent = (y / rect.height) * 100;

            // Batasi rotasi agar tidak terlalu ekstrim
            const rotateX = (yPercent - 50) / 10; // Max ±5 derajat
            const rotateY = (50 - xPercent) / 10; // Max ±5 derajat

            link.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05, 1.05, 1.05)`;
        });

        link.addEventListener("mouseleave", () => {
            link.style.transform =
                "perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)";
        });
    });

    // Tambahkan class untuk link 3D (tambahkan di HTML jika diperlukan)
    document.querySelectorAll("a.add-3d-effect").forEach((el) => {
        el.classList.add("nav-link-3d", "transition-transform", "duration-300");
    });
});
