<div class="order-tab">
	<h3><span class="glyphicon glyphicon-shopping-cart"></span>Your dCart</h3>
	<hr>
	<div class="cart_contents">
		<?php echo form_open("checkout", ["style" => "position:relative; z-index: 99999;"]); ?>
			<div class="totals row" style="display:none;">
				<span class="col-lg-6 col-xs-5 col-xs-offset-1">Subtotal:</span><span class="subtotal col-lg-5 col-xs-6"></span>
				<span class="col-lg-6 col-xs-5 col-xs-offset-1">Food tax:</span><span class="rest_tax col-lg-5 col-xs-6"></span>
				<span class="col-lg-6 col-xs-5 col-xs-offset-1">Labor Fee:</span><span class="labor col-lg-5 col-xs-6"></span>
				<span class="col-lg-6 col-xs-5 col-xs-offset-1 mileageHead" style="display:none;">Mileage Fee:</span><span class="mileage col-lg-5 col-xs-6" style="display:none;"></span>
				<span class="col-lg-6 col-xs-5 col-xs-offset-1">Total:</span><span class="total col-lg-5 col-xs-6"></span>
				<div class="btn-group col-lg-12">
					<button class="btn btn-primary" style="margin:auto;">Continue</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script>
	
</script>