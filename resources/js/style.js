document.addEventListener("DOMContentLoaded", function () {
    const statusDropdowns = document.querySelectorAll(".status-dropdown");

    // Initialize status indicators
    statusDropdowns.forEach((dropdown) => {
        updateStatusIndicators(
            dropdown.getAttribute("data-order-id"),
            dropdown.value
        );

        // Start animation if status is "sedang_diantar"
        if (dropdown.value === "sedang_diantar") {
            const orderId = dropdown.getAttribute("data-order-id");
            document
                .getElementById(`truck-${orderId}`)
                .classList.add("animate-truck");
        } else if (dropdown.value === "terkirim") {
            const orderId = dropdown.getAttribute("data-order-id");
            document
                .getElementById(`package-${orderId}`)
                .classList.add("animate-package");
        }

        dropdown.addEventListener("change", function () {
            const orderId = dropdown.getAttribute("data-order-id");
            const newStatus = dropdown.value;

            // Show loading spinner
            document.getElementById(`spinner-${orderId}`).style.display =
                "block";

            // Update status in backend
            updateStatus(orderId, newStatus);
        });
    });

    function updateStatus(orderId, newStatus) {
        fetch(`/kurir/manajemen/${orderId}`, {
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({ status_pengiriman: newStatus }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                // Hide spinner
                document.getElementById(`spinner-${orderId}`).style.display =
                    "none";

                if (data.success) {
                    showToast("Status berhasil diupdate!", "success");
                    updateStatusIndicators(orderId, newStatus);

                    // Handle animations based on new status
                    const truck = document.getElementById(`truck-${orderId}`);
                    const package = document.getElementById(
                        `package-${orderId}`
                    );

                    // Reset animations first
                    truck.classList.remove("animate-truck");
                    package.classList.remove("animate-package");

                    // Apply new animations based on status
                    if (newStatus === "sedang_diantar") {
                        // Force reflow to restart animation
                        void truck.offsetWidth;
                        truck.classList.add("animate-truck");
                    } else if (newStatus === "terkirim") {
                        void package.offsetWidth;
                        package.classList.add("animate-package");
                    }
                } else {
                    showToast(data.message || "Gagal update status!", "error");
                }
            })
            .catch((error) => {
                document.getElementById(`spinner-${orderId}`).style.display =
                    "none";
                console.error("Error:", error);
                showToast("Terjadi kesalahan saat update status.", "error");
            });
    }

    function updateStatusIndicators(orderId, status) {
        const steps = document.querySelectorAll(
            `#order-${orderId} .status-step`
        );
        const lines = document.querySelectorAll(
            `#order-${orderId} .status-line`
        );

        // Reset all indicators
        steps.forEach((step) => step.classList.remove("active", "error"));
        lines.forEach((line) => line.classList.remove("active"));

        // Set active steps based on current status
        const statusOrder = [
            "mencari_kurir",
            "menunggu_pickup",
            "sedang_diantar",
            "terkirim",
        ];

        if (status === "gagal_kirim") {
            // Show error state
            steps[statusOrder.indexOf("sedang_diantar")].classList.add("error");
        } else {
            const currentIndex = statusOrder.indexOf(status);

            for (let i = 0; i <= currentIndex; i++) {
                const step = [...steps].find(
                    (s) => s.dataset.status === statusOrder[i]
                );
                if (step) step.classList.add("active");

                // Activate line before this step if not the first step
                if (i > 0) {
                    lines[i - 1].classList.add("active");
                }
            }
        }
    }

    function showToast(message, type) {
        const toast = document.createElement("div");
        toast.className = `toast toast-${type}`;

        // Add icon based on type
        const icon = type === "success" ? "✅" : "❌";
        toast.innerHTML = `<span>${icon}</span> ${message}`;

        document.body.appendChild(toast);

        // Remove after animation completes
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
