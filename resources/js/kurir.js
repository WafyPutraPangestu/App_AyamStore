document.addEventListener("DOMContentLoaded", function () {
    // Add some interactivity to cards
    const cards = document.querySelectorAll(".order-card");

    cards.forEach((card) => {
        card.addEventListener("mousemove", function (e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left; // x position within the element
            const y = e.clientY - rect.top; // y position within the element

            // Calculate rotation based on mouse position
            // Maximum rotation is 5deg
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateY = ((x - centerX) / centerX) * 5; // -5 to +5 deg
            const rotateX = ((centerY - y) / centerY) * 5; // -5 to +5 deg

            // Apply transform
            card.style.transform = `translateZ(20px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });

        // Reset transform on mouse leave
        card.addEventListener("mouseleave", function () {
            card.style.transform = "translateZ(0) rotateX(0) rotateY(0)";
        });
    });

    // Make pagination interactive
    const pagination = document.querySelector(".pagination");
    if (pagination) {
        pagination.addEventListener("mousemove", function (e) {
            const rect = pagination.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const centerX = rect.width / 2;
            const rotateY = ((x - centerX) / centerX) * 3; // Smaller angle for pagination

            pagination.style.transform = `translateZ(5px) rotateY(${rotateY}deg)`;
        });

        pagination.addEventListener("mouseleave", function () {
            pagination.style.transform = "translateZ(0) rotateY(0)";
        });
    }
});
