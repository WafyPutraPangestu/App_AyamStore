// resources/js/product-search.js
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search");
    if (!searchInput) return;

    const rows = document.querySelectorAll("tbody tr");

    searchInput.addEventListener("keyup", function () {
        const searchText = this.value.toLowerCase();

        rows.forEach((row) => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchText) ? "" : "none";
        });
    });
});
