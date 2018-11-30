<?php
$kw = '';


$products_q = array();
$products_q['limit'] = 10;
$products_q['content_type'] = 'product';

if (isset($params['kw']) and $params['kw']) {
    $kw = $params['kw'];
    $products_q['keyword'] = $kw;
}

$products = get_content($products_q);

$data = $products;
//d($products );

?>
<script>mw.require('autocomplete.js')</script>

<script>
    mw_admin_custom_checkout_callback = function () {
        $('#mw_admin_edit_order_item_popup_modal').remove();

        mw.reload_module('shop/orders/manage');
        mw.reload_module('shop/checkout');
        mw.reload_module('shop/cart');
        mw.notification.success("Order completed", 5000);
    }
</script>





<script>



    $(document).ready(function () {
        var created_by_field = new mw.autoComplete({
            element:"#select-post-author",
            ajaxConfig: {
                method: 'get',
                url: mw.settings.api_url + 'get_content_admin?get_extra_data=1&content_type=product&kw=${val}'
            },
            map : {
                value: 'id',
                title: 'title',
                image: 'picture'
            },
            /*selected:[
                {
                    id: 0,
                    display_name: '0'
                }
            ]*/
        });
        $(created_by_field).on("change", function(e, val){

            $("#created_by").val(val[0].id).trigger('change')
        })
    });


</script>
<div id="select-post-author"></div>

<input type="text" name="created_by" id="created_by">







<script>







    mw_admin_custom_order_item_add = function ($form) {
        mw.cart.add($form);

    }


    $(function () {
        add_order_tabs =  mw.tabs({
            nav: '#mw-add-order .mw-ui-btn-nav-tabs a',
            tabs: '#mw-add-order .mw-ui-box-content'
        });

        $( window ).on( "mw.cart.add", function() {
            mw.reload_module('shop/cart');
            mw.notification.success("Item added to cart");
            add_order_tabs.set(1)
        });





        $('.js-search-product-for-custom-order').on('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var $form = $(this);
            var $kw = '';
            $form.children('input[type="text"]').each(function () {
                if ($(this).val().length != 0) {
                    $kw = $(this).val();
                }
            });

            $('#<?php print $params['id'] ?>').attr('kw', $kw);
            mw.reload_module('#<?php print $params['id'] ?>')

        });
    });


</script>

<div class="demobox" id="mw-add-order">
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
        <a href="javascript:;" class="mw-ui-btn active">Add to cart</a>
        <a href="javascript:;" class="mw-ui-btn">My cart's content</a>
        <a href="javascript:;" class="mw-ui-btn">Checkout</a>

    </div>
    <div class="mw-ui-box">
        <!-- Add to cart -->
        <div class="mw-ui-box-content">
            <div id="mw_admin_custom_order_item_add_form" class="m-b-10">
                <h2>Add custom item</h2>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">Product name</label>
                    <input type="text" name="title" class="mw-ui-field element-block" required="required">
                    <input type="hidden" name="for" value="custom_item">
                    <input type="hidden" name="for_id" value="1">
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">Price</label>
                    <input type="text" name="price" class="mw-ui-field element-block" required="required"
                           placeholder="Example: 10"/>
                </div>

                <div class="right">
                    <button class="mw-ui-btn mw-ui-btn-info"
                            onclick="mw_admin_custom_order_item_add('#mw_admin_custom_order_item_add_form')"><i
                                class="mai-plus"></i> add
                    </button>
                </div>
            </div>

            <hr>

                <h2>Add existing product</h2>

                <div class="mw-ui-field-holder">
                    <form class="js-search-product-for-custom-order">
                        <input type="text" name="search" class="mw-ui-field element-block "
                               placeholder="Search product in catalog" required="required" value="<?php print $kw; ?>">
                    </form>
                </div>



            <?php if (is_array($data) and !empty($data)): ?>
                <div class="table-responsive">
                    <table class="mw-ui-table table-style-2 layout-auto" width="100%" cellspacing="0" cellpadding="0">
                        <tbody>
                        <?php foreach ($data as $item): ?>
                            <?php
                            $pub_class = '';
                            $append = '';
                            if (isset($item['is_active']) and $item['is_active'] == '0') {
                                $pub_class = ' content-unpublished';
                                $append = '<div class="post-un-publish"><span class="mw-ui-btn mw-ui-btn-yellow disabled unpublished-status">' . _e("Unpublished", true) . '</span><span class="mw-ui-btn mw-ui-btn-green publish-btn" onclick="mw.post.set(' . $item['id'] . ', \'publish\');">' . _e("Publish", true) . '</span></div>';
                            }
                            ?>
                            <?php $pic = get_picture($item['id']); ?>

                            <tr>
                                <td><a class="text-center"><span class="mw-user-thumb image"
                                                                 style="background-image: url('<?php print $pic ?>');"></span></a>
                                </td>
                                <td><?php print content_title($item['id']) ?><?php print $append; ?></td>
                                <td>
                                    <module type="shop/cart_add" template="mw_default"
                                            content-id="<?php print ($item['id']) ?>"/>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
            No products found
            <?php endif; ?>

        </div>

        <!-- My cart's content -->
        <div class="mw-ui-box-content" style="display: none;">
            <module type="shop/cart" data-checkout-link-enabled="n" template="mw_default"/>
        </div>

        <!-- Checkout -->
        <div class="mw-ui-box-content" style="display: none;">
            <module type="shop/checkout" data-checkout-link-enabled="n" template="mw_default"
                    id="mw-admin-custom-checkout-add-order"/>
        </div>
    </div>


    <button class="mw-ui-btn mw-ui-btn-notification m-t-20 pull-right"
            onclick="mw.cart.checkout('#mw-admin-custom-checkout-add-order', mw_admin_custom_checkout_callback);"
            type="button">
        <?php _e("Complete order"); ?>
    </button>
</div>










