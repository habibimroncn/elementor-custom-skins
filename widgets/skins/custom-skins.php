<?php

if(class_exists('\ElementorPro\Modules\Posts\Skins\Skin_Cards')):

class Hn_Custom_Skin extends \ElementorPro\Modules\Posts\Skins\Skin_Cards {

	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'hn_register_frontend_scripts' ] );
	}

	public function hn_register_frontend_scripts(){
		wp_enqueue_script(
			'hn-customskins',
			plugins_url( 'assets/js/main.min.js', ELEMENTOR_CUSTOMSKINS_FILE ),
			['elementor-frontend'],
			'1.0.0',
			true
		);
	}

	public function get_id() {
		return 'hn-custom-skin';
	}
	public function get_title() {
		return __( 'Custom Skins' );
	}
	public function register_design_controls() {
		$this->register_design_layout_controls();
		$this->register_design_card_controls();
		$this->register_design_image_controls();
		$this->register_design_content_controls();
		$this->register_additional_controls();
	}
	public function register_additional_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Custom Button', 'elementor-customskins' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT
			]
		);

		// -----------------

		$this->add_control(
			'view_show_hide',
			[
				'label' => esc_html__( 'View Details', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementor-customskins' ),
				'label_off' => esc_html__( 'Hide', 'elementor-customskins' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'view_text',
			[
				'label' => esc_html__( 'View Text', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'View Details', 'elementor-customskins' ),
				'condition' => [
					$this->get_control_id( 'view_show_hide' ) => 'yes',
				],
			]
		);

		// -----------------

		$this->add_control(
			'map_it_show_hide',
			[
				'label' => esc_html__( 'Map It', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementor-customskins' ),
				'label_off' => esc_html__( 'Hide', 'elementor-customskins' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'map_it_text',
			[
				'label' => esc_html__( 'Map It Text', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Map It', 'elementor-customskins' ),
				'condition' => [
					$this->get_control_id( 'map_it_show_hide' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'map_it_slug',
			[
				'type' => \Elementor\Controls_Manager::TEXT,
				'label' => esc_html__( 'Slug (Map It)', 'elementor-customskins' ),
				'placeholder' => esc_html__( 'ACF slug used to Map It', 'elementor-customskins' ),
			]
		);

		// -----------------

		$this->add_control(
			'sub_show_hide',
			[
				'label' => esc_html__( 'Subtitle', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementor-customskins' ),
				'label_off' => esc_html__( 'Hide', 'elementor-customskins' ),
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sub_taxonomy',
			[
				'label' => esc_html__( 'Taxonomy', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [],
				'options' => $this->get_taxonomies(),
				'condition' => [
					$this->get_control_id( 'sub_show_hide' ) => 'yes',
				]
			]
		);

		$this->add_control(
			'level_taxonomy',
			[
				'label' => esc_html__( 'Level', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'options' =>  [
					'parent'  => esc_html__( 'Parent', 'elementor-customskins' ),
					'child' => esc_html__( 'Child', 'elementor-customskins' )
				],
				'default' => 'parent',
				'condition' => [
					$this->get_control_id( 'sub_show_hide' ) => 'yes'
				]
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_cb_view_details',
			[
				'label' => esc_html__( 'View Details', 'elementor-customskins' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'vd_icon_typography',
				'selector' => '{{WRAPPER}} .elementor-post__view-details',
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_responsive_control(
			'hn_vd_font_size',
			[
				'label' => esc_html__( 'Size', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 13,
				],
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__view-details' => 'font-size: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'hn_vd_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-post__view-details' => 'color: {{VALUE}}',
				],
				'default' => '#FFFFFF',
			]
		);

		$this->add_control(
			'hn_vd_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-post__view-details' => 'background-color: {{VALUE}}',
				],
				'default' => '#1A89B9',
			]
		);

		$this->add_responsive_control(
			'hn_vd_padding',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Padding', 'elementor-customskins' ),
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__view-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [10,20,10,20,'px',true]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'vd_border',
				'label' => esc_html__( 'Border', 'elementor-customskins' ),
				'selector' => '{{WRAPPER}} .elementor-post__view-details',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_cb_map_it',
			[
				'label' => esc_html__( 'Map It', 'elementor-customskins' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'mi_icon_typography',
				'selector' => '{{WRAPPER}} .elementor-post__map-it',
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_responsive_control(
			'hn_mi_font_size',
			[
				'label' => esc_html__( 'Size', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 13,
				],
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__map-it' => 'font-size: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'hn_mi_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-post__map-it' => 'color: {{VALUE}}',
				],
				'default' => '#FFFFFF',
			]
		);

		$this->add_control(
			'hn_mi_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-customskins' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-post__map-it' => 'background-color: {{VALUE}}',
				],
				'default' => '#51AF47',
			]
		);

		$this->add_responsive_control(
			'hn_mi_padding',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Padding', 'elementor-customskins' ),
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__map-it' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [10,20,10,20,'px',true]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'mi_border',
				'label' => esc_html__( 'Border', 'elementor-customskins' ),
				'selector' => '{{WRAPPER}} .elementor-post__map-it',
			]
		);

		$this->end_controls_section();
	}
	public function get_style_depends() {
		wp_register_style( 'widget-style-customskins', plugins_url( 'assets/css/customskins.css', ELEMENTOR_CUSTOMSKINS_FILE ) );

		return [
			'widget-style-customskins'
		];
	}
	protected function render_post_header() {
		?>
		<article <?php post_class( [ 'elementor-post elementor-grid-item' ] ); ?>>
			<div class="elementor-post__card">
		<?php
	}

	protected function render_post_footer() {
		?>
			</div>
		</article>
		<?php
	}

	protected function render_avatar() {
		?>
		<div class="elementor-post__avatar">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 128, '', get_the_author_meta( 'display_name' ) ); ?>
		</div>
		<?php
	}

	protected function render_badge() {
		$taxonomy = $this->get_instance_value( 'badge_taxonomy' );
		if ( empty( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$terms = get_the_terms( get_the_ID(), $taxonomy );
		if ( empty( $terms[0] ) ) {
			return;
		}
		?>
		<div class="elementor-post__badge"><?php echo esc_html( $terms[0]->name ); ?></div>
		<?php
	}

	public function get_container_class() {
		return 'elementor-has-item-ratio elementor-posts--skin-' . $this->get_id();
	}

	protected function render_thumbnail() {
		if ( 'none' === $this->get_instance_value( 'thumbnail' ) ) {
			return;
		}

		$settings = $this->parent->get_settings();
		$setting_key = $this->get_control_id( 'thumbnail_size' );
		$settings[ $setting_key ] = [
			'id' => get_post_thumbnail_id(),
		];
		$thumbnail_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		if ( empty( $thumbnail_html ) ) {
			return;
		}

		$optional_attributes_html = $this->get_optional_link_attributes_html();

		?>
		<a class="elementor-post__thumbnail__link" href="<?php echo esc_url( get_permalink() ); ?>" <?php \Elementor\Utils::print_unescaped_internal_string( $optional_attributes_html ); ?>><div class="elementor-post__thumbnail elementor-fit-height"><?php \Elementor\Utils::print_unescaped_internal_string( $thumbnail_html ); ?></div></a>
		<?php
		if ( $this->get_instance_value( 'show_badge' ) ) {
			$this->render_badge();
		}

		if ( $this->get_instance_value( 'show_avatar' ) ) {
			$this->render_avatar();
		}
	}

	protected function render_hn_custom_btn() {
		$settings = $this->parent->get_settings_for_display();

		$current_p_id = get_the_ID();
		$sl_url = '#';

		$map_it_text_key = $this->get_control_id( 'map_it_text' );
		$map_it_slug_key = $this->get_control_id( 'map_it_slug' );
		$map_it_sh = $this->get_instance_value( 'map_it_show_hide' );

		$map_it_text = $settings[ $map_it_text_key ];
		$map_it_slug = $settings[ $map_it_slug_key ];

		$map_it_slug_convert = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $map_it_slug)));

		$slug_url = get_field( $map_it_slug, $current_p_id );

		if($slug_url):
			$sl_url = esc_url($slug_url);
		endif;

		// ---

		$view_text_key = $this->get_control_id( 'view_text' );
		$view_sh = $this->get_instance_value( 'view_show_hide' );

		$view_text = $settings[ $view_text_key ];
	?>

		<div class="hn-custom-btn-container">
			<?php 
				if($view_sh):
			?>
					<a class="elementor-post__view-details" href="<?php echo esc_url(get_the_permalink()); ?>" target="_blank">
						<?php echo wp_kses_post( $view_text ); ?>
					</a>
			<?php
				endif;
			?>

			<?php 
				if($map_it_sh):
			?>
					<a class="elementor-post__map-it" href="<?php echo esc_url($slug_url); ?>" target="_blank">
						<?php echo wp_kses_post( $map_it_text ); ?>
					</a>
			<?php
				endif;
			?>
		</div>

	<?php
	}

	protected function render_hn_subtitle(){
		$settings = $this->parent->get_settings_for_display();

		$current_p_id = get_the_ID();
		$sub_show_hide = $this->get_instance_value( 'sub_show_hide' );
		$sub_key = $this->get_control_id( 'sub_taxonomy' );
		$sub_level_key = $this->get_control_id( 'level_taxonomy' );

		$sub_taxonomy = $settings[$sub_key];
		$sub_level = $settings[$sub_level_key];

		if(!$sub_show_hide):
			return;
		endif;

		$terms = wp_get_post_terms( $current_p_id, $sub_taxonomy );
	?>
		<div class="custom-skins-subtitle">
	<?php
		foreach ( $terms as $term ) {
			if($sub_level == 'parent'):
				if($term->parent == 0):
	?>
					<p class="elementor-post__subtitle parent">
						<?php echo $term->name; ?>
					</p>
	<?php
				endif;
			endif;

			if($sub_level == 'child'):
				if($term->parent != 0):
	?>
					<p class="elementor-post__subtitle child">
						<?php echo $term->name; ?>
					</p>
	<?php
				endif;
			endif;
		}
	?>
		</div>
	<?php
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [
			'show_in_nav_menus' => true,
		], 'objects' );

		$options = [
			'' => esc_html__( 'Choose', 'elementor-customskins' ),
		];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	protected function render_post() {
		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_text_header();
		$this->render_title();
		$this->render_hn_subtitle();
		$this->render_excerpt();
		$this->render_read_more();
		$this->render_hn_custom_btn();
		$this->render_text_footer();
		$this->render_meta_data();
		$this->render_post_footer();
	}
}

endif;