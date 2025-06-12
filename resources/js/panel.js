document.addEventListener("alpine:init", () => {
    Alpine.data("kurirPanel", () => ({
        searchTerm: "",
        selectedStatus: "all",
        showPerformanceDetails: false,

        init() {
            // Auto refresh data setiap 30 detik
            setInterval(() => {
                // Bisa ditambahkan AJAX call untuk refresh data
                console.log("Auto refresh data...");
            }, 30000);
        },

        exportData() {
            // Fungsi untuk export data
            console.log("Exporting data...");
        },

        refreshData() {
            // Fungsi untuk refresh manual
            location.reload();
        },
    }));
});
