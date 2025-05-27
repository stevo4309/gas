<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joy Smart Gas - Select Product</title>
    <style>
        body { 
            text-align: center; 
            font-family: Arial, sans-serif; 
            padding: 50px; 
            background: url('images/gas-hero.jpg') no-repeat center center/cover;
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container { 
            max-width: 400px; 
            padding: 20px; 
            background: rgba(255, 255, 255, 0.9); 
            border-radius: 10px; 
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2); 
            backdrop-filter: blur(5px);
            color: black;
        }
        button { 
            width: 100%; 
            padding: 15px; 
            margin: 10px 0; 
            border-radius: 5px; 
            border: none; 
            cursor: pointer; 
            font-size: 16px; 
            transition: 0.3s; 
        }
        button:hover { 
            opacity: 0.8; 
        }
        .btn-refill { background-color: #007bff; color: white; }
        .btn-complete { background-color: #28a745; color: white; }
        .btn-accessories { background-color: #ff6600; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2>Choose a Product</h2>
    <form id="productForm" action="order.php" method="POST">
        <button type="submit" name="product" value="refilling" class="btn-refill">Gas Refilling</button>
        <button type="submit" name="product" value="complete_gas" class="btn-complete">Complete Gas Cylinder</button>
        <button type="submit" name="product" value="accessories" class="btn-accessories">Gas Accessories</button>
    </form>
</div>

<script>
    document.getElementById("productForm").addEventListener("submit", function(event) {
        let selectedProduct = document.activeElement.value;
        console.log("Submitting Order for: ", selectedProduct);

        if (!selectedProduct) {
            event.preventDefault();
            alert("Please select a product!");
        }
    });
</script>

</body>
</html>
