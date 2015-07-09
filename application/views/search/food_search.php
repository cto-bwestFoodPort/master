<form class="layer6 col-lg-12 search_form" action="#" method="post">
    <div class="form-group col-lg-10">
        <label for="addr_search">Enter your address: </label>
        <input type="text" id="addr_search" class="form-control" >
        <input type="hidden" id="default_addr"<?php if($zip){echo ' data-zip="'.$zip.'"';}?><?php if($city){echo ' data-city="'.$city.'"';}?><?php if($addr1){echo ' value="'.$addr1.'"';} ?>/>
    	<button style="position:relative; top: 25px;" type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

