<?php
/**
 * Plugin Name: Zoo Repairs - Starlink Standalone Landing Page
 * Description: Serves the compiled standalone Starlink landing page for Page ID 76959 with zero theme overhead and safe fallback.
 * Author: Zoo Repairs Integration Team
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

add_filter('template_include', function ($template) {
    // Only intercept target WordPress Page ID 76959 (Starlink Installation Brisbane Southside)
    if (is_page(76959)) {
        $html_file = WP_CONTENT_DIR . '/uploads/zoo-starlink/starlink-installation-brisbane-southside.html';

        // Safe fallback: HTML file must exist, be readable, and be non-empty (minimum 1000 bytes)
        if (!file_exists($html_file) || !is_readable($html_file) || filesize($html_file) < 1000) {
            return $template;
        }

        // Safe fallback: If headers have already been sent, safe clean interception cannot be guaranteed
        if (headers_sent()) {
            return $template;
        }

        // Safe fallback: Inspect all active output buffers.
        // If any buffer contains preceding output that cannot safely be discarded, do not serve standalone HTML.
        $safe_to_clean = true;
        for ($level = ob_get_level(); $level > 0; $level--) {
            $status = ob_get_status($level);
            $has_content = isset($status['buffer_used']) ? $status['buffer_used'] > 0 : (ob_get_length() > 0);
            $is_cleanable = isset($status['flags']) ? ($status['flags'] & PHP_OUTPUT_HANDLER_CLEANABLE) : true;

            if ($has_content && !$is_cleanable) {
                $safe_to_clean = false;
                break;
            }
        }

        if (!$safe_to_clean) {
            return $template;
        }

        // Safely discard all cleanable output buffers
        while (ob_get_level() > 0) {
            $status = ob_get_status();
            if (isset($status['flags']) && !($status['flags'] & PHP_OUTPUT_HANDLER_CLEANABLE)) {
                break;
            }
            if (!@ob_end_clean()) {
                // If clearing failed and content remains in buffer, abort and fall back
                if (ob_get_length() > 0) {
                    return $template;
                }
                break;
            }
        }

        // Verify headers were not sent during buffer flushing
        if (headers_sent()) {
            return $template;
        }

        // Send appropriate HTTP headers
        status_header(200);
        header('Content-Type: text/html; charset=UTF-8');
        header('Content-Length: ' . filesize($html_file));

        // Reinforce search indexing if site is public
        if ((int) get_option('blog_public', 1) === 1) {
            header('X-Robots-Tag: index, follow', true);
        }

        // Stream the compiled Astro HTML directly and terminate execution
        readfile($html_file);
        exit;
    }

    // Normal WordPress template fallback for all other requests
    return $template;
}, 999);
