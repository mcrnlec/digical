<?php
/**
 * GitHub Plugin Updater
 * 
 * Enables WordPress to check for plugin updates from GitHub releases
 * 
 * @package DigiCal
 * @version 1.0
 */

if (!defined('ABSPATH')) exit;

class DigiCal_GitHub_Updater {
    private $plugin_file;
    private $github_owner = 'YOUR_GITHUB_USERNAME';
    private $github_repo = 'digi-cal';
    private $github_api_url = 'https://api.github.com/repos';
    private $cache_key = 'digical_github_release_info';
    private $cache_expiration = 3600; // 1 hour

    public function __construct($plugin_file) {
        $this->plugin_file = $plugin_file;
        
        // Hook into WordPress update checks
        add_filter('pre_set_site_transient_update_plugins', [$this, 'check_for_updates']);
        add_filter('plugins_api', [$this, 'plugin_info'], 20, 3);
        
        // Add settings to specify custom GitHub owner
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('digical_settings', 'digical_github_owner');
    }

    /**
     * Get GitHub owner from settings or use default
     */
    public function get_github_owner() {
        $owner = get_option('digical_github_owner');
        return $owner ?: $this->github_owner;
    }

    /**
     * Check for updates from GitHub
     */
    public function check_for_updates($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        $plugin_slug = plugin_basename($this->plugin_file);
        
        // Get release info from GitHub
        $release_info = $this->get_github_release_info();
        
        if (!$release_info || is_wp_error($release_info)) {
            return $transient;
        }

        // Get current plugin version
        $current_version = $transient->checked[$plugin_slug];
        $github_version = $release_info['tag_name'];

        // Remove 'v' prefix if present
        $github_version = ltrim($github_version, 'v');
        $current_version = ltrim($current_version, 'v');

        // Compare versions
        if (version_compare($github_version, $current_version, '>')) {
            $transient->response[$plugin_slug] = (object) [
                'slug' => 'digi-cal',
                'plugin' => $plugin_slug,
                'new_version' => $github_version,
                'tested' => '6.4',
                'requires' => '5.0',
                'requires_php' => '7.0',
                'package' => $release_info['download_url'],
                'url' => $release_info['repository_url'],
                'plugin_name' => 'DigiCal',
                'author' => 'DigiCal',
            ];
        }

        return $transient;
    }

    /**
     * Provide plugin information for the update modal
     */
    public function plugin_info($result, $action, $args) {
        if ($action !== 'plugin_information') {
            return $result;
        }

        if ($args->slug !== 'digi-cal') {
            return $result;
        }

        $release_info = $this->get_github_release_info();

        if (!$release_info || is_wp_error($release_info)) {
            return $result;
        }

        $github_version = ltrim($release_info['tag_name'], 'v');

        $plugin_info = (object) [
            'name' => 'DigiCal',
            'slug' => 'digi-cal',
            'plugin' => 'digi-cal/digi-cal.php',
            'version' => $github_version,
            'author' => 'DigiCal Contributors',
            'author_profile' => $release_info['author_url'],
            'requires' => '5.0',
            'requires_php' => '7.0',
            'tested' => '6.4',
            'requires_plugins' => [],
            'last_updated' => $release_info['published_at'],
            'download_link' => $release_info['download_url'],
            'homepage' => $release_info['repository_url'],
            'description' => $release_info['body'] ?: 'Conference calendar management system with backend management (DB-backed).',
            'sections' => [
                'description' => $release_info['body'] ?: 'A professional WordPress plugin for managing conference and event calendars with comprehensive backend tools.',
                'installation' => 'Download the plugin and upload it to your WordPress site. Then activate it from the Plugins page.',
                'changelog' => $release_info['body'] ?: 'See GitHub releases for detailed changelog.',
            ],
            'banners' => [
                'low' => 'https://raw.githubusercontent.com/' . $this->get_github_owner() . '/' . $this->github_repo . '/main/assets/banner-772x250.jpg',
                'high' => 'https://raw.githubusercontent.com/' . $this->get_github_owner() . '/' . $this->github_repo . '/main/assets/banner-1544x500.jpg',
            ],
            'icons' => [
                '1x' => 'https://raw.githubusercontent.com/' . $this->get_github_owner() . '/' . $this->github_repo . '/main/assets/icon-128x128.jpg',
                '2x' => 'https://raw.githubusercontent.com/' . $this->get_github_owner() . '/' . $this->github_repo . '/main/assets/icon-256x256.jpg',
            ],
        ];

        return $plugin_info;
    }

    /**
     * Get release information from GitHub
     */
    private function get_github_release_info() {
        // Try to get from cache first
        $cache = get_transient($this->cache_key);
        if ($cache !== false) {
            return $cache;
        }

        $github_owner = $this->get_github_owner();
        $api_url = "{$this->github_api_url}/{$github_owner}/{$this->github_repo}/releases/latest";

        // Make request to GitHub API
        $response = wp_remote_get($api_url, [
            'sslverify' => true,
            'timeout' => 10,
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; DigiCal',
            ],
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data['tag_name'])) {
            return false;
        }

        // Find the ZIP asset
        $download_url = null;
        if (isset($data['assets'])) {
            foreach ($data['assets'] as $asset) {
                if (strpos($asset['name'], 'digi-cal') !== false && strpos($asset['name'], '.zip') !== false) {
                    $download_url = $asset['browser_download_url'];
                    break;
                }
            }
        }

        // Fallback: use zipball URL
        if (!$download_url) {
            $download_url = $data['zipball_url'];
        }

        $release_info = [
            'tag_name' => $data['tag_name'],
            'download_url' => $download_url,
            'repository_url' => $data['html_url'],
            'author_url' => $data['user']['html_url'] ?? '#',
            'published_at' => $data['published_at'],
            'body' => $data['body'] ?? '',
        ];

        // Cache the result
        set_transient($this->cache_key, $release_info, $this->cache_expiration);

        return $release_info;
    }

    /**
     * Clear cache when manually checking for updates
     */
    public function clear_cache() {
        delete_transient($this->cache_key);
    }
}

// Initialize the updater
if (is_admin()) {
    new DigiCal_GitHub_Updater(__FILE__);
}
