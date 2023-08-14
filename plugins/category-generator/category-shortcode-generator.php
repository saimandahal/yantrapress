<!-- 
Plugin Name: Category Generator
Description: Generates a shortcode to display products from a specified category.
Version: 1.0
Author: Saiman Dahal
-->
<?php
function category_shortcode_generator_menu() {
    add_menu_page(
        'Category Shortcode Generator',
        'Shortcode Generator',
        'manage_options',
        'category-shortcode-generator',
        'category_shortcode_generator_page'
    );
}
add_action('admin_menu', 'category_shortcode_generator_menu');
function category_shortcode_generator_page() {
    ?>
    <div class="wrap">
        <h2>Category Shortcode Generator</h2>
        <form method="post">
            <label for="category">Enter Category name:</label>
            <input type="text" name="category" id="category" />
            <button class="button" type="submit">Generate Shortcode</button>
        </form>
        <?php
        if (isset($_POST['category'])) {
            $category_slug = sanitize_text_field($_POST['category']);
            $shortcode = "[category_products category='$category_slug']";
            echo "<h3>Generated Shortcode:</h3>";
            echo "<pre>$shortcode</pre>";
            echo "<h5>You can now use this shortcode in your elementor based sections</h5>";

        }
        ?>
    </div>
    <?php
}
function category_shortcode_generator_page() {
    ?>
    <div class="wrap">
        <h2>Category Shortcode Generator</h2>
        <form method="post">
            <label for="category">Enter Category Slug:</label>
            <input type="text" name="category" id="category" />
            <button class="button" type="submit">Generate Shortcode</button>
        </form>
        <?php
        if (isset($_POST['category'])) {
            $category_slug = sanitize_text_field($_POST['category']);
            $shortcode = "[category_products category='$category_slug']";
            echo "<h3>Generated Shortcode:</h3>";
            echo "<pre>$shortcode</pre>";
            echo "<h3>Products:</h3>";
            echo do_shortcode($shortcode);
        }
        ?>
    </div>
    <?php
}
