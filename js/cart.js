
// For cart interactions, mini-cart display, theme & avatar modals, etc.

function loadMiniCart() {
    fetch('mini-cart.php')
      .then(res => res.text())
      .then(data => {
        const miniCart = document.getElementById('miniCartHover');
        if (miniCart) {
          miniCart.innerHTML = data;
        }
      })
      .catch(err => console.error("loadMiniCart Error:", err));
  }
  
  function updateCartCount() {
    fetch("mini-cart.php")
      .then(res => res.text())
      .then(html => {
        // update mini-cart visually too
        const miniCart = document.getElementById("miniCartHover");
        if (miniCart) miniCart.innerHTML = html;
  
        // Extract the count from the hidden span
        const temp = document.createElement("div");
        temp.innerHTML = html;
        const count = temp.querySelector("#cartCountData");
        if (count) {
          const badge = document.getElementById("cartCount");
          if (badge) badge.innerText = count.textContent.trim();
        }
      })
      .catch(err => console.error("updateCartCount Error:", err));
  }
  
  
  // THEME & AVATAR
  function openThemeModal() {
    document.getElementById("themeModal").style.display = "block";
    document.getElementById("modalBackdrop").style.display = "block";
  }
  function closeThemeModal() {
    document.getElementById("themeModal").style.display = "none";
    document.getElementById("modalBackdrop").style.display = "none";
  }
  function changeTheme(theme) {
    const fd = new FormData();
    fd.append('theme', theme);
    fetch('set_theme.php', { method: 'POST', body: fd })
      .then(r => r.text())
      .then(txt => {
        alert(txt);
        location.reload();
      })
      .catch(err => console.error("changeTheme Error:", err));
  }
  
  function openAvatarModal() {
    document.getElementById("avatarModal").style.display = "block";
    document.getElementById("modalBackdrop").style.display = "block";
  }
  function closeAvatarModal() {
    document.getElementById("avatarModal").style.display = "none";
    document.getElementById("modalBackdrop").style.display = "none";
  }
  function selectAvatar(avatar) {
    fetch('set_avatar_cookie.php?avatar=' + avatar)
      .then(res => res.text())
      .then(data => {
        const avDiv = document.getElementById("avatarDisplay");
        if (avDiv) {
          avDiv.innerHTML = data;
        }
        closeAvatarModal();
      })
      .catch(err => console.error("selectAvatar Error:", err));
  }
  

    //bookshow
// Adds a book to the cart 
function addToCart(bookId) {
  const formData = new FormData();
  formData.append("book_id", bookId);
  fetch("cartshow.php", { method: "POST", body: formData })
      .then(response => response.text())
      .then(data => { alert("Book added to cart!"); })
      .catch(error => { console.error("Error:", error); });
}

function toggleBankDetails() {
  const method = document.getElementById("modal_payment_method").value;
  document.getElementById("bank_section").style.display = method === "bank_transfer" ? "block" : "none";
  document.getElementById("card_section").style.display = method === "credit_card" ? "block" : "none";
}

function submitCheckoutModal() {
  const method = document.getElementById("modal_payment_method").value;

  // Validate credit card fields if selected
  if (method === "credit_card") {
    const card = document.getElementById("card_number").value;
    const expiry = document.getElementById("card_expiry").value;
    const cvc = document.getElementById("card_cvc").value;

    const validCard = /^\d{16}$/.test(card);
    const validExpiry = /^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry);
    const validCvc = /^\d{3}$/.test(cvc);

    if (!validCard || !validExpiry || !validCvc) {
      alert("Please enter valid credit card details.");
      return;
    }
  }

  const formData = new FormData(document.getElementById("checkoutModalForm"));
  fetch("checkoutshow.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(data => {
      document.getElementById("modalCheckoutResponse").innerHTML = data;
    })
    .catch(err => console.error("Checkout error:", err));
}




  // for onclick to work
window.addToCart = addToCart;


window.loadMiniCart = loadMiniCart;