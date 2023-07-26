<?php
/*
Plugin Name: Product Tag Filter
Description: Displays WooCommerce products based on tags.
To display, write shortcode as: [product_tag_filter tags="tag1,tag2"]
Version: 1.0
Author: Saiman Dahal
*/
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    require_once plugin_dir_path(__FILE__) . 'includes/product-tag-filter-functions.php';
    add_shortcode('product_tag_filter', 'product_tag_filter_shortcode');
}
