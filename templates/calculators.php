<?php

// - - - - - Calculate by Square Feet

$getCalc = get_post_meta( get_the_ID(), '_select', true );

echo $_POST["yp_postal"];

if ($getCalc === 'sf') {
	?>

	<div id="calcbags" class="yp_custompricing">
		<p><strong>Price:</strong> $<span id="yp_prodprice"></span> / Square Foot</p>
		<p>Charge by: <?php echo get_post_meta( get_the_ID(), '_select', true ); echo " : " .$_SESSION['postal']; ?></p>
		<p id="yp_instruct" style='color:#FF0000'>Please enter your length and width.</p>
		<p style="overflow:hidden;">
		<span style="width:49%; margin-right:2%; float:left">
		<strong>Length (feet):</strong><br/><input type='text' name='yp_length' class='ypdimension numonly'/>
		</span>
		<span style="width:49%; float:left; clear:right">
		<strong>Width (feet):</strong><br/><input type='text' name='yp_width' class='ypdimension numonly'/>
		</span>
		</p>
		<p><strong>Square Footage:</strong><br/><input type='text' name='yp_sf' readonly/></p>
		<p><strong>Number of Pallets:</strong><br/><input type='text' name='yp_numbags' readonly/><span style='font-size:11px'><strong>1 Pallet = 700 Square Feet</strong></p>
		<p><strong>Delivery Date:</strong><br/><span style='font-size:11px'>Select a date or leave blank for next day delivery.</span><br/><input type='text' name='yp_deliverydate' class='datepicker' readonly/><span style='font-size:11px'><strong>Time Now: <?php $this->setTime(); ?></strong><br/><strong>Note: </strong>Orders by 12:00PM will be shipped tomorrow by 12:00PM. Orders by 5:00PM will be shipped tomorrow by 5:00PM.</span></p>
		<hr></hr>
		<p><strong>Sub Total:</strong> <span id='subtotal'></span></p>
		<p><strong>Shipping Total:</strong> <span id='shippingprice'></span><input type='hidden' name='yp_shippingprice' /></p>
		<p><strong>Total:</strong> <span id='totalprice'></span></p>
		<input type='hidden' name='yp_price' readonly/>
		<input type='hidden' name='yp_calctype' value="sf" />
	</div>

	<?php
// - - - - - Calculate by Cubic Square Yards
}elseif ($getCalc === 'cu'){
	?>	
	<div id="calcbulk" class="yp_custompricing">
		<p><strong>Price:</strong> $<span id="yp_prodprice"></span> / Cubic Yard</p>
		<p>Charge by: <?php echo get_post_meta( get_the_ID(), '_select', true ); echo " : " .$_SESSION['postal']; ?></p>
		<p id="yp_instruct" style='color:#FF0000'>Please enter your depth, length and width.</p>
		<p><strong>Depth: </strong><select name='yp_depth'><option value='1'>1"</option><option value='2'>2"</option><option value='3'>3"</option><option value='4'>4"</option><option value='5'>5"</option><option value='6'>6"</option></select></p>
		<p style="overflow:hidden;">
		<span style="width:49%; margin-right:2%; float:left">
		<strong>Length (feet):</strong><br/><input type='text' name='yp_length' class='ypdimension numonly'/>
		</span>
		<span style="width:49%; float:left; clear:right">
		<strong>Width (feet):</strong><br/><input type='text' name='yp_width' class='ypdimension numonly'/>
		</span>
		</p>
		<p><strong>Cubic Yards:</strong><br/><input type='text' name='yp_cubicyards' readonly/></p>
		<p><strong>Delivery Date:</strong><br/><span style='font-size:11px'>Select a date or leave blank for next day delivery.</span><br/><input type='text' name='yp_deliverydate' class='datepicker' readonly/><span style='font-size:11px'><strong>Time Now: <?php $this->setTime(); ?></strong><br/><strong>Note: </strong>Orders by 12:00PM will be shipped tomorrow by 12:00PM. Orders by 5:00PM will be shipped tomorrow by 5:00PM.</span></p>
		<hr></hr>
		<p><strong>Sub Total:</strong> <span id='subtotal'></span></p>
		<p><strong>Shipping Total:</strong> <span id='shippingprice'></span><input type='hidden' name='yp_shippingprice' /></p>
		<p><strong>Total:</strong> <span id='totalprice'></span></p>
		<input type='hidden' name='yp_price' readonly/>
		<input type='hidden' name='yp_calctype' value="cu" />
	</div>
	<?php
}
?>