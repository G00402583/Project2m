
// For any extra dynamic form logic (like expiry year population).

document.addEventListener("DOMContentLoaded", () => {
    // Example: populate expiry years if you have a <select id="expiry_year">
    const ySel = document.getElementById("expiry_year");
    if (ySel) {
      const thisYear = new Date().getFullYear();
      for (let i = 0; i < 10; i++) {
        const opt = document.createElement("option");
        opt.value = thisYear + i;
        opt.textContent = thisYear + i;
        ySel.appendChild(opt);
      }
    }
  });
  