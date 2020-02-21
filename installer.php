<?php
namespace Dragon;

class Installer {
    /**
     * Create Dragon database tables
     */
    public static function create_tables() {

        global $wpdb;

        // charset.
        $charset_collate = ! empty( $wpdb->charset ) ? "DEFAULT CHARACTER SET {$wpdb->charset}" : 'DEFAULT CHARACTER SET utf8';

        // collation.
        $charset_collate .= ! empty( $wpdb->collate ) ? " COLLATE {$wpdb->collate}" : ' COLLATE utf8_general_ci';

        // forms table name.
        $motels_table = $wpdb->prefix . 'dragon_motels';

        // check if table exists already.
        $table_exists = $wpdb->get_results( "SHOW TABLES LIKE '{$motels_table}'", ARRAY_A ); // WPCS: db call ok, cache ok, unprepared SQL ok.

        // if form table not exists create it.
        if ( 0 === count( $table_exists ) ) {

            // generate table sql.
            $sql = "CREATE TABLE $motels_table (
            ID INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
            object_type VARCHAR(20) NOT NULL,
            object_id BIGINT(20) UNSIGNED NOT NULL default 0,
            phone VARCHAR( 50 ) NOT NULL,
            latitude VARCHAR( 50 ) NOT NULL,
            longitude VARCHAR( 50 ) NOT NULL,
            author VARCHAR( 100 ) NOT NULL,
            PRIMARY KEY ID (ID)
        ) $charset_collate;";
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            // create database table.
            dbDelta( $sql );
        }

        // locations table name.
        $floors_table = $wpdb->base_prefix . 'dragon_floors';

        // check if table already exists.
        $table_exists = $wpdb->get_results( "SHOW TABLES LIKE '{$floors_table}'", ARRAY_A ); // WPCS: db call ok, cache ok, unprepared SQL ok.

        // create table if not already exists.
        if ( 0 === count( $table_exists ) ) {

            // generate table sql.
            $sql = "CREATE TABLE $floors_table (
            ID INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
            object_type VARCHAR(20) NOT NULL,
            object_id BIGINT(20) UNSIGNED NOT NULL default 0,  
            title VARCHAR( 200 ),
            number_rooms TINYINT NOT NULL default 0,
            available_rooms TINYINT NOT NULL default 0,
            number_price INT(11) NOT NULL default 0,
            PRIMARY KEY ID (ID)
        ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';

            // create database table.
            dbDelta( $sql );
        }

    }

    public static function delete_tables() {
        global $wpdb;
        // forms table name.
        $motels_table = $wpdb->prefix . 'dragon_motels';
        $wpdb->query("DROP TABLE IF EXISTS {$motels_table}");

        $floors_table = $wpdb->base_prefix . 'dragon_floors';
        $wpdb->query("DROP TABLE IF EXISTS {$floors_table}");        
    }
}
