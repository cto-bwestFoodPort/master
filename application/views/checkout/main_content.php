<?php if(count($cart_contents) == 0){
	redirect(site_url());
}?>
<div class="panel panel-default no-pad col-lg-7">
	<div class="panel-heading">
		<?php echo form_open('billing/do_purchase'); ?>
		<input type="hidden" id="default_addr" />
		<section class="info">
			<h4>Your Information:</h4>
			<div class="name">
				<?php echo $first_name . " " . $last_name; ?>
			</div>
			<div class="address">
				<?php echo $address; ?>
			</div>
			<div class="phone">
				<?php echo $phone; ?>
			</div>
			<label for="addr_conf">Please verify the above information: </label>
			<input type="checkbox" name="addr_conf" val="yes" required />
			<span style="cursor:pointer;">or <a id="edit">edit</a></span>
		</section>
	</div>
	<div class="panel-body table-responsive">
		<h4>Your Order:</h4>
		<table class="orders_table table" cellpadding="6" cellspacing="1" style="width:100%" border="0">
			<tr>
				<th>QTY</th>
				<th>Item Description</th>
				<th style="text-align:right">Item Price</th>
			</tr>
			<?php $i = 1; ?>
			<?php foreach ($cart_contents as $items): ?>
			<?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>
			<!--TODO: move logic to controller; -->
			<tr <?php $paired = isset($items['options']['paired']) ? "data-paired='".$items['options']['paired']."'":""; echo $paired;?>data-foodid="<?php echo $items['id']; ?>" data-restid="<?php echo $items['options']['rest_id'];?>" <?php $promo = isset($items['options']['promos']) ? "data-promos='".implode(',', $items['options']['promos'])."'":""; echo $promo; ?>>
				<td><?php if(!isset($items['options']['paired'])){echo form_input(array('data-origqty' => $items['qty'], 'class' => 'qty', 'type' => 'number', 'name' => $i.'[qty]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5'));} ?></td>
				<td>
					<?php $description = trim(ucfirst($items['name'])); ?>
					<?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
					<?php $options = $this->cart->product_options($items['rowid']); ?>
					
					<?php $description .= " - " .$options['food_spec']; ?>
					<?php endif; ?>
					<?php echo $description; ?>
				</td>
				<td style="text-align:right" class="price"><?php echo $this->cart->format_number($items['price']); ?></td>
			</tr>
			<?php $i++; ?>
			<?php endforeach; ?>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2"> </td>
				<td><strong>Subtotal: </strong></td>
				<td class="subtotal"></td>
			</tr>
			<tr>
				<td colspan="2"> </td>
				<td><strong>Restaurant Tax: </strong></td>
				<td class="rest_tax"></td>
			</tr>
			<tr>
				<td colspan="2"> </td>
				<td><strong>Labor Fee: </strong></td>
				<td class="labor"></td>
			</tr>
			<tr>
				<td colspan="2"> </td>
				<td><strong>Mileage Fee: </strong></td>
				<td class="mileage"></td>
			</tr>
			<tr>
				<td colspan="2"> </td>
				<td><strong>Total: </strong></td>
				<td class="total"></td>
			</tr>
		</table>
		<p><?php echo form_submit('', 'Checkout'); ?></p>
	</div>
</div>