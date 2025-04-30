

// Filter books
function updateBookList() {
    const formData = new FormData(document.getElementById('book_form'));
    fetch('bookshow.php', { method: 'POST', body: formData })
      .then(response => response.text())
      .then(data => {
        document.getElementById('book_response').innerHTML = data;
      })
      .catch(error => { console.error('Error:', error); });
  }
  
  // Add to cart
  function addToCart(bookId) {
    const formData = new FormData();
    formData.append('book_id', bookId);
    fetch('cartshow.php', { method: 'POST', body: formData })
      .then(response => response.text())
      .then(data => {
        updateCartCount();
        loadMiniCart();
        alert("Book added to cart!");
      })
      .catch(error => { console.error('Error:', error); });
  }
  
  // Mini cart + count
  window.onload = function () {
    loadMiniCart();
    updateCartCount();
  }
  
  // User modal
  function openUserFormModal() {
    document.getElementById("userFormModal").style.display = "block";
    document.getElementById("modalBackdrop").style.display = "block";
  }
  function closeUserFormModal() {
    document.getElementById("userFormModal").style.display = "none";
    document.getElementById("modalBackdrop").style.display = "none";
  }
  function submitCheckoutModal() {
    const method = document.getElementById("modal_payment_method").value;
  
    if (method === "credit_card") {
      const card = document.getElementById("card_number")?.value.trim();
      const expiry = document.getElementById("card_expiry")?.value.trim();
      const cvc = document.getElementById("card_cvc")?.value.trim();
  
      const validCard = /^\d{16}$/.test(card);
      const validExpiry = /^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry);
      const validCvc = /^\d{3}$/.test(cvc);
  
      if (!validCard || !validExpiry || !validCvc) {
        alert("Invalid card details. Please check your inputs.");
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
  
  
  // Checkout modal
  function openCheckoutModal() {
    document.getElementById("checkoutModal").style.display = "block";
    document.getElementById("modalBackdrop").style.display = "block";
  }
  function closeCheckoutModal() {
    document.getElementById("checkoutModal").style.display = "none";
    document.getElementById("modalBackdrop").style.display = "none";
  }
  function copyBillingModal() {
    const billing = document.getElementById("modal_billing_address").value;
    const shipping = document.getElementById("modal_shipping_address");
    if (document.getElementById("modal_same_as_billing").checked) {
      shipping.value = billing;
    } else {
      shipping.value = "";
    }
  }
  function submitCheckoutModal() {
    const form = document.getElementById("checkoutModalForm");
  
    const cardNumber = document.getElementById("card_number").value;
    const expiry = document.getElementById("card_expiry").value;
    const cvc = document.getElementById("card_cvc").value;
  
    const cardValid = /^\d{16}$/.test(cardNumber);
    const expiryValid = /^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry);
    const cvcValid = /^\d{3}$/.test(cvc);
  
    if (!cardValid || !expiryValid || !cvcValid) {
      alert("Invalid card details. Please check your inputs.");
      return;
    }
  
    const formData = new FormData(form);
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
  
  



// === CARTSHOW PAGE FUNCTIONS ===

// Open the theme selection modal
function openThemeModal() {
    document.getElementById("themeModal").style.display = "block";
    document.getElementById("modalBackdrop").style.display = "block";
}

// Close the theme selection modal
function closeThemeModal() {
    document.getElementById("themeModal").style.display = "none";
    document.getElementById("modalBackdrop").style.display = "none";
}

// Change the theme by sending the selection to set_theme.php
function changeTheme(theme) {
    const formData = new FormData();
    formData.append('theme', theme);

    fetch('set_theme.php', { method: 'POST', body: formData })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => { console.error('Error:', error); });
}

// Open the avatar selection modal
function openAvatarModal() {
    const avatarGrid = document.getElementById("avatarGrid");
    avatarGrid.innerHTML = ""; // Clear any previous avatars
  
    const avatarCount = 5; 
  
    for (let i = 1; i <= avatarCount; i++) {
      const img = document.createElement("img");
      img.src = `images/avatars/img${i}.jpg`;
      img.style.width = "60px";
      img.style.height = "60px";
      img.style.margin = "5px";
      img.style.cursor = "pointer";
      img.onclick = function () {
        selectAvatar(i);
      };
      avatarGrid.appendChild(img);
    }
  
    document.getElementById("avatarModal").style.display = "block";
    document.getElementById("modalBackdrop").style.display = "block";
  }
  
  
  

// Close the avatar selection modal
function closeAvatarModal() {
    document.getElementById("avatarModal").style.display = "none";
    document.getElementById("modalBackdrop").style.display = "none";
}

// Select an avatar and update it by contacting set_avatar_cookie.php
function selectAvatar(avatar) {
    fetch("set_avatar_cookie.php?avatar=" + image.image_path)

        .then(response => response.text())
        .then(data => {
            document.getElementById("avatarDisplay").innerHTML = data;
            closeAvatarModal();
        })
        .catch(error => { console.error('Error:', error); });
}


// === CHECKOUT PAGE FUNCTIONS ===

// Copy the Billing Address into the Shipping Address
function copyBillingAddress() {
    const billing = document.querySelector('input[name="billing_address"]');
    const shipping = document.querySelector('input[name="shipping_address"]');
    if (document.getElementById("same_as_billing").checked) {
        shipping.value = billing.value;
    } else {
        shipping.value = "";
    }
}


//toggle bank details
function toggleBankDetailsModal() {
    const method = document.getElementById("modal_payment_method").value;
    const cardSection = document.getElementById("modal_card_section");
    const bankSection = document.getElementById("modal_bank_section");
  
    if (cardSection && bankSection) {
      cardSection.style.display = method === "credit_card" ? "block" : "none";
      bankSection.style.display = method === "bank_transfer" ? "block" : "none";
    }
  }
  
  
  





function loadAvatars() {
    const grid = document.getElementById("avatarGrid");
    grid.innerHTML = "<p>Loading...</p>";
  
    fetch("get_avatars.php")
      .then(res => res.json())
      .then(data => {
        grid.innerHTML = ""; // Clear loading
        data.forEach(image => {
          const img = document.createElement("img");
          img.src = "images/avatars/" + image.image_path;
          img.alt = image.image_path;
          img.style = "width:60px; height:60px; margin:5px; cursor:pointer;";
          img.onclick = () => selectAvatar(image.id);
          grid.appendChild(img);
        });
      })
      .catch(err => {
        console.error("Failed to load avatars:", err);
        grid.innerHTML = "<p>Error loading avatars</p>";
      });
  }

  function toggleBankDetailsMain() {
    const method = document.getElementById("payment_method").value;
    const cardSection = document.getElementById("card_section");
    const bankSection = document.getElementById("bank_section");
  
    if (cardSection && bankSection) {
      cardSection.style.display = method === "credit_card" ? "block" : "none";
      bankSection.style.display = method === "bank_transfer" ? "block" : "none";
    }
  }
  
  
    // Clear invalid input if switching methods
{
    document.getElementById("card_number").value = "";
    document.getElementById("card_expiry").value = "";
    document.getElementById("card_cvc").value = "";
  }
  
  function copyBillingAddress() {
    const bill = document.querySelector("input[name='billing_address']");
    const ship = document.querySelector("input[name='shipping_address']");
    if (document.getElementById("same_as_billing").checked) {
      ship.value = bill.value;
    }
  }
  

  function submitUserFormModal() {
    const form = document.getElementById("userFormModal");
    if (!form) {
      alert("Form not found.");
      return false;
    }
  
    const username = form.querySelector('[name="username"]')?.value.trim();
    const email = form.querySelector('[name="email"]')?.value.trim();
    const password = form.querySelector('[name="password"]')?.value.trim();
    const phone = form.querySelector('[name="phone"]')?.value.trim();
    const address = form.querySelector('[name="address"]')?.value.trim();
  
    if (!username || !email || !password || !phone || !address) {
      alert("All fields are required.");
      return false;
    }
  

  
    // If all good, submit form to backend or process here
    form.submit(); 
    return false; // prevent double-submit
  }
  

 // Expose globally for onclick to work
window.addToCart = addToCart;


window.loadMiniCart = loadMiniCart;