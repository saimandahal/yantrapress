<?php
/*
Plugin Name: Category Products
Description: Displaying products based on categories in WooCommerce.
Version: 1.0
Author: Saiman Dahal
*/

//Shortcode
//Format: [category_products category="grocery" limit="5"]
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
	?>
<style>
        

        .product img {
				width: 300px;
            height: auto;
            margin-bottom: 10px;
        }

        .product h3 {
            margin: 0;
        }

        .star-ratings {
            display: inline-block;
        }

        .star-ratings .star-rating {
            color: #ffc720;
            font-size: 16px;
        }

        .star-ratings .star-rating:before {
            content: '\2605';
        }

        /* Custom styles for links */
        .product h3 a {
            text-decoration: none;
            color: #333;
        }

        .product h3 a:hover {
            color: #cc0000;
        }
	.product img {
            transition: transform 0.3s ease;
        }

        .product img:hover {
            transform: scale(1.1);
        }

    </style>

            <div class="product">
				<div class="row">
					<div class="col-md-3">
	<?
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            global $product;
            ?>
						
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a> <!-- Image wrapped in a link -->
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3> <!-- Title wrapped in a link -->
                <div class="star-ratings">
                    <?php echo wc_get_rating_html($product->get_average_rating()); ?> <!-- Display star ratings -->
                </div>
                <div><?php the_excerpt(); ?></div>
                <div><?php echo $product->get_price_html(); ?></div>
            
				
				<?php
        }
    } 
				

	else {
        echo 'No products found.';
    }
	?>
								</div>
				</div>
			</div>
<?php
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('category_products', 'display_category_products');
