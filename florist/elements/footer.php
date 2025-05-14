<footer class="text-center text-lg-start footer">

  <div class="container text-center text-md-start mt-5">
    <div class="row mt-3">

      <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
        <h6 class="footer-title footer-web-name">BloomExpress</h6>
        <hr class="footer-hr"/>

        <p class="footer-txt">
        Discover the perfect gift at BloomExpress, where our exquisite bouquets are crafted to bring joy and beauty to any occasion. 
        </p>
      </div>

      <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">

          <h6 class="footer-title">Useful links</h6>

          <hr class="footer-hr"/>

          <p>
            <a class="footer-txt" href="account.php">Your Account</a>
          </p>

          <p>
            <a class="footer-txt" href="index.php">Home</a>
          </p>
        </div>


        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
          <h6 class="footer-title">Contact</h6>
                  
          <hr class="footer-hr"/>

          <p class="footer-txt">Pravets, Bulgaria</p>
          <p class="footer-txt">example@gmail.com</p>
          <p class="footer-txt">+359 00 000 0000</p>
        </div>

    </div>
  </div>


  <!-- Copyright Section -->
  <div class="text-center p-3 footer-txt" style="background-color: hsla(0, 0.00%, 0.00%, 0.20)">
      © 2025 Copyright:
    <a class="footer-bx " href="#">BloomExpress</a>
  </div>
    
</footer>



<!-- JS -->

<!-- quantity selector (shop) -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll(".cart-selector").forEach(card => {

          const decreaseBtn = card.querySelector(".decrease");
          const increaseBtn = card.querySelector(".increase");
          const quantityDisplay = card.querySelector(".quantity-cart");
          const quantityField = card.querySelector(".quantity-field");
          
          decreaseBtn.addEventListener("click", function() {
              let value = parseInt(quantityDisplay.textContent) || 0;
              if (value > 1) {
                  quantityDisplay.textContent = value - 1;
                  quantityField.value = value - 1;
              }
          });

          increaseBtn.addEventListener("click", function() {

              let value = parseInt(quantityDisplay.textContent) || 0;
              quantityDisplay.textContent = value + 1;
              quantityField.value = value + 1;
          });
      });
  });
</script>


<!-- choose payment method -->
<script>
  function togglePayment() {
      let payOnline = document.getElementById("payOnline").checked;
      let fetchShop = document.getElementById("fetchShop").checked;

      let cardSelect = document.getElementById("cardSelect");
      let shopSelect = document.getElementById("shopSelect");

      if (payOnline) {
          cardSelect.style.display = "block";
          shopSelect.style.display = "none";
      } else if (fetchShop) {
          cardSelect.style.display = "none";
          shopSelect.style.display = "block";
      } else {
          cardSelect.style.display = "none";
          shopSelect.style.display = "none";
      }
  }
</script>


<!-- orders nav -->
<script>
  function showOrders(type) {
    document.getElementById('ongoing-orders-container').style.display = (type === 'ongoing') ? 'block' : 'none';
    document.getElementById('past-orders-container').style.display = (type === 'past') ? 'block' : 'none';
    document.getElementById('ongoing-btn').classList.toggle('active-tab', type === 'ongoing');
    document.getElementById('past-btn').classList.toggle('active-tab', type === 'past');
  }
</script>


<!-- shop nav -->
<script>
  function showBqSection(section) {
    const sections = ['wedding', 'bday', 'sayily', 'congrats', 'justbcz'];
    sections.forEach(sec => {
      document.getElementById(sec + '-cont').style.display = (sec === section) ? 'block' : 'none';
      document.getElementById(sec + '-btn').classList.remove('active-section');
    });
      document.getElementById(section + '-cont').style.display = 'block';
      document.getElementById(section + '-btn').classList.add('active-section');
  }

        // Initialize with wedding section visible
        showBqSection('wedding');
    </script>


<!-- log in -->
<script>
        function openPopup() {
            document.getElementById("popup").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }
</script>


<!-- sign up -->
<script>
        function openSPopup() {
            document.getElementById("Spopup").style.display = "block";
            document.getElementById("Soverlay").style.display = "block";
        }

        function closeSPopup() {
            document.getElementById("Spopup").style.display = "none";
            document.getElementById("Soverlay").style.display = "none";
        }
</script>


<!-- Account edit info -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-link").forEach(button => {
        button.addEventListener("click", function () {
            const row = this.closest("tr");
            const cell = row.cells[1]; // Cell containing the value to be edited
            const actionCell = row.cells[2]; // The cell where buttons go

            const saveBtn = actionCell.getElementsByClassName("save-btn")[0];
            const cancelBtn = actionCell.getElementsByClassName("cancel-btn")[0];
            const editBtn = actionCell.getElementsByClassName("edit-btn")[0];

     
            if (this.textContent == "Edit") {

                const elements = document.getElementsByClassName("error");
                while(elements.length > 0){
                    elements[0].parentNode.removeChild(elements[0]);
                }

                row.classList.add("editing");
                const currentValue = cell.textContent.trim();
                const inputType = row.cells[0].textContent.includes("Password") ? "password" : "text";
                
                // Replace cell content with input field and action buttons
                cell.innerHTML = `
                  <input type="${inputType}" value="${currentValue}" class="edit-input">
                  <span style="display:none;">${currentValue}</span>
                `;

                if (inputType == "password") {
                  cell.innerHTML = `
                    <input placeholder="Old Password" type="${inputType}" class="edit-input pass-edit-input pass-delete">
                    <span style="display:none;">${currentValue}</span>
                
                    <input placeholder="New Password" type="${inputType}" class="edit-input pass-edit-input">
                    <span style="display:none;">${currentValue}</span>
                
                    <input placeholder="Confirm Password" type="${inputType}" class="edit-input pass-edit-input pass-delete">
                    <span style="display:none;">${currentValue}</span>
                     `;
                }

                editBtn.style.display = "none";
                saveBtn.style.display = "block";
                cancelBtn.style.display = "block";
            }
        });
    });

    // SAVE
    document.addEventListener("click", function (e) {
      if (e.target.matches(".save-btn")) {
            const row = e.target.closest("tr");
            const field = row.cells[0].textContent.trim();
            const cell = row.cells[1];
           

            if (field != 'Password'){
              var newValue = cell.getElementsByTagName("input")[0].value;
            } else {
              var oldPass = cell.getElementsByTagName("input")[0].value;
              var newValue = cell.getElementsByTagName("input")[1].value;
              var confirmPass = cell.getElementsByTagName("input")[2].value;
            }
            
            
            const field_id = row.cells[0].id;
        
            const actionCell = row.cells[2];
            const saveBtn = actionCell.getElementsByClassName("save-btn")[0];
            const cancelBtn = actionCell.getElementsByClassName("cancel-btn")[0];
            const editBtn = actionCell.getElementsByClassName("edit-btn")[0];


            if (field == "Password"){
              var fieldInfo = `field=${encodeURIComponent(field_id)}&oldPass=${encodeURIComponent(oldPass)}&newPass=${encodeURIComponent(newValue)}&confirmPass=${encodeURIComponent(confirmPass)}`;
            } else {
              var fieldInfo = `field=${encodeURIComponent(field_id)}&value=${encodeURIComponent(newValue)}`;
            }

               const elements = document.getElementsByClassName("error");
                while(elements.length > 0){
                    elements[0].parentNode.removeChild(elements[0]);
                }

            // Send data to PHP file using AJAX
            fetch('php-action-files/update_profile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: fieldInfo
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cell.innerHTML = newValue;

                    if ( field == 'Password' ) {
                      cell.innerHTML = "***";            
                    }

                    editBtn.style.display = "block";
                    saveBtn.style.display = "none";
                    cancelBtn.style.display = "none";
                    row.classList.remove("editing");
  
                } else {

                   if ( field == 'Password' ) {
                      cell.innerHTML = "***";            
                    }

                  cell.innerHTML += "<div class='error error-blink'>" + data.message + "</div>";
                }

            });
        }
    });

    // CANCEL
    document.addEventListener("click", function (e) {
        if (e.target.matches(".cancel-btn")) {
            const row = e.target.closest("tr");
            const actionCell = row.cells[2];
            const cell = row.cells[1]; // Cell containing the value to be edited
            const editButton = row.querySelector(".edit-link");
            const oldValue = cell.getElementsByTagName("span")[0].innerHTML;

            const saveBtn = actionCell.getElementsByClassName("save-btn")[0];
            const cancelBtn = actionCell.getElementsByClassName("cancel-btn")[0];
            const editBtn = actionCell.getElementsByClassName("edit-btn")[0];

            

            // Reset to "Edit"
            editButton.textContent = "Edit";
            row.classList.remove("editing");
            cell.innerHTML = oldValue;

            editBtn.style.display = "block";
            saveBtn.style.display = "none";
            cancelBtn.style.display = "none";
        }
    });


});

</script>


<!-- flowers used dropdown (product-card) -->
<script>
function toggleDropdown(button) {
    const content = button.nextElementSibling;
    const parent = button.parentElement;

    content.style.display = content.style.display === "block" ? "none" : "block";
    parent.classList.toggle("open");
}
</script>


<!-- account signed up popup -->
<script>
        function openAccPopup() {
        document.getElementById('AccPopup').style.display = 'block';
        document.getElementById('AccOverlay').style.display = 'flex';
        }

        function closeAccPopup() {
        document.getElementById('AccPopup').style.display = 'none';
        document.getElementById('AccOverlay').style.display = 'none';
        }

        document.getElementById('openPopupBtn').addEventListener('click', openAccPopup);
</script>


<!-- copy to clipboard -->
<script>
        function copyToClipboard() {
            const text = document.getElementById("textToCopy").innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert("Code copied to clipboard!");
            });
        }
</script>


<script>
  <?php
    if ( isset( $_POST['submitSU'] ) ) {
  ?>
      openPopup();
  <?php
    }
  ?>
</script>


<script>
  <?php
    if ( isset( $_POST['submitLI'] ) ) {
  ?>
      openSPopup();
  <?php
    }
  ?>
</script>

<!-- Bootstrap -->

<!--
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

