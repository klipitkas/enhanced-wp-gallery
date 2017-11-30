<?php
/*
    Plugin Name: Enhanced WordPress Gallery
    Description: Adds enhanced features to the default WordPress gallery.
    Version:     1.0
    Author:      Konstantinos Lypitkas
*/

if ( ! defined( 'ABSPATH' ) )
    exit( 'This plugin should not be accessed directly!' );

function ewg_load_frontend_assets() {
    wp_register_style(
        'fancybox3-css',
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css'
    );
    wp_register_script(
        'fancybox3-js',
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js'
    );
    wp_enqueue_style( 'fancybox3-css' );
    wp_enqueue_script( 'fancybox3-js' );
}

function enhanched_wordpress_gallery_shortcode($args){
    $image_ids = isset($args['ids']) ? $args['ids'] : array();

    if ( empty( $image_ids ) )
        exit( 'No images have been specified for this gallery!' );

    $columns = isset($args['columns']) ? $args['columns'] : 3;
    $size = isset($args['size']) ? $args['size'] : 'thumbnail';
    $supported_image_sizes = get_intermediate_image_sizes();

    if ( ! in_array( $size, $supported_image_sizes ) )
        exit( 'The specified image size is not available!' );

    $galleryHTML = "<div id=\"ewg-gallery\" class=\"gallery " .
        "gallery-columns-$columns gallery-size-$size\">";

    $image_ids = str_replace(' ', '', $image_ids);
    $image_ids = explode(',', $image_ids);

    foreach ($image_ids as $image_id) {
        $thumb = wp_get_attachment_image_src($image_id, $size);
        $full = wp_get_attachment_image_src($image_id, 'full');

        if ( $thumb == false || $full == false )
            continue;

        $galleryHTML .= "<figure class=\"gallery-item\">" .
            "<div class=\"gallery-icon landscape\">";
        $galleryHTML .= "<a data-fancybox=\"images\" data-width=\"$full[1]\" " .
            "data-height=\"$full[2]\" href=\"$full[0]\">";
        $galleryHTML .= "<img src=\"$thumb[0]\"/>";
        $galleryHTML .= "</a>";
        $galleryHTML .= "</div></figure>";
    }

    $galleryHTML .= "</div>";

    echo $galleryHTML;
}

add_action('wp_enqueue_scripts', 'ewg_load_frontend_assets', 99999);
add_shortcode('ewg', 'enhanched_wordpress_gallery_shortcode');
