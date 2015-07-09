<form method="post">
	<input type="hidden" value="<?php echo $rest_id; ?>" />
<table class="table">
	<thead>
		<tr>
			<th>Food Name</th>
			<th>Description</th>
			<th>Prices</th>
			<th>Rules</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($food_items as $item){
			$items = "<tr>";
			$items .= "<td><input type=\"text\" value=\"".$item['food_name']."\"/></input></td>";
			$items .= "<td><textarea>".$item['description']."</textarea></td>";
			$pricePoints = "";
			for($i = 0; $i < count($item['price']['topic']); $i++){
				$pricePoints .= "<input type=\"text\" value=\"".$item['price']['topic'][$i]."\"/>";
				$pricePoints .= "<input type=\"text\" value=\"".$item['price']['price'][$i]."\"/><br>";
			}
			$items .= "<td>".$pricePoints."</td>";
			$items .= "<td>Nothing</td>";
			$items .= "</tr>";

			echo $items;
		}?>
</table>