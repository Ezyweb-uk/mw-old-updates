<?php

/*

type: layout

name: Default

description: Default

*/




?>
    <h2 style="margin-top:0 " class="edit nodrop" field="checkout_shipping_information_title" rel="global"
        rel_id="<?php print $params['id'] ?>">
        <?php _e("Shipping Information"); ?>
    </h2>
    <hr/>

<?php

include (__DIR__.'/_select.php');
?>