// Import everything from autoload folder
import './autoload/**/*'; // eslint-disable-line

// Import local dependencies
import './plugins/lazyload';
import './plugins/modernizr.min';
import 'slick-carousel';
import 'jquery-match-height';
import objectFitImages from 'object-fit-images';
// import '@fancyapps/fancybox/dist/jquery.fancybox.min';
// import { jarallax, jarallaxElement } from 'jarallax';
// import ScrollOut from 'scroll-out';

/**
 * Import scripts from Custom Divi blocks
 */
// eslint-disable-next-line import/no-unresolved
// import '../blocks/divi/**/index.js';

/**
 * Import scripts from Custom Elementor widgets
 */
// eslint-disable-next-line import/no-unresolved
// import '../blocks/elementor/**/index.js';

/**
 * Import scripts from Custom ACF Gutenberg blocks
 */
// eslint-disable-next-line import/no-unresolved
// import '../blocks/gutenberg/**/index.js';

/**
 * Init foundation
 */
$(document).foundation();

/**
 * Fit slide video background to video holder
 */
function resizeVideo() {
  let $holder = $('.videoHolder');
  $holder.each(function () {
    let $that = $(this);
    let ratio = $that.data('ratio') ? $that.data('ratio') : '16:9';
    let width = parseFloat(ratio.split(':')[0]);
    let height = parseFloat(ratio.split(':')[1]);
    $that.find('.video').each(function () {
      if ($that.width() / width > $that.height() / height) {
        $(this).css({
          width: '100%',
          height: 'auto',
        });
      } else {
        $(this).css({
          width: ($that.height() * width) / height,
          height: '100%',
        });
      }
    });
  });
}

/**
 * Scripts which runs after DOM load
 */
$(document).on('ready', function () {
  // Responsive utilities
  function handleResponsiveElements() {
    // Handle responsive tables
    $('table').each(function () {
      if (!$(this).parent().hasClass('table-responsive')) {
        $(this).wrap('<div class="table-responsive"></div>');
      }
    });

    // Handle responsive images
    $('img').each(function () {
      if (!$(this).hasClass('responsive-img')) {
        $(this).addClass('responsive-img');
      }
    });

    // Handle responsive videos
    $('video').each(function () {
      if (!$(this).hasClass('responsive-video')) {
        $(this).addClass('responsive-video');
      }
    });
  }

  // Initialize responsive elements
  handleResponsiveElements();

  // Header is now always white - no scroll effects needed

  /**
   * Mobile menu toggle functionality
   */
  $('.mobile-menu-toggle').on('click', function () {
    const $hamburger = $(this).find('.hamburger');
    const $mobileMenu = $('.mobile-menu');
    const $overlay = $('.mobile-menu-overlay');

    $hamburger.toggleClass('is-active');
    $mobileMenu.toggleClass('is-open');
    $overlay.toggleClass('is-open');
    $('body').toggleClass('mobile-menu-open');

    // Add smooth animation delay
    if ($mobileMenu.hasClass('is-open')) {
      setTimeout(() => {
        $mobileMenu.find('.header-menu li').each(function (index) {
          $(this).css({
            animation: `slideInRight 0.3s ease forwards ${index * 0.1}s`,
            opacity: '0',
          });
          setTimeout(() => {
            $(this).css('opacity', '1');
          }, index * 100 + 300);
        });
      }, 100);
    }
  });

  // Close mobile menu when clicking overlay
  $('.mobile-menu-overlay').on('click', function () {
    $('.hamburger').removeClass('is-active');
    $('.mobile-menu').removeClass('is-open');
    $('.mobile-menu-overlay').removeClass('is-open');
    $('body').removeClass('mobile-menu-open');
  });

  // Close mobile menu on menu item click
  $('.mobile-menu .header-menu a').on('click', function () {
    // Add delay for better UX
    setTimeout(() => {
      $('.hamburger').removeClass('is-active');
      $('.mobile-menu').removeClass('is-open');
      $('.mobile-menu-overlay').removeClass('is-open');
      $('body').removeClass('mobile-menu-open');
    }, 200);
  });

  /**
   * Enhanced submenu functionality for mobile
   */
  $('.mobile-menu .has-dropdown > a').on('click', function (e) {
    e.preventDefault();
    const $parent = $(this).parent();
    const $submenu = $parent.find('.submenu');

    // Toggle submenu
    $submenu.slideToggle(400, 'easeInOutCubic').toggleClass('is-open');
    $parent.toggleClass('submenu-open');

    // Close other submenus
    $('.mobile-menu .has-dropdown').not($parent).removeClass('submenu-open');
    $('.mobile-menu .submenu')
      .not($submenu)
      .slideUp(400, 'easeInOutCubic')
      .removeClass('is-open');
  });

  /**
   * Make elements equal height
   */
  $('.matchHeight').matchHeight();

  /**
   * Initialize testimonials slider
   */
  if ($('#testimonials-slider').length && typeof $.fn.slick === 'function') {
    $('#testimonials-slider').slick({
      dots: true,
      arrows: false,
      infinite: true,
      speed: 600,
      fade: true,
      cssEase: 'ease',
      autoplay: true,
      autoplaySpeed: 4000,
      pauseOnHover: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      rows: 0,
    });
  }

  /**
   * IE Object-fit cover polyfill
   */
  if ($('.of-cover').length) {
    objectFitImages('.of-cover');
  }

  /**
   * Add fancybox to images
   */
  // $('.gallery-item')
  //   .find('a[href$="jpg"], a[href$="png"], a[href$="gif"]')
  //   .attr('rel', 'gallery')
  //   .attr('data-fancybox', 'gallery');
  // $(
  //   '.fancybox, a[rel*="album"], a[href$="jpg"], a[href$="png"], a[href$="gif"]'
  // ).fancybox({
  //   minHeight: 0,
  //   helpers: {
  //     overlay: {
  //       locked: false,
  //     },
  //   },
  // });

  /**
   * Init parallax
   */
  // jarallaxElement();
  // jarallax(document.querySelectorAll('.jarallax'), {
  //   speed: 0.5,
  // });

  /**
   * Detect element appearance in viewport
   */
  // ScrollOut({
  //   offset: function() {
  //     return window.innerHeight - 200;
  //   },
  //   once: true,
  //   onShown: function(element) {
  //     if ($(element).is('.ease-order')) {
  //       $(element)
  //         .find('.ease-order__item')
  //         .each(function(i) {
  //           let $this = $(this);
  //           $(this).attr('data-scroll', '');
  //           window.setTimeout(function() {
  //             $this.attr('data-scroll', 'in');
  //           }, 300 * i);
  //         });
  //     }
  //   },
  // });

  /**
   * Remove placeholder on click
   */
  const removeFieldPlaceholder = () => {
    $('input, textarea').each((i, el) => {
      $(el)
        .data('holder', $(el).attr('placeholder'))
        .on('focusin', () => {
          $(el).attr('placeholder', '');
        })
        .on('focusout', () => {
          $(el).attr('placeholder', $(el).data('holder'));
        });
    });
  };

  removeFieldPlaceholder();

  $(document).on('gform_post_render', () => {
    removeFieldPlaceholder();
  });

  /**
   * Scroll to Gravity Form confirmation message after form submit
   */
  $(document).on('gform_confirmation_loaded', function (event, formId) {
    let $target = $('#gform_confirmation_wrapper_' + formId);
    if ($target.length) {
      $('html, body').animate({ scrollTop: $target.offset().top - 50 }, 500);
      return false;
    }
  });

  /**
   * Hide gravity forms required field message on data input
   */
  $('body').on(
    'change keyup',
    '.gfield input, .gfield textarea, .gfield select',
    function () {
      let $field = $(this).closest('.gfield');
      if ($field.hasClass('gfield_error') && $(this).val().length) {
        $field.find('.validation_message').hide();
      } else if ($field.hasClass('gfield_error') && !$(this).val().length) {
        $field.find('.validation_message').show();
      }
    }
  );

  /**
   * Add `is-active` class to menu-icon button on Responsive menu toggle
   * And remove it on breakpoint change
   */
  $(window)
    .on('toggled.zf.responsiveToggle', function () {
      $('.menu-icon').toggleClass('is-active');
    })
    .on('changed.zf.mediaquery', function () {
      $('.menu-icon').removeClass('is-active');
    });

  /**
   * Close responsive menu on orientation change
   */
  $(window).on('orientationchange', function () {
    $('.hamburger').removeClass('is-active');
    $('.mobile-menu').removeClass('is-open');
    $('.mobile-menu-overlay').removeClass('is-open');
    $('body').removeClass('mobile-menu-open');

    // Re-initialize responsive elements after orientation change
    setTimeout(function () {
      handleResponsiveElements();
    }, 100);
  });

  resizeVideo();
});

/**
 * Scripts which runs after all elements load
 */
$(window).on('load', function () {
  // jQuery code goes here

  let $preloader = $('.preloader');
  if ($preloader.length) {
    $preloader.addClass('preloader--hidden');
  }
});

/**
 * Scripts which runs at window resize
 */
// Responsive utilities
window.handleResponsiveElements = function () {
  // Handle responsive tables
  $('table').each(function () {
    if (!$(this).parent().hasClass('table-responsive')) {
      $(this).wrap('<div class="table-responsive"></div>');
    }
  });

  // Handle responsive images
  $('img').each(function () {
    if (!$(this).hasClass('responsive-img')) {
      $(this).addClass('responsive-img');
    }
  });

  // Handle responsive videos
  $('video').each(function () {
    if (!$(this).hasClass('responsive-video')) {
      $(this).addClass('responsive-video');
    }
  });
};

/**
 * Scripts which runs on scrolling
 */
$(window).on('scroll', function () {
  // jQuery code goes here
});

// Touch device detection and optimization
if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
  $('body').addClass('touch-device');

  // Optimize hover effects for touch devices
  $('.button, a')
    .on('touchstart', function () {
      $(this).addClass('touch-active');
    })
    .on('touchend', function () {
      var $this = $(this);
      setTimeout(function () {
        $this.removeClass('touch-active');
      }, 150);
    });
}

// Viewport height fix for mobile browsers
function setVH() {
  let vh = window.innerHeight * 0.01;
  document.documentElement.style.setProperty('--vh', `${vh}px`);
}

setVH();
window.addEventListener('resize', setVH);
window.addEventListener('orientationchange', function () {
  setTimeout(setVH, 100);
});
