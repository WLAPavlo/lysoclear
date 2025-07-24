<?php
// Get page title and featured image for banner
$banner_title = get_the_title();
$banner_image_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full_hd') : 'https://images.pexels.com/photos/3845810/pexels-photo-3845810.jpeg?auto=compress&cs=tinysrgb&w=1920&h=600&fit=crop';
?>

<section class="treatment-banner">
    <div class="treatment-banner__inner" style="background-image: url('<?php echo esc_url($banner_image_url); ?>');">
        <div class="treatment-banner__overlay"></div>
        <div class="treatment-banner__content">
            <div class="treatment-title-block">
                <h1 class="treatment-title"><?php echo esc_html($banner_title); ?></h1>
            </div>
        </div>
    </div>
</section>
