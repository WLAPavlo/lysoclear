<?php

namespace theme;

/**
 * Handles lazyloading of images.
 */
class CreateLazyImg
{
    /**
     * Remove inline scripts from the HTML to parse.
     *
     * @param string $html HTML content
     *
     * @return string
     */
    public function ignoreScripts($html)
    {
        return preg_replace('/<script\b(?:[^>]*)>(?:.+)?<\/script>/Umsi', '', $html);
    }

    /**
     * Remove noscript tags from the HTML to parse.
     *
     * @param string $html HTML content
     *
     * @return string
     */
    public function ignoreNoscripts($html)
    {
        return preg_replace('#<noscript>(?:.+)</noscript>#Umsi', '', $html);
    }

    /**
     * Finds the images to be lazyloaded and call the callback method to replace them.
     *
     * @param string $html Original HTML
     * @param string $buffer Content to parse
     *
     * @return string
     */
    public function lazyloadImages($html, $buffer)
    {
        $clean_buffer = preg_replace('/<script\b(?:[^>]*)>(?:.+)?<\/script>/Umsi', '', $html);
        $clean_buffer = preg_replace('#<noscript>(?:.+)</noscript>#Umsi', '', $clean_buffer);
        if (!preg_match_all('#<img(?<atts>\s.+)\s?/?>#iUs', $clean_buffer, $images, PREG_SET_ORDER)) {
            return $html;
        }

        $images = array_unique($images, SORT_REGULAR);

        foreach ($images as $image) {
            $image = $this->canLazyload($image);

            if (!$image) {
                continue;
            }

            $image_lazyload = $this->replaceImage($image);
            $image_lazyload .= $this->noscript($image[0]);
            $html = str_replace($image[0], $image_lazyload, $html);

            unset($image_lazyload);
        }

        return $html;
    }

    /**
     * Applies lazyload on background images defined in style attributes.
     *
     * @param string $html Original HTML
     * @param string $buffer Content to parse
     *
     * @return string
     */
    public function lazyloadBackgroundImages($html, $buffer)
    {
        if (!preg_match_all(
            '#<(?<tag>div|section|article|a|figure|li|span|header|footer)\s+(?<before>[^>]+[\'"\s])?style\s*=\s*([\'"])(?<styles>.*?)\3(?<after>[^>]*)>#is',
            $buffer,
            $elements,
            PREG_SET_ORDER
        )) {
            return $html;
        }

        foreach ($elements as $element) {
            if ($this->isExcluded($element['before'] . $element['after'], $this->getExcludedAttributes())) {
                continue;
            }

            if (!preg_match(
                '#background-image\s*:\s*(?<attr>\s*url\s*\((?<url>[^)]+)\))\s*;?#is',
                $element['styles'],
                $url
            )) {
                continue;
            }

            $url['url'] = esc_url(
                trim(strip_tags(html_entity_decode($url['url'], ENT_QUOTES | ENT_HTML5)), '\'" ')
            );

            if ($this->isExcluded($url['url'], $this->getExcludedSrc())) {
                continue;
            }

            $lazy_bg = $this->addLazyCLass($element[0]);
            $lazy_bg = str_replace($url[0], '', $lazy_bg);
            $lazy_bg = str_replace(
                '<' . $element['tag'],
                '<' . $element['tag'] . ' data-bg="' . esc_attr($url['url']) . '"',
                $lazy_bg
            );

            $html = str_replace($element[0], $lazy_bg, $html);
            unset($lazy_bg);
        }

        return $html;
    }

    /**
     * Applies lazyload on picture elements found in the HTML.
     *
     * @param string $html Original HTML
     * @param string $buffer Content to parse
     *
     * @return string HTML with lazyloaded pictures
     */
    public function lazyloadPictures($html, $buffer)
    {
        if (!preg_match_all('#<picture(?:.*)?>(?<sources>.*)</picture>#iUs', $buffer, $pictures, PREG_SET_ORDER)) {
            return $html;
        }

        $pictures = array_unique($pictures, SORT_REGULAR);
        $excluded = array_merge($this->getExcludedAttributes(), $this->getExcludedSrc());

        foreach ($pictures as $picture) {
            if ($this->isExcluded($picture[0], $excluded)) {
                continue;
            }

            if (preg_match_all('#<source(?<atts>\s.+)>#iUs', $picture['sources'], $sources, PREG_SET_ORDER)) {
                $sources = array_unique($sources, SORT_REGULAR);

                $lazy_sources = 0;

                foreach ($sources as $source) {
                    $lazyload_srcset = preg_replace('/([\s"\'])srcset/i', '\1data-lazy-srcset', $source[0]);
                    $html = str_replace($source[0], $lazyload_srcset, $html);

                    unset($lazyload_srcset);
                    ++$lazy_sources;
                }
            }

            if (isset($lazy_sources) && 0 === $lazy_sources) {
                continue;
            }

            if (!preg_match('#<img(?<atts>\s.+)\s?/?>#iUs', $picture[0], $img)) {
                continue;
            }

            $img = $this->canLazyload($img);

            if (!$img) {
                continue;
            }

            $img_lazy = $this->replaceImage($img);
            $img_lazy .= $this->noscript($img[0]);
            $safe_img = str_replace('/', '\/', preg_quote($img[0], '#'));
            $html = preg_replace('#<noscript[^>]*>.*' . $safe_img . '.*<\/noscript>(*SKIP)(*FAIL)|' . $safe_img . '#iU', $img_lazy, $html);

            unset($img_lazy);
        }

        return $html;
    }

    /**
     * Checks if the provided string matches with the provided excluded patterns.
     *
     * @param string $string The string to check
     * @param array $excluded_values Patterns to match against
     *
     * @return bool true if the string matches with any of the excluded patterns, false otherwise
     */
    public function isExcluded($string, $excluded_values)
    {
        if (!is_array($excluded_values)) {
            (array) $excluded_values;
        }

        if (empty($excluded_values)) {
            return false;
        }

        foreach ($excluded_values as $excluded_value) {
            if (false !== strpos($string, $excluded_value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the list of excluded attributes.
     *
     * @return array An array of excluded attributes
     */
    public function getExcludedAttributes()
    {
        /**
         * Filters the attributes used to prevent lazylad from being applied.
         *
         * @param array $excluded_attributes an array of excluded attributes
         *
         * @author Remy Perona
         *
         * @since 1.0
         */
        return [
            'data-src=',
            'data-no-lazy=',
            'data-lazy-original=',
            'data-lazy-src=',
            'data-lazysrc=',
            'data-lazyload=',
            'data-bgposition=',
            'data-envira-src=',
            'fullurl=',
            'lazy-slider-img=',
            'data-srcset=',
            'class="ls-l',
            'class="ls-bg',
            'class="no-lazyload',
            'soliloquy-image',
            'loading="eager"',
            'swatch-img',
            'data-height-percentage',
            'data-large_image',
            'avia-bg-style-fixed',
            'data-skip-lazy',
            'skip-lazy',
        ];
    }

    /**
     * Returns the list of excluded src.
     *
     * @return array An array of excluded src
     */
    public function getExcludedSrc()
    {
        /**
         * Filters the src used to prevent lazylad from being applied.
         *
         * @param array $excluded_src An array of excluded src
         *
         * @author Remy Perona
         *
         * @since 1.0
         */
        return [
            '/wpcf7_captcha/',
            'timthumb.php?src',
            'woocommerce/assets/images/placeholder.png',
        ];
    }

    /**
     * Finds patterns matching smiley and call the callback method to replace them with the image.
     *
     * @param string $text Content to search in
     *
     * @return string Content with smileys replaced by images
     */
    public function convertSmilies($text)
    {
        global $wp_smiliessearch;

        if (!get_option('use_smilies') || empty($wp_smiliessearch)) {
            return $text;
        }

        $output = '';
        // HTML loop taken from texturize function, could possible be consolidated.
        // capture the tags as well as in between.
        $textarr = preg_split('/(<.*>)/U', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $stop = count($textarr); // loop stuff.

        // Ignore proessing of specific tags.
        $tags_to_ignore = 'code|pre|style|script|textarea';
        $ignore_block_element = '';

        for ($i = 0; $i < $stop; ++$i) {
            $content = $textarr[$i];

            // If we're in an ignore block, wait until we find its closing tag.
            if ('' === $ignore_block_element && preg_match('/^<(' . $tags_to_ignore . ')>/', $content, $matches)) {
                $ignore_block_element = $matches[1];
            }

            // If it's not a tag and not in ignore block.
            if ('' === $ignore_block_element && strlen($content) > 0 && '<' !== $content[0]) {
                $content = preg_replace_callback($wp_smiliessearch, [$this, 'translateSmiley'], $content);
            }

            // did we exit ignore block.
            if ('' !== $ignore_block_element && '</' . $ignore_block_element . '>' === $content) {
                $ignore_block_element = '';
            }

            $output .= $content;
        }

        return $output;
    }

    /**
     * Returns the placeholder for the src attribute.
     *
     * @param int $width Width of the placeholder image. Default 0
     * @param int $height Height of the placeholder image. Default 0
     *
     * @return string
     *
     * @author Remy Perona
     *
     * @since 1.2
     */
    public function getPlaceholder($width = 0, $height = 0)
    {
        $width = 0 === $width ? 0 : absint($width);
        $height = 0 === $height ? 0 : absint($height);

        return str_replace(
            ' ',
            '%20',
            "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 {$width} {$height}'%3E%3C/svg%3E"
        );
    }

    /**
     * Add the identifier class to the element.
     *
     * @param string $element Element to add the class to
     *
     * @return string Element with the class added
     */
    private function addLazyClass($element)
    {
        $class = $this->getClasses($element);
        if (empty($class)) {
            return preg_replace('#<(img|div|section|article|a|figure|li|span|header|footer)([^>]*)>#is', '<\1 class="pre-lazyload"\2>', $element);
        }

        if (empty($class['attribute']) || empty($class['classes'])) {
            return str_replace($class['attribute'], 'class="pre-lazyload"', $element);
        }

        $quotes = $this->getAttributeQuotes($class['classes']);
        $classes = $this->trimOuterQuotes($class['classes'], $quotes);

        if (empty($classes)) {
            return str_replace($class['attribute'], 'class="pre-lazyload"', $element);
        }

        $classes .= ' pre-lazyload';

        return str_replace(
            $class['attribute'],
            'class=' . $this->normalizeClasses($classes, $quotes),
            $element
        );
    }

    /**
     * Gets the attribute value's outer quotation mark, if one exists, i.e. " or '.
     *
     * @param string $attribute_value The target attribute's value
     *
     * @return bool|string Quotation character; else false when no quotation mark
     */
    private function getAttributeQuotes($attribute_value)
    {
        $attribute_value = trim($attribute_value);
        $first_char = $attribute_value[0];

        if ('"' === $first_char || "'" === $first_char) {
            return $first_char;
        }

        return false;
    }

    /**
     * Gets the class attribute and values from the given element, if it exists.
     *
     * @param string $element Given HTML element to extract classes from
     *
     * @return bool|string[] {
     *
     * @type string $attribute Class attribute and value, e.g. class="value"
     * @type string $classes String of class attribute's value(s)
     * }
     */
    private function getClasses($element)
    {
        if (!preg_match('#class\s*=\s*(?<classes>["\'].*?["\']|[^\s]+)#is', $element, $class)) {
            return false;
        }

        if (empty($class)) {
            return false;
        }

        if (!isset($class['classes'])) {
            return false;
        }

        return [
            'attribute' => $class[0],
            'classes' => $class['classes'],
        ];
    }

    /**
     * Removes outer single or double quotations.
     *
     * @param string $string The string to strip quotes from
     * @param string $quotes The outer quotes to remove
     *
     * @return string The string without quotes
     */
    private function trimOuterQuotes($string, $quotes)
    {
        $string = trim($string);
        if (empty($string)) {
            return '';
        }

        if (empty($quotes)) {
            return $string;
        }

        $string = ltrim($string, $quotes);
        $string = rtrim($string, $quotes);

        return trim($string);
    }

    /**
     * Normalizes the class attribute values to ensure well-formed.
     *
     * @param string $classes The string of class attribute value(s)
     * @param bool|string $quotes (optional) Quotation mark to wrap around the classes
     *
     * @return string Well-formed class attributes
     */
    private function normalizeClasses($classes, $quotes = '"')
    {
        $array_of_classes = $this->stringToArray($classes);
        $classes = implode(' ', $array_of_classes);

        if (false === $quotes) {
            $quotes = '"';
        }

        return $quotes . $classes . $quotes;
    }

    /**
     * Converts the given string into an array of strings.
     *
     * Note:
     *  1. Removes empties.
     *  2. Trims each string.
     *
     * @param string $string The target string to convert
     * @param string $delimiter (optional) Default: ' ' empty string
     *
     * @return array An array of trimmed strings
     */
    private function stringToArray($string, $delimiter = ' ')
    {
        if (empty($string)) {
            return [];
        }

        $array = explode($delimiter, $string);
        $array = array_map('trim', $array);

        // Remove empties.
        return array_filter($array);
    }

    /**
     * Checks if the image can be lazyloaded.
     *
     * @param array $image array of image data coming from Regex
     *
     * @return array|bool
     */
    private function canLazyload($image)
    {
        if ($this->isExcluded($image['atts'], $this->getExcludedAttributes())) {
            return false;
        }

        // Given the previous regex pattern, $image['atts'] starts with a whitespace character.
        if (!preg_match('@\ssrc\s*=\s*(\'|")(?<src>.*)\1@iUs', $image['atts'], $atts)) {
            return false;
        }

        $image['src'] = trim($atts['src']);

        if ('' === $image['src']) {
            return false;
        }

        if ($this->isExcluded($image['src'], $this->getExcludedSrc())) {
            return false;
        }

        // Don't apply LazyLoad on images from WP Retina x2.
        if (function_exists('wr2x_picture_rewrite')
            && function_exists('wr2x_get_retina')
            && function_exists('wr2x_get_pathinfo_from_image_src')) {
            if (wr2x_get_retina(trailingslashit(ABSPATH) . wr2x_get_pathinfo_from_image_src(trim($image['src'], '"')))) {
                return false;
            }
        }

        return $image;
    }

    /**
     * Replaces the original image by the lazyload one.
     *
     * @param array $image array of matches elements
     *
     * @return string HTML with lazyloaded images
     */
    private function replaceImage($image)
    {
        $width = 1;
        $height = 1;

        if (preg_match('@\swidth\s*=\s*(\'|")(?<width>.*)\1@iUs', $image['atts'], $atts)) {
            $width = absint($atts['width']);
        }

        if (preg_match('@\sheight\s*=\s*(\'|")(?<height>.*)\1@iUs', $image['atts'], $atts)) {
            $height = absint($atts['height']);
        }

        $placeholder_atts = preg_replace(
            '@\ssrc\s*=\s*(\'|")(?<src>.*)\1@iUs',
            ' src=$1' . $this->getPlaceholder($width, $height) . '$1',
            $image['atts']
        );

        $image_lazyload = str_replace(
            $image['atts'],
            $placeholder_atts . ' data-lazy-src="' . esc_url($image['src']) . '"',
            $image[0]
        );

        $image_lazyload = preg_replace(
            '/[\s|"|\'](srcset)\s*=\s*("|\')([^"|\']+)\2/i',
            ' data-lazy-$1=$2$3$2',
            $image_lazyload
        );

        return preg_replace(
            '/[\s|"|\'](sizes)\s*=\s*("|\')([^"|\']+)\2/i',
            ' data-lazy-$1=$2$3$2',
            $image_lazyload
        );
    }

    /**
     * Returns the HTML tag wrapped inside noscript tags.
     *
     * @param string $element Element to wrap
     *
     * @return string
     */
    private function noscript($element)
    {
        return '<noscript>' . $element . '</noscript>';
    }

    /**
     * Replace matches by smiley image, lazyloaded.
     *
     * @param array $matches Array of matches
     *
     * @return string
     */
    private function translateSmiley($matches)
    {
        global $wpsmiliestrans;

        if (0 === count($matches)) {
            return '';
        }

        $smiley = trim(reset($matches));
        $img = $wpsmiliestrans[$smiley];

        $matches = [];
        $ext = preg_match('/\.([^.]+)$/', $img, $matches) ? strtolower($matches[1]) : false;
        $image_exts = ['jpg', 'jpeg', 'jpe', 'gif', 'png'];

        // Don't convert smilies that aren't images - they're probably emoji.
        if (!in_array($ext, $image_exts, true)) {
            return $img;
        }

        /**
         * Filter the Smiley image URL before it's used in the image element.
         *
         * @param string $smiley_url URL for the smiley image
         * @param string $img Filename for the smiley image
         * @param string $site_url Site URL, as returned by site_url()
         *
         * @since 2.9.0
         */
        $src_url = apply_filters('smilies_src', includes_url("images/smilies/{$img}"), $img, site_url());

        // Don't LazyLoad if process is stopped for these reasons.
        if (is_feed() || is_preview()) {
            return sprintf(
                ' <img src="%s" alt="%s" class="wp-smiley" /> ',
                esc_url($src_url),
                esc_attr($smiley)
            );
        }

        return sprintf(
            ' <img src="%s" data-lazy-src="%s" alt="%s" class="wp-smiley" /> ',
            $this->getPlaceholder(),
            esc_url($src_url),
            esc_attr($smiley)
        );
    }
}
