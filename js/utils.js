// Utility functions for modal handling and user creation 
function createUser(event) {
  event.preventDefault();

  const form = document.getElementById("book_userForm");
  const formData = new FormData(form);

  fetch("createUser.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) throw new Error("Network error.");
      return response.text();
    })
    .then((data) => {
      const createResp = document.getElementById("login_response2");
      if (createResp) createResp.innerHTML = data;
      else alert(data);
    })
    .catch((error) => {
      console.error("createUser Error:", error);
    });
}

function showModal(modalContent) {
  const modal = document.getElementById("login_modal");
  const backdrop = document.getElementById("backdrop");
  if (modal && backdrop) {
    modal.innerHTML = modalContent;
    modal.style.display = "block";
    backdrop.style.display = "block";
    backdrop.addEventListener("click", handleBackdropClick);
  }
}

function handleBackdropClick(event) {
  const modal = document.getElementById("login_modal");
  if (event.target.id === "backdrop") {
    closeModal();
  }
}

function showLoginModal() {
  const modalHTML = `
    <div class="styled-modal">
      <h2>Login</h2>
      <form id="login" action="login-handler.php" method="POST">
        <input type="hidden" name="redirect_to" value="bookinventory.php">

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Login" class="primary-btn">
      </form>
      <div id="login_response" style="color:red; margin-top:10px;"></div>
      <button class="secondary-btn" onclick="showCreateUserModal()">Create User</button>
    </div>
  `;
  showModal(modalHTML);
}

function showCreateUserModal() {
  const modalHTML = `
    <div class="styled-modal">
      <h2>Create Account</h2>
      <form id="book_userForm" onsubmit="createUser(event)">
        <label for="create_username">Username:</label>
        <input type="text" id="create_username" name="username" required>

        <label for="create_email">Email:</label>
        <input type="email" id="create_email" name="email" required>

        <label for="create_password">Password:</label>
        <input type="password" id="create_password" name="password" required>

        <input type="submit" value="Create User" class="primary-btn">
      </form>
      <div id="login_response2" style="color:red; margin-top:10px;"></div>
      <button class="secondary-btn" onclick="showLoginModal()">Back to Login</button>
    </div>
  `;
  showModal(modalHTML);
}

function closeModal() {
  const modal = document.getElementById("login_modal");
  const backdrop = document.getElementById("backdrop");
  if (modal && backdrop) {
    modal.style.display = "none";
    backdrop.style.display = "none";
    backdrop.removeEventListener("click", handleBackdropClick);
  }
}
