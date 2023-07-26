<?php
/*
Plugin Name: Category Products
Description: Displaying products based on categories in WooCommerce.
Version: 1.0
Author: Saiman Dahal
*/

//Shortcode
//Format: [category_products category="featured" limit="5"]
function display_category_products($atts) {
    $atts = shortcode_atts(array(
        'category' => '',
        'limit' => 10,
    ), $atts, 'category_products');

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $atts['limit'],
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $atts['category'],
            ),
        ),
    );
    $products = new WP_Query($args);
    ob_start();
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            global $product;
            ?>
            <div class="product">
                <h3><?php the_title(); ?></h3>
                <div><?php the_excerpt(); ?></div>
                <div><?php echo $product->get_price_html(); ?></div>
            </div>
            <?php
        }
    } else {
        echo 'No products found.';
    }
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('category_products', 'display_category_products');
