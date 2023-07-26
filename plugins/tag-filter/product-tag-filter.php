<?php
function product_tag_filter_shortcode($atts) {
    $atts = shortcode_atts(array(
        'tags' => '', 
    ), $atts, 'product_tag_filter');
    $tags_array = explode(',', $atts['tags']);
    $args = array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_tag',
                'field'    => 'slug',
                'terms'    => $tags_array,
            ),
        ),
    );

    $products_query = new WP_Query($args);
    ob_start();

    if ($products_query->have_posts()) {
        echo '<ul class="product-tag-filter">';
        while ($products_query->have_posts()) {
            $products_query->the_post();
            global $product;
            ?>
            <li>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </li>
            <?php
        }
        echo '</ul>';
        wp_reset_postdata();
    } else {
        echo '<p>No products found with the specified tags.</p>';
    }
    return ob_get_clean();
}
