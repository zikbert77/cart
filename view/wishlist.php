<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wishlist</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12 text-right">
            <a href="/">products</a> |
            <a href="/?action=cart">cart</a>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="row">

                <h1>Wishlist</h1>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td>Id</td>
                        <td>image</td>
                        <td>title</td>
                        <td>price</td>
                        <td>action</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($inWishList as $product): ?>
                        <tr id="tr_<?= $product['id'] ?>">
                            <td>
                                <?= $product['id'] ?>
                            </td>
                            <td>
                                <img src="<?= $product['image'] ?>" alt="">
                            </td>
                            <td>
                                <?= $product['title'] ?>
                            </td>
                            <td>
                                <?= $product['price'] ?>
                            </td>
                            <td>
                                <button class="btn btn-danger remove-from-wishlist" data-product-id="<?= $product['product_id'] ?>">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

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

        $('.remove-from-wishlist').on('click', function () {
            let productId = $(this).data('product-id');

            ajaxCall("index.php", {
                productId: productId,
                removeFromWishlist: true
            }, 'Product has been removed from wishlist');

            $(this).parents('tr:first').remove();
        });
    })
</script>
</body>
</html>