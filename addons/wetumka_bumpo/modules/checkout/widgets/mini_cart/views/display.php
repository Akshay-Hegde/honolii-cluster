
    <?php foreach ($cart as $key => $items): ?>

            <table class="table table-striped">
                <tr><td>ID</td><td><?php echo $items['id']; ?></td></tr>
                <tr><td>Qty</td><td><?php echo $items['qty']; ?></td></tr>
                <tr><td>Price</td><td><?php echo $items['price']; ?></td></tr>
                <tr><td>Name</td><td><?php echo $items['name']; ?></td></tr>
            </table>

    <?php endforeach; ?>    
