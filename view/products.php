<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Products</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-right">
                <a href="/?action=cart">cart</a> |
                <a href="/?action=wishlist">wishlist</a>
                <hr>
            </div>
        </div>
        <div class="row">

            <h1>Products</h1>

            <div class="col-12">
                <div class="row">

                    <?php foreach ($products as $product): ?>
                        <div class="col-2 product-wrapper text-center">
                            <div class="product-img">
                                <img src="<?= $product['image'] ?>" alt="">
                            </div>
                            <div class="product-title">
                                <h5><?= $product['title'] ?></h5>
                            </div>
                            <div class="product-price">
                                <span><?= $product['price'] ?></span>
                            </div>
                            <form onsubmit="return false;">
                                <div class="form-group">
                                    <input class="form-control" type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input class="form-control" type="hidden" name="product_title" value="<?= $product['title'] ?>">
                                    <button class="btn btn-warning btn-block add-to-wishlist">Add to wishlist</button>
                                    <input class="form-control" type="text" name="quantity" value="1">
                                    <button class="btn btn-success btn-block add-to-cart" type="submit" name="add_to_cart">Add to cart</button>
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function () {

            function ajaxCall(path, data, toastrMessage) {
                $.post(path, data, function (data, status) {
                    if (status === 'success') {
                        toastr.success(toastrMessage);
                    } else {
                        toastr.danger('Some shit happens');
                    }
                })
            }

            $('.add-to-cart').click(function () {
               let form = $(this).parents('form:first');

               let productId = form.find("[name=product_id]").val();
               let quantity = form.find("[name=quantity]").val();
               let productTitle = form.find("[name=product_title]").val();

               ajaxCall("index.php", {
                   productId: productId,
                   quantity: quantity,
                   addToCart: true
               }, productTitle +' has been added to cart');
            });

            $('.add-to-wishlist').click(function () {
                let form = $(this).parents('form:first');

                let productId = form.find("[name=product_id]").val();
                let productTitle = form.find("[name=product_title]").val();

                ajaxCall("index.php", {
                    productId: productId,
                    addToWishlist: true
                }, productTitle +' has been added to wishlist');
            });
        })
    </script>
</body>
</html>