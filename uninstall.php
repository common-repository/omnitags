<?php
if (!defined( 'WP_UNINSTALL_PLUGIN')) {
    exit();
}

function omnitags_uninstall()
{
    global $wpdb;
    $tb_omnitags_config = $wpdb->prefix.'omnitags_config';

    if ($wpdb->get_var("SHOW TABLES LIKE '$tb_omnitags_config'") == $tb_omnitags_config) {
        $sql = "DROP TABLE `$tb_omnitags_config`";
        $wpdb->query($sql);
    }
}

omnitags_uninstall();