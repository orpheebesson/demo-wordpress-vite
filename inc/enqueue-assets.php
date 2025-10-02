<?php

function registerCustomAssets() {
    $vite_server = 'http://localhost:5173';
    $response = wp_remote_get($vite_server);
    $is_dev = !is_wp_error($response);

    if ($is_dev) {
        wp_enqueue_script_module('vite-client', $vite_server . '/@vite/client', [], null);
        wp_enqueue_script_module('my-theme', $vite_server . '/src/js/main.js', [], null);
    } else {
        $manifest_path = get_theme_file_path('dist/.vite/manifest.json');

        if (!file_exists($manifest_path)) {
            return;
        }

        $manifest = json_decode(file_get_contents($manifest_path), true);

        if (isset($manifest['src/js/main.js'])) {
            $js_file = $manifest['src/js/main.js']['file'];
            wp_enqueue_script('my-theme', get_template_directory_uri() . '/dist/' . $js_file, [], null,
                [
                    'strategy'  => 'defer',
                    'in_footer' => true,
                ]
            );

            $css_file = $manifest['src/js/main.js']['css'][0];
            if (isset($css_file)) {
                wp_enqueue_style('my-theme', get_template_directory_uri() . '/dist/' . $css_file, [], null);
            }
        }
    }
}

add_action('wp_enqueue_scripts', 'registerCustomAssets');