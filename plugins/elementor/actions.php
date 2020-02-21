<?php
function insertOrUpdateMotelInfo($document, $data) {
    if ($data['elements'][0]['elements'][0]['elements'][0]['widgetType'] === 'motel') {
        global $wpdb;
        $post = $document->get_post();

        $motels_table = $wpdb->prefix . 'dragon_motels';
        
        $floors_table = $wpdb->base_prefix . 'dragon_floors';
        $wpdb->delete( $floors_table, array( 'object_type' => $post->post_type, 'object_id' => $post->ID ) );
        
        $motelInfo = $data['elements'][0]['elements'][0]['elements'][0]['settings'];

        $phone = $motelInfo['motel_phone'];
        $author = $motelInfo['motel_author'];
        $latitude = $motelInfo['motel_location_latitude'];
        $longitude = $motelInfo['motel_location_longitude'];
        
        $format = array('%s','%s');
        $existMotel = $wpdb->get_var("SELECT COUNT(*) FROM $motels_table WHERE object_type= '".$post->post_type."' AND object_id= ".$post->ID);
        if ($existMotel > 0) {
            $updateData = array(
                'phone' => $phone,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'author' => $author,
            );

            $whereData = array(
                'object_type' => $post->post_type,
                'object_id' => $post->ID,
            );

            $wpdb->update( $motels_table, $updateData, $whereData);
            
            $floorInfo = $motelInfo['tabs'];
            foreach($floorInfo as $floor){
                $title = $floor['floor_title'];
                $number = $floor['number_room'];
                $price = $floor['price_room'];
                $available = $floor['available_room'];   
                $data = array(
                    'object_type' => $post->post_type,
                    'object_id' => $post->ID,
                    'title' => $title,
                    'number_rooms' => $number,
                    'available_rooms' => $available,
                    'number_price' => $price,
                );

                $format = array('%s','%d');
                $wpdb->insert($floors_table, $data, $format);
            }

        } else {
            $format = array('%s','%s');
            $data = array(
                'object_type' => $post->post_type,
                'object_id' => $post->ID,
                'phone' => $phone,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'author' => $author,
            );

            $wpdb->insert($motels_table, $data, $format);
            $motel_id = $wpdb->insert_id;

            $floorInfo = $motelInfo['tabs'];
            foreach($floorInfo as $floor){
                $title = $floor['floor_title'];
                $number = $floor['number_room'];
                $price = $floor['price_room'];
                $available = $floor['available_room'];   
                $data = array(
                    'object_type' => $post->post_type,
                    'object_id' => $post->ID,
                    'title' => $title,
                    'number_rooms' => $number,
                    'available_rooms' => $available,
                    'number_price' => $price,
                );

                $format = array('%s','%d');
                $wpdb->insert($floors_table, $data);
            }

        }

    }
}

