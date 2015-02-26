<?php
/*
  Plugin Name: Who Stick It
  Version: 1.0.2
  Plugin URI:
  Description: Make a sticky menu effect of any part on your website !
  Author: Whodunit
  Author URI: http://www.whodunit.fr/
 */

if (!class_exists('who-stick-it')) {

    class who_stick_it {

        public static function hooks() {
            if (is_admin()) {
                add_action('admin_menu', array(__CLASS__, 'add_settings_panels'));
                add_action('save_post', array(__CLASS__, 'save_post'));
            }
        }

        public static function add_settings_panels() {
            global $tblbords_option;
            add_submenu_page(
                    'options-general.php', __('Who stick it'), __('Who stick it'), 'administrator', 'who-stick-it', array(__CLASS__, 'tblbords')
            );
        }

        public static function tblbords() {
            global $tblbords_option;
            require_once 'template/tblbords.php';
        }

        private static function set_option($option, $value) {
            if (get_option($option) !== FALSE) {
                update_option($option, $value);
            } else {
                add_option($option, $value, '', 'no');
            }
        }

        public static function save_post($post_id) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return $post_id;
            if (isset($_POST['post_type'])) {
                if ('distributeurs' != $_POST['post_type'] || !current_user_can('edit_post', $post_id)) {
                    return $post_id;
                }
            }
            if ($parent_post_id = wp_is_post_revision($post_id)) {
                $post_id = $parent_post_id;
            }
        }

    }

    who_stick_it::hooks();
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links');

function add_action_links($links) {
    $mylinks = array(
        '<a href="' . admin_url('options-general.php?page=who-stick-it') . '">Options</a>',
    );
    return array_merge($links, $mylinks);
}

add_action('wp_head', 'hook_js');

function hook_js() {
    $url = plugins_url();
    wp_enqueue_script('who-stick-it', $url . '/who-stick-it/js/jquery.sticky.js');
}

add_action('wp_footer', 'hook_css');

function hook_css() {
    ?>
    <style>
        .ancre-sticky
        {
            position: relative;top: -150px;
        }
    </style>
    <script>
        jQuery.noConflict();
        (function ($) {
            $(function () {
    <?Php
    $who_stick_it = get_option('who_stick_it');
    $table_who_stick_it = json_decode($who_stick_it, true);
    foreach ($table_who_stick_it as $key => $value) {
        ?>
                    $("<?php echo ($value[2] == 'id') ? '#' : '.' ?><?php echo $value[0] ?>").sticky({topSpacing: <?php echo $value[1] ?>});
        <?php
    }
    ?>
                $(document).on("scroll", onScroll);
                function onScroll(event) {
                    var scrollPos = $(document).scrollTop();
                    $('.menu-about-us-container a, .menu-maximum-diversification-container a,.menu-sustainable-way-container a,menu-antibenchmark-strategy-container a').each(function () {
                        var refElement = $(this).attr("href");
                        var position = $(refElement).offset();
                        if (scrollPos + 300 > position.top && scrollPos < position.top + 300) {
                            $(this).addClass("active");
                        }
                        else {
                            $(this).removeClass("active");
                        }
                    });
                }
            });
            var str = window.location.hash;
            $("body").append("<a href=" + window.location.hash + " id='superhide'></a>");

            var timerId = setInterval(clickancre, 0);
            function clickancre() {
                $("#superhide").trigger("click");
                clearInterval(timerId);
            }
        })(jQuery);
    </script>
    <?php
}
?>