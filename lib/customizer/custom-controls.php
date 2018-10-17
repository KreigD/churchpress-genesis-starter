<?php
/**
 * ChurchPress Genesis Starter.
 *
 * Add custom controls to the customizer here.
 *
 * @package ChurchPress Genesis Starter
 * @author  ChurchPress
 * @license GPL-3.0+
 * @link    https://ChurchPress.co
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
    return NULL;
}
/**
 * Class to create a custom post control
 */
class Post_Dropdown_Custom_Control extends WP_Customize_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'dropdown_posts';
	/**
	 * Posts
	 */
	private $posts = array();
	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		parent::__construct( $manager, $id, $args );
		// Get our Posts
		$this->posts = get_posts( $this->input_attrs );
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
	?>
		<div class="dropdown_posts_control">
			<?php if( !empty( $this->label ) ) { ?>
				<label for="<?php echo ecp_attr( $this->id ); ?>" class="customize-control-title">
					<?php echo ecp_html( $this->label ); ?>
				</label>
			<?php } ?>
			<?php if( !empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo ecp_html( $this->description ); ?></span>
			<?php } ?>
			<select name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" <?php $this->link(); ?>>
				<?php
					if( !empty( $this->posts ) ) {
						foreach ( $this->posts as $post ) {
							printf( '<option value="%s" %s>%s</option>',
								$post->ID,
								selected( $this->value(), $post->ID, false ),
								$post->post_title
							);
						}
					}
				?>
			</select>
		</div>
	<?php
	}
}

/**
 * Menu Select Dropdown
 */
class Menu_Select_Dropdown_Control extends WP_Customize_Control {
	/**
	 * The type of control being used
	 */
	public $type = 'dropdown_menus';

	/**
	 * Menus
	 */
	private $menus = array();

	/**
	 * Get our menus
	 *
	 * @param string $manager
	 * @param int $id
	 * @param array $options
	 */
	function __construct( $manager, $id, $args = array(), $options = array() ) {
		parent::__construct( $manager, $id, $args );

		$this->menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
	}

	public function render_content() {
		?>
		<div class="dropdown_menus_control">
			<?php if( !empty( $this->label ) ) { ?>
				<label for="<?php echo ecp_attr( $this->id ); ?>" class="customize-control-title">
					<?php echo ecp_html( $this->label ); ?>
				</label>
			<?php } ?>
			<?php if( !empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo ecp_html( $this->description ); ?></span>
			<?php } ?>
			<select name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" <?php $this->link(); ?>>
				<?php
					if( !empty( $this->menus ) ) {
						foreach ( $this->menus as $menu ) {
							printf( '<option value="%s" %s>%s</option>',
								$menu->term_id,
								selected( $this->value(), $menu->term_id, false ),
								$menu->name
							);
						}
					}
				?>
			</select>
		</div>
		<?php
	}
}

/**
 * TinyMCE Editor Custom Control
 */
class TinyMCE_Control extends WP_Customize_Control {
	function __construct($manager, $id, $options) {
		parent::__construct($manager, $id, $options);

		global $num_customizer_teenies_initiated;
		$num_customizer_teenies_initiated = empty($num_customizer_teenies_initiated)
			? 1
			: $num_customizer_teenies_initiated + 1;
	}
	function render_content() {
		global $num_customizer_teenies_initiated, $num_customizer_teenies_rendered;
		$num_customizer_teenies_rendered = empty($num_customizer_teenies_rendered)
			? 1
			: $num_customizer_teenies_rendered + 1;

		$value = $this->value();
		?>
			<label class="tinymce-control">
				<span class="customize-control-title"><?php echo ecp_html($this->label); ?></span>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo ecp_html( $this->description ); ?></span>
				<?php } ?>
				<input id="<?php echo $this->id ?>-link" class="wp-editor-area" type="hidden" <?php $this->link(); ?> value="<?php echo ecp_textarea($value); ?>">
				<?php
					wp_editor($value, $this->id, [
						'textarea_name' => $this->id,
						'media_buttons' => true,
						'drag_drop_upload' => true,
						'teeny' => false,
						'quicktags' => true,
						'textarea_rows' => 10,
						// MAKE SURE TINYMCE CHANGES ARE LINKED TO CUSTOMIZER
						'tinymce' => [
							'setup' => "function (editor) {
								var cb = function () {
									var linkInput = document.getElementById('$this->id-link')
									linkInput.value = editor.getContent()
									linkInput.dispatchEvent(new Event('change'))
								}
								editor.on('Change', cb)
								editor.on('Undo', cb)
								editor.on('Redo', cb)
							}"
						]
					]);
				?>
			</label>
		<?php
		// PRINT THEM ADMIN SCRIPTS AFTER LAST EDITOR
		if ($num_customizer_teenies_rendered == $num_customizer_teenies_initiated)
			do_action('admin_print_footer_scripts');
	}
}
