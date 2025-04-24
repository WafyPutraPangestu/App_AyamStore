document.addEventListener("DOMContentLoaded", function () {
    const selectAll = document.getElementById("select-all");
    const itemCheckboxes = document.querySelectorAll(".item-checkbox");
    const quantityInputs = document.querySelectorAll(".quantity-input");
    const debounceTimeouts = {};

    function updateSubtotalAndTotal() {
        quantityInputs.forEach((input) => {
            const id = input.dataset.id;
            const harga = parseInt(input.dataset.harga);
            const quantity = parseInt(input.value) || 0;
            const subtotal = harga * quantity;
            const subtotalEl = document.getElementById(`subtotal-${id}`);
            subtotalEl.textContent = `Subtotal: Rp ${subtotal.toLocaleString(
                "id-ID"
            )}`;
        });

        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        itemCheckboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                const row = checkbox.closest(".flex");
                const input = row.querySelector(".quantity-input");
                const harga = parseInt(input.dataset.harga);
                const quantity = parseInt(input.value) || 0;
                total += harga * quantity;
            }
        });

        document.getElementById(
            "total-keseluruhan"
        ).textContent = `Rp ${total.toLocaleString("id-ID")}`;
    }

    selectAll.addEventListener("change", function () {
        itemCheckboxes.forEach((checkbox) => {
            checkbox.checked = selectAll.checked;
        });
        updateTotal();
    });

    itemCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            if (!this.checked) {
                selectAll.checked = false;
            } else if ([...itemCheckboxes].every((cb) => cb.checked)) {
                selectAll.checked = true;
            }
            updateTotal();
        });
    });

    quantityInputs.forEach((input) => {
        input.addEventListener("input", function () {
            updateSubtotalAndTotal();

            const id = this.dataset.id;
            const value = this.value;

            if (debounceTimeouts[id]) clearTimeout(debounceTimeouts[id]);

            debounceTimeouts[id] = setTimeout(() => {
                fetch(`/keranjang/update-quantity/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({ quantity: value }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                `Quantity item ${id} berhasil diupdate.`
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Gagal update quantity:", error)
                    );
            }, 500);
        });
    });

    updateSubtotalAndTotal(); // initial

    const checkboxes = document.querySelectorAll(".item-checkbox");
    const bulkForm = document.getElementById("bulk-delete-form");
    const selectedIdsInput = document.getElementById("selected-ids");

    function updateBulkDeleteVisibility() {
        const selected = [...checkboxes]
            .filter((c) => c.checked)
            .map((c) => c.value);
        if (selected.length > 0) {
            bulkForm.classList.remove("hidden");
            selectedIdsInput.value = selected.join(",");
        } else {
            bulkForm.classList.add("hidden");
            selectedIdsInput.value = "";
        }
    }

    checkboxes.forEach((c) => {
        c.addEventListener("change", updateBulkDeleteVisibility);
    });

    if (selectAll) {
        selectAll.addEventListener("change", function () {
            checkboxes.forEach((c) => (c.checked = selectAll.checked));
            updateBulkDeleteVisibility();
        });
    }
    document
        .getElementById("checkout-form")
        .addEventListener("submit", function (e) {
            e.preventDefault();

            const checked = document.querySelectorAll(".item-checkbox:checked");
            const totalText =
                document.getElementById("total-keseluruhan").textContent;
            const totalWithoutRupiah = totalText.replace(/[^0-9]/g, "");
            const total = parseInt(totalWithoutRupiah);

            if (checked.length === 0) {
                alert("Pilih minimal satu item untuk checkout.");
                return;
            }

            const selectedItems = [...checked].map((c) => {
                const row = c.closest(".flex");
                const input = row.querySelector(".quantity-input");
                const quantity = parseInt(input.value) || 1;
                return {
                    produk_id: c.value,
                    quantity: quantity,
                };
            });

            console.log("Selected items for checkout:", selectedItems);

            fetch("/checkout", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    selected_items: selectedItems,
                    total: total,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        window.location.href = "/user/order-form";
                    }
                })
                .catch((error) => {
                    console.error("Error saat kirim data:", error);
                });
        });
});
