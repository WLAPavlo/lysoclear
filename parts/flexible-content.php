<?php if (have_rows('flexible_content')) { ?>
    <section class="flexible-content">
        <?php while (have_rows('flexible_content')) {
            the_row();
            get_template_part('parts/flexible/flexible', get_row_layout());
        } ?>
    </section>
<?php } ?>
