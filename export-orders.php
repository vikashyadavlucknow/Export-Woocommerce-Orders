<?php
/*
Plugin Name: Export Woocommerce Orders
Description: Export WooCommerce orders from the Orders page.
Version: 1.0
Author: Vikash yadav
*/

// Enqueue JavaScript file
function enqueue_export_orders_script()
{
    wp_enqueue_script('export-orders', plugin_dir_url(__FILE__) . 'export-orders.js', array('jquery'), '1.0', true);
    wp_localize_script('export-orders', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('export_orders_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'enqueue_export_orders_script');
// Add export button to WooCommerce Orders page

function add_all_required_buttons()
{
    global $pagenow;
    // Check if the current page is the WooCommerce Orders page
    if ($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'shop_order') {
?>
        <script>
            jQuery(document).ready(function($) {
                $('<button class="button export-selected-products" id="exportSelectedOrders" style="margin-top:10px;margin-right:5px">Export Selected Orders</button>').insertBefore('.wp-header-end');
                $('#exportSelectedOrders').click(function(e) {
                    e.preventDefault();
                    var selectedOrders = $('input[name="post[]"]:checked').map(function() {
                        return this.value;
                    }).get();
                    if (selectedOrders.length === 0) {
                        alert('Please select at least one order.');
                        return;
                    }
                    // AJAX call to handle product export
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'export_selected_products',
                            orders: selectedOrders
                        },
                        success: function(response) {
                            if (response.success) {
                                window.location.href = response.data.file_url;
                            } else {
                                alert('Failed to export selected products.');
                            }
                        },
                        error: function() {
                            alert('Error exporting selected products.');
                        }
                    });
                });

            });
        </script>
        <script>
            (function($) {
                // Add Export Orders button
                $('#post-query-submit').after('<form id="date-picker-form"><input type="date" id="start-date" name="start_date"><input type="date" id="end-date" name="end_date"><button id="export-orders-button" type="submit" style="padding: 7px;color: steelblue;background: whitesmoke;border: 1px solid #90b4d2;border-radius: 3px;cursor: pointer;">Export Orders</button></form>');
                // Add Export All Orders button
                $('.wp-header-end').before('<a href="#" class="page-title-action" id="downloadOrders" style="margin-right: 0.5rem">Export All Orders</a>');
            })(jQuery)
        </script>
<?php
    }
}
add_action('admin_footer', 'add_all_required_buttons');

include(plugin_dir_path(__FILE__) . 'inc/export-selected-orders.php');
include(plugin_dir_path(__FILE__) . 'inc/export-datewise-orders.php');
include(plugin_dir_path(__FILE__) . 'inc/export-all-orders.php');
?>