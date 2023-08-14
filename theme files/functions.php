<?php
// Enqueue custom styles

function child_theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles' );



function change_woocommerce_breadcrumb_separator( $defaults ) {
    $defaults['delimiter'] = ' > ';
    return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'change_woocommerce_breadcrumb_separator' );

function display_gray_stars() {
    $stars = '<span class="gray-stars">';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 22 22"><path d="M11 1.37512C11.2705 1.37512 11.535 1.45515 11.7601 1.60514C11.9852 1.75513 12.1609 1.96836 12.265 2.218L14.2657 6.9755L19.3738 7.41808C19.6436 7.44075 19.9007 7.54258 20.1129 7.71079C20.3251 7.87901 20.4828 8.10612 20.5665 8.36365C20.6501 8.62118 20.6558 8.89767 20.5829 9.15844C20.51 9.4192 20.3617 9.65264 20.1567 9.82948L16.2766 13.212L17.4376 18.2479C17.4991 18.5112 17.4815 18.7867 17.3872 19.0401C17.2929 19.2934 17.126 19.5134 16.9074 19.6724C16.6888 19.8314 16.4281 19.9224 16.158 19.9341C15.8879 19.9458 15.6204 19.8776 15.3889 19.7381L10.9966 17.074L6.61379 19.7381C6.38225 19.8776 6.11472 19.9458 5.84463 19.9341C5.57454 19.9224 5.3139 19.8314 5.09527 19.6724C4.87665 19.5134 4.70975 19.2934 4.61544 19.0401C4.52114 18.7867 4.50361 18.5112 4.56504 18.2479L5.72434 13.2171L1.8477 9.82948C1.64266 9.65264 1.4944 9.4192 1.4215 9.15844C1.3486 8.89767 1.35432 8.62118 1.43793 8.36365C1.52154 8.10612 1.67932 7.87901 1.89149 7.71079C2.10366 7.54258 2.36078 7.44075 2.63059 7.41808L7.74043 6.9755L9.73504 2.218C9.8392 1.96836 10.0149 1.75513 10.24 1.60514C10.4651 1.45515 10.7295 1.37512 11 1.37512Z" fill="#D0D0D0"/></svg>';
    }
    $stars .= '</span>';
    return $stars;
}

 function display_best_selling_products($atts) {
    $atts = shortcode_atts(array(
        'limit' => 10,
    ), $atts, 'best_selling_products');

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $atts['limit'],
        'meta_key' => 'total_sales',
        'orderby' => 'meta_value_num',
        'order' => 'desc',
    );

    $products = new WP_Query($args);

    ob_start();

    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            global $product;
			$average_rating = $product->get_average_rating();

            ?>
            <div class="product">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<div class="star-ratings">
                    <?php
                    if ($average_rating > 0) {
                        echo wc_get_rating_html($average_rating);
                    } else {
                        echo display_gray_stars();
                    }
                    ?>
                </div>
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
add_shortcode('best_selling_products', 'display_best_selling_products');

?>