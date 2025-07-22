<?php if (have_rows('features_items')) { ?>
  <section class="features-section">
    <div class="grid-container menu-grid-container">
      <div class="grid-x grid-margin-x">
          <?php while (have_rows('features_items')) {
              the_row();
              $icon = get_sub_field('icon');
              $content = get_sub_field('content');
              ?>
            <div class="cell medium-4 features-item">
                <?php if ($icon) { ?>
                  <div class="features-item__icon">
                      <?php echo wp_get_attachment_image($icon['ID'], 'medium', false, ['alt' => $icon['alt']]); ?>
                  </div>
                <?php } ?>
                <?php if ($content) { ?>
                  <div class="features-item__content">
                      <?php echo $content; ?>
                  </div>
                <?php } ?>
            </div>
          <?php } ?>
      </div>
    </div>
  </section>
<?php } ?>
