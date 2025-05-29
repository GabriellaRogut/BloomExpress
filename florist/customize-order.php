<?php 
    include("DB/connection.php");
    include("config/config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloomExpress - Customize Your Bouquet</title>
    
    <link rel="icon" type="image/x-icon" href="images/logo-ico.ico">
    <?php include("elements/links.php")?>

    <link rel="stylesheet" href="styles/style.css?v=<?= time() ?>">

</head>

<body>
    <?php include("elements/offcanvas.php") ?>


        <div class="row">

            <div class="col-2 col-xl-1 sidebar-col">
                <div class="sidebar">

                    <div class="logo-row">
                        <a href="index.php"><img class="sb-logo" src="images/logo.png" alt="logo"></a>
                    </div>
                    
                    <a href="index.php">Home</a>
                    <a href="shop.php" class="active">Shop</a>
                    <a href="cart.php">Cart</a>
                    <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasOrders">Orders</a>
                    <a href="account.php">Account</a>
                </div>
            </div>


            <!-- Content -->
            <div class="col-10 col-xl-11 custom-content">
                <div class="col-9 choice-col">
                    <div class="row" id="flower-container">
                        <!-- Flower options are here /js/ -->
                    </div>
                </div>

                
                <div class="col-3 price-col">
                    <h4 class="total-price-custom">Total Price: $<span id="total-price">0</span></h4>
                    <button class="ss-btn cust-btn">Add to Cart</button>
                </div>
            </div>
        </div>

    <script>
        const flowers = [
            { name: "Roses", price: 10 },
            { name: "Tulips", price: 7 },
            { name: "Lilies", price: 8 },
            { name: "Sunflowers", price: 6 },
            { name: "Daisies", price: 5 },
            { name: "Dahlias", price: 8 },
            { name: "Baby's-Breath", price: 4}
        ];
        
        const flowerContainer = document.getElementById("flower-container");
        const totalPriceEl = document.getElementById("total-price");
        let totalPrice = 0;

        flowers.forEach((flower, index) => {
            const flowerCol = document.createElement("div");
            flowerCol.classList.add("col-md-4", "mb-3");
            flowerCol.innerHTML = `
                <div class="card flower-card" data-price="${flower.price}" data-name="${flower.name}">
                    <h5>${flower.name}</h5>
                    <p>Price: $${flower.price}</p>
                    <input type="number" class="form-control qty-input" data-index="${index}" value="0" min="0">
                </div>
            `;
            flowerContainer.appendChild(flowerCol);
        });
        
        document.querySelectorAll(".qty-input").forEach(input => {
            input.addEventListener("input", () => {
                totalPrice = 0;
                document.querySelectorAll(".qty-input").forEach(input => {
                    const index = input.getAttribute("data-index");
                    totalPrice += flowers[index].price * parseInt(input.value || 0);
                });
                totalPriceEl.textContent = totalPrice;
            });
        });
    </script>


    <?php include("elements/footer.php")?>

</body>
</html>