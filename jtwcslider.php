<?php
/*
Plugin Name: Jtwcslider para Wordpress
Plugin URI: http://jonathan.com
Description: Agrega Jtwcslider a WooCommerce
Version: 1.0
Author: Jonathan Toro
Author URI:
Text Domain:
Domain Path:
*/

// No permitir que se cargue directamente
defined('ABSPATH') or die('No script kiddies please!');

//Obtener un Path
define('JTWCSLIDER_PATH', plugin_dir_url(__FILE__));

//Cargar Scripts y Stylesheets
function jtwcslider_scripts(){
    wp_enqueue_style('bxslider', JTWCSLIDER_PATH . '/css/jquery.bxslider.min.css');

    if(wp_script_is('jquery', 'enqueued')) {
        return;
    }else{
        wp_enqueue_script('jquery');
    }

    wp_enqueue_script('bxsliderjs', JTWCSLIDER_PATH . '/js/jquery.bxslider.min.js');
}
add_action('wp_enqueue_scripts', 'jtwcslider_scripts');

// crea el shortcode para mostrar productos
// uso: [jtwcslider]
function jtwcslider_shortcode(){
    $args = array(
        'post_per_page' => 10,
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN'
            )
        )
    );
    $slider_productos = new WP_Query($args);
    echo "<ul class='slider'>";
    while($slider_productos->have_posts()): $slider_productos->the_post(); ?>
        <li>
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('shop_catalog');
            the_title('<h3>', '</h3>'); ?>
            </a>
        </li>
<?php
endwhile; wp_reset_postdata();
echo "</ul>";
}
add_shortcode('jtwcslider', 'jtwcslider_shortcode');

// Agregando el metodo en el footer
function jtwcslider_ejecutar() { ?>
    <script>
        $ = jQuery.noConflict();
        $(document).ready(function(){
            $('.slider').bxSlider({
                auto: true,
                minSlides: 4,
                maxSlides: 4,
                slideWidth: 250,
                slideMargin: 10,
                moveSlides: 1
            });
        });
    </script>
    <?php
    }
    add_action('wp_footer', 'jtwcslider_ejecutar');
