<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Styles -->
</head>

<body>

    <div class="container-fluid">
        <h1 class="m-3 text-center">PayPal Payment</h1>

        <div class="container mb-2">
            <div class="row justify-content-center">
                <div class="col-8 ">
                    <form action="<?php echo e(route('paypal')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="price" id="floatingInput"
                                placeholder="Product Price">
                            <label for="floatingInput">Price (USD):</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="product_name" id="floatingProductName"
                                placeholder="Product Name">
                            <label for="floatingProductName">Product Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="quantity" id="floatingQuantity"
                                placeholder="Quantity">
                            <label for="floatingQuantity">Quantity</label>
                        </div>
                        <div class="form-floating mb-3">
                            <button class="btn btn-primary" type="submit">Pay with PayPal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="container border border-1">
        <form action="{{}}" method="POST">
            <?php echo csrf_field(); ?>
            <label for="amount">Amount (USD):</label>
            <input type="number" name="amount" required>

            <label for="card_type">Card Type:</label>
            <input type="text" name="card_type" required>

            <label for="card_number">Card Number:</label>
            <input type="text" name="card_number" required>

            <label for="expire_month">Expiration Month:</label>
            <input type="text" name="expire_month" required>

            <label for="expire_year">Expiration Year:</label>
            <input type="text" name="expire_year" required>

            <label for="cvv">CVV:</label>
            <input type="text" name="cvv" required>

            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" required>

            <button type="submit">Pay with Credit Card</button>
        </form>
    </div> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>


</body>

</html><?php /**PATH C:\Users\USER\Desktop\payment-methods\resources\views/payment.blade.php ENDPATH**/ ?>