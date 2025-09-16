<?php
/**
 * Plugin Name: Background Video Controller
 * Description: 縦動画の背景自動再生プラグイン。モバイル全画面、PC中央表示＋背景ぼかし/拡大/黒対応
 * Version: 0.01
 * Author: Netservice
 * Author URI: https://netservice.jp/
 * License: GPLv2 or later
 * Text Domain: background-video-controller
 */

if (!defined('ABSPATH')) {
    exit;
}

class BackgroundVideoController {

    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'of_enqueue_assets'));
        add_shortcode('bvc', array($this, 'of_bvc_shortcode'));
    }

    public function of_enqueue_assets() {
        wp_enqueue_style(
            'of-bvc-style',
            plugin_dir_url(__FILE__) . 'assets/bvc.css',
            array(),
            date('YmdHis')
        );
    }

    public function of_bvc_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => '',
            'poster' => '',
            'pc_width' => '480',
            'bg_mode' => 'blur',
            'class' => ''
        ), $atts);

        if (empty($atts['id'])) {
            return '';
        }

        $video_id = intval($atts['id']);
        $poster_id = intval($atts['poster']);
        $pc_width = intval($atts['pc_width']);
        $bg_mode = in_array($atts['bg_mode'], array('blur', 'zoom', 'black')) ? $atts['bg_mode'] : 'blur';
        $class = sanitize_html_class($atts['class']);

        $video_url = wp_get_attachment_url($video_id);
        if (!$video_url) {
            return '';
        }

        $mp4_url = '';
        $webm_url = '';

        $file_path = get_attached_file($video_id);
        if ($file_path) {
            $path_info = pathinfo($file_path);
            $base_path = $path_info['dirname'] . '/' . $path_info['filename'];
            $base_url = str_replace(basename($file_path), $path_info['filename'], $video_url);

            if (file_exists($base_path . '.mp4')) {
                $mp4_url = $base_url . '.mp4';
            }
            if (file_exists($base_path . '.webm')) {
                $webm_url = $base_url . '.webm';
            }
        }

        if (empty($mp4_url) && empty($webm_url)) {
            $mp4_url = $video_url;
        }

        $poster_url = '';
        if ($poster_id) {
            $poster_url = wp_get_attachment_url($poster_id);
        }

        $wrap_class = 'bvc-wrap bg-' . $bg_mode;
        if (!empty($class)) {
            $wrap_class .= ' ' . $class;
        }

        $style = '';

        $video_attrs = 'autoplay loop muted playsinline aria-hidden="true" tabindex="-1" preload="metadata"';
        if ($poster_url) {
            $video_attrs .= ' poster="' . esc_url($poster_url) . '"';
        }

        $sources = '';
        if ($webm_url) {
            $sources .= '<source src="' . esc_url($webm_url) . '" type="video/webm">';
        }
        if ($mp4_url) {
            $sources .= '<source src="' . esc_url($mp4_url) . '" type="video/mp4">';
        }

        $poster_img = '';
        if ($poster_url) {
            $poster_img = '<img class="bvc-poster" src="' . esc_url($poster_url) . '" alt="">';
        }

        $js = '';
        if ($poster_url) {
            $js = '<script>
            (function(){
                var v=document.querySelector(".bvc-wrap video");
                if(v){
                    v.addEventListener("canplay",function(){
                        v.play().catch(function(){
                            document.querySelector(".bvc-wrap").classList.add("no-autoplay");
                        });
                    });
                }
            })();
            </script>';
        }

        return sprintf(
            '%s<div class="%s">
                <video class="bvc-fg" %s>%s</video>
                <video class="bvc-bg" %s>%s</video>
                %s
            </div>%s',
            $style,
            esc_attr($wrap_class),
            $video_attrs,
            $sources,
            $video_attrs,
            $sources,
            $poster_img,
            $js
        );
    }
}

new BackgroundVideoController();