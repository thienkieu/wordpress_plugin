<?php
namespace Dragon\Plugins\Elementor;

use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Core\Schemes;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor google maps widget.
 *
 * Elementor widget that displays an embedded google map.
 *
 * @since 1.0.0
 */
class Widget_Custom_Google_Maps extends Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
  
		wp_register_script( 'custom-google-maps-handle', '/wp-content/plugins/dragon/assets/js/WidgetCustomGoogleMaps.js', [ 'elementor-frontend' ], '1.0.0', true );		
		wp_register_script( 'google-map-script', '//maps.googleapis.com/maps/api/js?key=AIzaSyBVSI5QWn2nFvM1cGgYhoJyVpMeKHGxVzg&callback=onGoogleMapInit', '', '1.0.0', true );
	 }
  
	public function get_script_depends() {
	   return [ 'custom-google-maps-handle', 'google-map-script' ];
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve google maps widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'custom_google_maps';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve google maps widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Custom Google Maps', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve google maps widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-google-maps';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the google maps widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'basic' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'google', 'map', 'embed', 'location' ];
	}

	/**
	 * Register google maps widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_map',
			[
				'label' => __( 'Map', 'elementor' ),
			]
		);

		$default_address = __( 'London Eye, London, United Kingdom', 'elementor' );
		$this->add_control(
			'address',
			[
				'label' => __( 'Location', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
					],
				],
				'placeholder' => $default_address,
				'default' => $default_address,
				'label_block' => true,
			]
		);

		$this->add_control(
			'zoom',
			[
				'label' => __( 'Zoom', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 400,
				],
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 1440,
					],
				],
				'selectors' => [
					'{{WRAPPER}} iframe' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'location_latitude',
			[
				'label' => __( 'Latitude', 'elementor' ),
				'type' => Controls_Manager::TEXT,				
				'label_block' => false,
			]
        );

        $this->add_control(
			'location_longitude',
			[
				'label' => __( 'Longitude', 'elementor' ),
				'type' => Controls_Manager::TEXT,				
				'label_block' => false,
			]
		);
		
		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_map_style',
			[
				'label' => __( 'Map', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'map_filter' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} iframe',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}}:hover iframe',
			]
		);

		$this->add_control(
			'hover_transition',
			[
				'label' => __( 'Transition Duration', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} iframe' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render google maps widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['address'] ) ) {
			return;
		}

		if ( 0 === absint( $settings['zoom']['size'] ) ) {
			$settings['zoom']['size'] = 10;
		}

		/*printf(
			'<div class="elementor-custom-embed"><iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near" aria-label="%s"></iframe></div>',
			rawurlencode( $settings['address'] ),
			absint( $settings['zoom']['size'] ),
			esc_attr( $settings['address'] )
		);*/

		echo '<div class="dragon-map" style="height:'.$settings['height']['size'].'px"></div>';
		
	}

	/**
	 * Render google maps widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
		function addHeaderBehavior(behaviors) {
    abc = function () {
        class CustomeFilter extends Marionette.Behavior  {
            editing = false;
        
            $currentEditingArea = null;
        
            ui() {
                return {
                    inlineEditingArea: '.' + this.getOption( 'inlineEditingClass' ),
                };
            };
        
            events() {
                return {
                    'click @ui.inlineEditingArea': 'onInlineEditingClick',
                    'input @ui.inlineEditingArea': 'onInlineEditingUpdate',
                };
            };
        
            initialize() {
				this.onInlineEditingBlur = this.onInlineEditingBlur.bind( this );
            };
        
            getEditingSettingKey() {
                return this.$currentEditingArea.data().elementorSettingKey;
            };
        
            
        
            onInlineEditingClick( event ) {
                var self = this,
                    $targetElement = jQuery( event.currentTarget );
        
                /**
                 * When starting inline editing we need to set timeout, this allows other inline items to finish
                 * their operations before focusing new editing area.
                 */
                setTimeout( function() {
                    self.startEditing( $targetElement );
                }, 30 );
            };
        
            onInlineEditingBlur( event ) {
                if ( 'mousedown' === event.type ) {
                    this.stopEditing();
        
                    return;
                }
        
                /**
                 * When exiting inline editing we need to set timeout, to make sure there is no focus on internal
                 * toolbar action. This prevent the blur and allows the user to continue the inline editing.
                 */
                setTimeout( () => {
                    const selection = elementorFrontend.elements.window.getSelection(),
                        $focusNode = jQuery( selection.focusNode );
        
                    if ( $focusNode.closest( '.pen-input-wrapper' ).length ) {
                        return;
                    }
        
                    this.stopEditing();
                }, 20 );
            };
        
            onInlineEditingUpdate() {
                let key = this.getEditingSettingKey(),
                    container = this.view.getContainer();
        
                const parts = key.split( '.' );
        
                // Is it repeater?
                if ( 3 === parts.length ) {
                    container = container.children[ parts[ 1 ] ];
                    key = parts[ 2 ];
                }
        
                $e.run( 'document/elements/settings', {
                    container,
                    settings: {
                        [ key ]: this.editor.getContent(),
                    },
                    options: {
                        external: true,
                    },
                } );
            };
        }
        console.log('sdfsdfsdfsd');
        return CustomeFilter;
    }

    behaviors.kit = {
        behaviorClass: this.abc(),
    };

    return behaviors;
}

		elementor.hooks.addFilter( 'elements/widget/behaviors', addHeaderBehavior ); 
		#>
		<?php
	}
}
