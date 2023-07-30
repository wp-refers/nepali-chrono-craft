<?php

namespace WPRefers\NepaliChronoCraft;

use WPRefers\NepaliChronoCraft\Lib\Data;
use WPRefers\NepaliChronoCraft\Lib\NepaliDate;
use WPRefers\NepaliChronoCraft\Lib\PhpCarbon;

class NepaliChronoCraft
{

    private $dateFormat = 'Y-m-d';
    private $timeFormat = 'H:i:s';

    public function __construct()
    {
        // Add Javascript and CSS for front-end display
        add_action('wp_enqueue_scripts', array( $this, 'enqueue' ));

        // Add the shortcode for front-end form display
        add_action( 'init', array( $this, 'add_shortcode' ) );
        // Add ajax function that will receive the call back for logged in users
        add_action( 'wp_ajax_nepali_chrono_craft_xhr_action', array( $this, 'nepali_chrono_craft_xhr_action') );
        // Add ajax function that will receive the call back for guest or not logged in users
        add_action( 'wp_ajax_nopriv_nepali_chrono_craft_xhr_action', array( $this, 'nepali_chrono_craft_xhr_action') );

        // Add shortcode support for widgets
        add_filter('widget_text', 'do_shortcode');
    }

    public function enqueue()
    {
        wp_enqueue_style('nepali-chrono-craft-style', plugins_url('css/nepali-chrono-craft.css', __DIR__));
        wp_enqueue_script('nepali-chrono-craft-script', plugins_url('js/nepali-chrono-craft.js', __DIR__), array('jquery'), '1.0', true);
        wp_localize_script('nepali-chrono-craft-script', "nepali_chrono_craft_data", array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce("_wpnonce")
        ));
    }

    public function nepali_chrono_craft_xhr_action()
    {
        check_ajax_referer( '_wpnonce', 'security');

        $year = sanitize_text_field($_POST['year']);
        $domain = sanitize_text_field($_POST['month']);
        $day = sanitize_text_field($_POST['day']);
        $convertTo = sanitize_text_field($_POST['convertTo']);

        wp_send_json(
            $this->nepali_chrono_craft_resolve_date(
                $year, $domain, $day, $convertTo
            )
        );
        wp_die();
    }

    public function nepali_chrono_craft_resolve_date( $year, $month, $day, $convertTo )
    {
        $dateConverter = (new NepaliDate());

        try {
            $response = $this->dateInFormat($convertTo === 'convert-bs-to-ad' ? $dateConverter->convertBsToAd($year, $month, $day) : $dateConverter->convertAdToBs($year, $month, $day));
        } catch (\Exception $exception) {
            $response = 'Invalid Date';
        }

        return array(
            'body'  => $response,
        );
    }

    public function add_shortcode()
    {
        add_shortcode('nepali-chrono-craft', array( $this, 'shortcode' ));
    }

    public function shortcode( $atts )
    {
        // extract the attributes into variables
        extract(shortcode_atts(array(
            'title' => 'Nepali Chrono Craft',
            'time'  => true,
            'format' => 'Y-m-d',
            'timeFormat' => 'H:i:s'
        ), $atts));

        $this->dateFormat = $format;
        $this->timeFormat = $timeFormat;

        $file_path = dirname(__FILE__) . '/templates/template-nepali-chrono-craft.php';

        ob_start();

        include($file_path);

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    private function dateInFormat( array $dateArgs )
    {
        return implode(' ', array(
            $dateArgs['y'],
            $dateArgs['M'],
            $dateArgs['d'],
            $dateArgs['l'],
        ));
    }

}