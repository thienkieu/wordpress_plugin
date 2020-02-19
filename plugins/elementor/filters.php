<?php
function addResponsiveImage($html, $settings) {
    if (isset($settings['image_mobile']) && isset($settings['image_tablet'])) {
        $mobile =  sprintf('<source media="(max-width: 768px)" srcset="%s">',esc_attr( $settings['image_mobile']['url'] ));
        $tablet =  sprintf('<source media="(max-width: 1024px) and (min-width: 768px)" srcset="%s">',esc_attr( $settings['image_tablet']['url'] ));
        $desktop =  sprintf('<source media="(min-width: 1025px)" srcset="%s">',esc_attr( $settings['image']['url'] ));
        return '<picture>'.$tablet.$mobile.$desktop.$html.'</picture>';
    } 

    return $html;
}