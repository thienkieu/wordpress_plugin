<?php
namespace Dragon\Plugins\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Schemes;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

/**
 * Elementor motel widget.
 *
 * Elementor widget that displays an motel into the page.
 *
 * @since 1.0.0
 */
class Widget_Motel extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve motel widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motel';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve motel widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Motel', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve motel widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fas fa-hotel';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the motel widget belongs to.
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
		return [ 'motel', ];
	}

	/**
	 * Register motel widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Motel', 'elementor' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'floor_title',
			[
				'label' => __( 'Title & Description', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Floor title', 'elementor' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'number_room',
			[
				'label' => __( 'Number of room', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => __( 1, 'elementor' ),
				'show_label' => true,
			]
        );

        $repeater->add_control(
			'price_room',
			[
				'label' => __( 'Price of room <sup> 1k</sup>', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => __( 1000, 'elementor' ),
				'show_label' => true,
			]
        );
        
        $repeater->add_control(
			'available_room',
			[
				'label' => __( 'Available of room', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => __( 1, 'elementor' ),
				'show_label' => true,
			]
        );
        
		$this->add_control(
			'tabs',
			[
				'label' => __( 'Floor Items', 'elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'floor_title' => __( 'Floor #1', 'elementor' ),
						'number_room' => '',
						'price_room' => '',
						'available_room' => '',
					],
					
				],
				'title_field' => '{{{ floor_title }}}',
			]
        );
        
        $this->add_control(
			'motel_phone',
			[
				'label' => __( 'Phone', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'label_block' => false,
			]
        );

        $this->add_control(
			'motel_author',
			[
				'label' => __( 'Author', 'elementor' ),
				'type' => Controls_Manager::TEXT,				
				'label_block' => false,
			]
        );

        $this->add_control(
			'motel_location_latitude',
			[
				'label' => __( 'Latitude', 'elementor' ),
				'type' => Controls_Manager::TEXT,				
				'label_block' => false,
			]
        );

        $this->add_control(
			'motel_location_longitude',
			[
				'label' => __( 'Longitude', 'elementor' ),
				'type' => Controls_Manager::TEXT,				
				'label_block' => false,
			]
        );
        
		$this->end_controls_section();

	}

	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		
	}

	/**
	 * Render image widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		
	}

}


include_once( __DIR__ .'/actions.php');
\add_action( 'elementor/document/after_save', 'insertOrUpdateMotelInfo', 10, 2 );