const membershipPackages = {
    "Platinum": [
      "Gents (Annual) - Rs 65000",
      "Ladies (Annual) - Rs 55000",
      "Couple (Annual) - Rs 85000"
    ],
    "Gold": [
      "Gents (Annual) - Rs 55000",
      "Ladies (Annual) - Rs 45000"
    ],
    "Silver": [
      "Individual (6-months) - Rs 45000",
      "Individual (3-months) - Rs 35000"
    ]
  };

  document.querySelectorAll('.book-now-btn').forEach(button => {
    button.addEventListener('click', function () {
      const membership = this.getAttribute('data-membership');
      document.getElementById('membershipType').value = membership;

      const packageSelect = document.getElementById('membershipPackage');
      packageSelect.innerHTML = '<option value="">Select a package</option>';

      if (membershipPackages[membership]) {
        membershipPackages[membership].forEach(option => {
          const opt = document.createElement('option');
          opt.value = option;
          opt.textContent = option;
          packageSelect.appendChild(opt);
        });
      }

      const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
      bookingModal.show();
    });
  });

  const creditCardInfo = document.getElementById('creditCardInfo');
  document.querySelectorAll('input[name="paymentMethod"]').forEach(input => {
    input.addEventListener('change', function () {
      creditCardInfo.style.display = this.value === 'Credit Card' ? 'block' : 'none';
    });
  });

  window.addEventListener('DOMContentLoaded', () => {
    creditCardInfo.style.display =
      document.querySelector('input[name="paymentMethod"]:checked').value === 'Credit Card'
        ? 'block' : 'none';
  });

  document.getElementById('bookingForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const selectedPackage = document.getElementById('membershipPackage').value;
    if (!selectedPackage) {
      alert("Please select a package option.");
      return;
    }

    document.getElementById('selectedPrice').value = selectedPackage;

    alert("Booking submitted successfully!\nPackage: " + selectedPackage);
    this.reset();

    const modalEl = bootstrap.Modal.getInstance(document.getElementById('bookingModal'));
    modalEl.hide();
  });
