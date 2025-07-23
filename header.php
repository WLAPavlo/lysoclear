<?php
/**
 * Header.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <!-- Set up Meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="<?php bloginfo('charset'); ?>">

    <!-- Set the viewport width to device width for mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <!-- Remove Microsoft Edge's & Safari phone-email styling -->
    <meta name="format-detection" content="telephone=no,email=no,url=no">

    <?php wp_head(); ?>
</head>

<body <?php body_class('no-outline fwp'); ?>>
<?php wp_body_open(); ?>

<!-- BEGIN of header -->
<header class="header">
    <div class="grid-container menu-grid-container">
        <div class="grid-x">
            <div class="cell auto">
                <div class="logo">
                    <h1>
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php show_custom_logo(); ?>
                            <span class="show-for-sr"><?php echo get_bloginfo('name'); ?></span>
                        </a>
                    </h1>
                </div>
            </div>
            <div class="cell shrink show-for-large">
                <?php if (has_nav_menu('header-menu')) { ?>
                    <nav>
                        <?php wp_nav_menu([
                            'theme_location' => 'header-menu',
                            'menu_class' => 'menu header-menu',
                            'container' => false,
                        ]); ?>
                    </nav>
                <?php } ?>
            </div>
            <div class="cell shrink hide-for-large">
                <?php if (has_nav_menu('header-menu')) { ?>
                    <button class="mobile-menu-toggle" type="button" aria-label="Toggle mobile menu">
                        <div class="hamburger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <?php if (has_nav_menu('header-menu')) { ?>
        <div class="mobile-menu-overlay hide-for-large"></div>
        <nav class="mobile-menu hide-for-large" id="mobile-menu">
            <?php wp_nav_menu([
                'theme_location' => 'header-menu',
                'menu_class' => 'header-menu',
                'container' => false,
            ]); ?>
        </nav>
    <?php } ?>
</header>
<!-- END of header -->
