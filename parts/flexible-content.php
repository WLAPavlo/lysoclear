<?php if (have_rows('flexible_content')) { ?>
    <section class="flexible-content">
        <?php while (have_rows('flexible_content')) {
            the_row();
            $layout = get_row_layout();


            get_template_part('parts/flexible/flexible', $layout);
        } ?>
    </section>
<?php } ?>
