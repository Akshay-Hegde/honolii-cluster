<div class="panel panel-default">
    <div class="panel-body">
        <?php foreach ($cart as $key => $items): ?>
    	
    	        <table class="table">
    	               
    	            <tr><td>Price</td><td>$<?php echo $items['price']; ?></td></tr>
    	            <tr>
    	            	<td>S&amp;H</td>
    	            	<td>Included</td>
    	            </tr>
    	            <tr><td>Name</td><td><?php echo $items['name']; ?></td></tr>
    	        </table>
    	
    	<?php endforeach; ?>
	</div>   
</div>