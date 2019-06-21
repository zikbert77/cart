<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>

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
            <a href="/?action=wishlist">wishlist</a>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="row">

                <h1>Cart</h1>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Id</td>
                            <td>image</td>
                            <td>title</td>
                            <td>quantity</td>
                            <td>price</td>
                            <td>action</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($inCart as $product): ?>
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
                                <form onsubmit="return false;">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <input type="text" name="quantity" value="<?= $product['quantity'] ?>">
                                </form>
                            </td>
                            <td>
                                <?= $product['price'] ?>
                            </td>
                            <td>
                                <button class="btn btn-danger remove-from-cart" data-product-id="<?= $product['product_id'] ?>">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        <tr>
                            <th>Total</th>
                            <td colspan="5" class="text-right totalAmount">0</td>
                        </tr>
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
        updateTotalAmount();

        function ajaxCall(path, data, toastrMessage) {
            $.post(path, data, function (data, status) {
                if (status === 'success') {
                    toastr.success(toastrMessage);
                } else {
                    toastr.danger('Some shit happens');
                }

                updateTotalAmount()
            })
        }

        function updateTotalAmount() {
            $.post('index.php', {
                getTotalAmount: true
            }, function (data, status) {
                $('.totalAmount').text(data)
            })
        }

        $('.remove-from-cart').on('click', function () {
            let productId = $(this).data('product-id');

            ajaxCall("index.php", {
                productId: productId,
                removeFromCart: true
            }, 'Product has been removed from cart');

            $(this).parents('tr:first').remove();
        });

        $('input[name=quantity]').change(function () {
            let form = $(this).parents('form:first');

            let productId = form.find("[name=product_id]").val();
            let quantity = $(this).val();

            ajaxCall("index.php", {
                productId: productId,
                quantity: quantity,
                changeQuantity: true
            }, 'Quantity has been changed');

            updateTotalAmount();
        })
    })
</script>
</body>
</html>