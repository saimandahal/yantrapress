<?php 
/* Control show - update menu data */
if( !class_exists('Zamona_Mega_Menu') ){
	class Zamona_Mega_Menu{
		private $delete_css_transient = true;
		
		function __construct() {
			add_filter( 'wp_edit_nav_menu_walker', array($this, 'show_custom_fields') );
			add_action( 'wp_update_nav_menu_item', array($this, 'save_custom_fields'), 10, 3 );
            add_filter( 'wp_setup_nav_menu_item', array($this, 'add_data_to_custom_fields') );
			
			add_action( 'widgets_init', array($this, 'register_sidebars'), 500 );
			
			add_filter( 'wp_nav_menu_objects', array($this, 'modify_nav_items') );
			
			add_filter( 'nav_menu_item_title', array($this, 'add_sub_label_to_menu_item_title'), 10, 4 );
			
			add_action( 'wp_enqueue_scripts', array($this, 'add_custom_css_for_background'), 99999 );
		}
		
		function show_custom_fields(){
			return 'Zamona_Custom_Mega_Menu';
		}
		
		function save_custom_fields( $menu_id, $menu_item_db_id, $args ){
			if ( isset($_REQUEST['menu-item-ts-sub-label-text']) && is_array($_REQUEST['menu-item-ts-sub-label-text']) ) {
				$ts_sub_label_text = $_REQUEST['menu-item-ts-sub-label-text'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_ts_sub_label_text', $ts_sub_label_text );
			}
			
			if ( isset($_REQUEST['menu-item-ts-sub-label-bg-color']) && is_array($_REQUEST['menu-item-ts-sub-label-bg-color']) ) {
				$ts_sub_label_bg_color = $_REQUEST['menu-item-ts-sub-label-bg-color'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_ts_sub_label_bg_color', $ts_sub_label_bg_color );
			}
			
			if ( isset($_REQUEST['menu-item-ts-is-megamenu'][$menu_item_db_id]) && is_array($_REQUEST['menu-item-ts-is-megamenu']) ) {
				$ts_is_megamenu = $_REQUEST['menu-item-ts-is-megamenu'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_ts_is_megamenu', $ts_is_megamenu );
			}
			else{
				update_post_meta( $menu_item_db_id, '_menu_item_ts_is_megamenu', 0 );
			}
			
			if ( isset($_REQUEST['menu-item-ts-megamenu-column']) && is_array($_REQUEST['menu-item-ts-megamenu-column']) ) {
				$ts_megamenu_column = $_REQUEST['menu-item-ts-megamenu-column'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_ts_megamenu_column', $ts_megamenu_column );
			}
			
			if ( isset($_REQUEST['menu-item-ts-sidebars']) && is_array($_REQUEST['menu-item-ts-sidebars']) ) {
				$ts_sidebars = $_REQUEST['menu-item-ts-sidebars'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_ts_sidebars', $ts_sidebars );
			}
			
			if ( isset($_REQUEST['menu-item-ts-static-html']) && is_array($_REQUEST['menu-item-ts-static-html']) ) {
				$ts_static_html = $_REQUEST['menu-item-ts-static-html'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_ts_static_html', $ts_static_html );
			}
			
			if ( isset($_REQUEST['menu-item-ts-thumbnail-id']) && is_array($_REQUEST['menu-item-ts-thumbnail-id']) ) {
				$ts_thumbnail_id = $_REQUEST['menu-item-ts-thumbnail-id'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_ts_thumbnail_id', $ts_thumbnail_id );
			}
			
			if ( isset($_REQUEST['menu-item-ts-background-id']) && is_array($_REQUEST['menu-item-ts-background-id']) ) {
				$ts_background_id = $_REQUEST['menu-item-ts-background-id'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_ts_background_id', $ts_background_id );
			}
			
			if ( isset($_REQUEST['menu-item-ts-background-repeat']) && is_array($_REQUEST['menu-item-ts-background-repeat']) ) {
				$ts_background_repeat = $_REQUEST['menu-item-ts-background-repeat'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_ts_background_repeat', $ts_background_repeat );
			}
			
			if ( isset($_REQUEST['menu-item-ts-background-position']) && is_array($_REQUEST['menu-item-ts-background-position']) ) {
				$ts_background_position = $_REQUEST['menu-item-ts-background-position'][$menu_item_db_id];
				update_post_meta( $menu_item_db_id, '_menu_item_ts_background_position', $ts_background_position );
			}
			
			/* Delete transient */
			if( $this->delete_css_transient ){
				set_transient('ts_mega_menu_custom_css', 0, MONTH_IN_SECONDS);
				$this->delete_css_transient = false;
			}
		}
		
		function add_data_to_custom_fields( $menu_item ){
			$menu_item->ts_sub_label_text = get_post_meta( $menu_item->ID, '_menu_item_ts_sub_label_text', true );
			$menu_item->ts_sub_label_bg_color = get_post_meta( $menu_item->ID, '_menu_item_ts_sub_label_bg_color', true );
			$menu_item->ts_is_megamenu = get_post_meta( $menu_item->ID, '_menu_item_ts_is_megamenu', true );
			$menu_item->ts_megamenu_column = get_post_meta( $menu_item->ID, '_menu_item_ts_megamenu_column', true );
			$menu_item->ts_static_html = get_post_meta( $menu_item->ID, '_menu_item_ts_static_html', true );
			$menu_item->ts_thumbnail_id = get_post_meta( $menu_item->ID, '_menu_item_ts_thumbnail_id', true );
			$menu_item->ts_background_id = get_post_meta( $menu_item->ID, '_menu_item_ts_background_id', true );
			$menu_item->ts_background_repeat = get_post_meta( $menu_item->ID, '_menu_item_ts_background_repeat', true );
			$menu_item->ts_background_position = get_post_meta( $menu_item->ID, '_menu_item_ts_background_position', true );
			return $menu_item;
		}
		
		function register_sidebars(){
			$num_widget = (int) zamona_get_theme_options('ts_menu_num_widget');
			if( $num_widget > 0 ){
				if($num_widget == 1){
					register_sidebar(array(
						'name'          => 'TS Mega Menu Widget Area 1',
						'id'            => 'ts-mega-sidebar',
						'description'   => 'TS Mega Menu Widget Area 1',
						'before_title'  => '<h2 class="widgettitle">',
						'after_title'   => '</h2>' 
					));				
				}
				else{
					register_sidebars( $num_widget, array(
						'name'          => 'TS Mega Menu Widget Area %d',
						'id'            => "ts-mega-sidebar",
						'before_title'  => '<h2 class="widgettitle">',
						'after_title'   => '</h2>' 
					));
				}
			}
		}
		
		public static function select_sidebar_html($item_id, $num_widget){
			$html = '';
			$num_widget = (int)$num_widget > 0 ? (int)$num_widget : 1;
				
			$fid = 'edit-menu-item-ts-sidebars-'.$item_id;
			$name = 'menu-item-ts-sidebars['.$item_id.']';
			$selection = get_post_meta( $item_id, '_menu_item_ts_sidebars', true);
			$options = array();
			for($k = 0; $k < $num_widget; $k++){
				$val = 'TS Mega Menu Widget Area '.($k+1);
				$options[$val] = $val;
			}
			if(empty($options)) return '';
			$html.= '<select id="'.$fid.'" name="'.$name.'" class="edit-menu-item-ts-sidebars widefat">';
			$html.= '<option value=""></option>';
			foreach($options as $value => $text){
				$selected = $value == $selection ? 'selected="selected"' : '';
				$html.= '<option value="'.$value.'" '.$selected.' >'.$text.'</option>';
			}
					
			$html.= '</select>';
			
			return $html;
		}
		
		function has_sub($menu_item_id, &$items) {
			$sub_count = 0;
			foreach ($items as $item) {
				if ($item->menu_item_parent && $item->menu_item_parent==$menu_item_id) {
				   $sub_count++;
				}
			}
			return $sub_count;
		}
		
		function modify_nav_items($items){
			foreach ($items as $item) {
				if ($sub_count = $this->has_sub($item->ID, $items)) {
					$item->sub_count = $sub_count; 
				}
				else{
					$item->sub_count = 0;
				}
			}
			return $items;    
		}
		
		function add_sub_label_to_menu_item_title( $title, $item, $args, $depth ){
			$sub_label_text = get_post_meta( $item->ID, '_menu_item_ts_sub_label_text', true );
			if( $sub_label_text ){
				$title .= '<span class="menu-sub-label">'.esc_html($sub_label_text).'</span>';
			}
			return $title;
		}
		
		function add_custom_css_for_background(){
			$style = get_transient('ts_mega_menu_custom_css');
			$site_url = site_url();
			if( $style === false || $style === '0' ){
				$style = '';
				global $wpdb;
				$sql = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_menu_item_ts_background_id' and meta_value <> '';";
				$rows = $wpdb->get_results( $sql );
				
				if( is_array($rows) && !empty($rows) ){
					foreach( $rows as $row ){
						$post_id = $row->post_id;
						$is_megamenu = get_post_meta($post_id, '_menu_item_ts_is_megamenu', true);
						$background_id = $row->meta_value;
						$background_url = wp_get_attachment_url( $background_id );
						
						if( $is_megamenu && $background_url ){
							$background_repeat = get_post_meta($post_id, '_menu_item_ts_background_repeat', true);
							$background_position = get_post_meta($post_id, '_menu_item_ts_background_position', true);
							
							$sub_style = '.menu-item-' . $post_id . ' > ul.sub-menu:before{';
							$sub_style .= 'background-image: url(' . $background_url . ');';
							$sub_style .= 'background-repeat: ' . $background_repeat . ';';
							$sub_style .= 'background-position: ' . str_replace('-', ' ', $background_position) . ';';
							$sub_style .= '}';
							
							$style .= $sub_style;
						}
					}
				}
				
				$sql = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_menu_item_ts_sub_label_bg_color' and meta_value <> ''";
				$sql .= " and post_id in (SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = '_menu_item_ts_sub_label_text' and meta_value <> '');";
				$rows = $wpdb->get_results( $sql );
				
				if( is_array($rows) && !empty($rows) ){
					foreach( $rows as $row ){
						$post_id = $row->post_id;
						$sub_label_bg_color = $row->meta_value;
						
						if( $sub_label_bg_color ){
							$sub_style = '.menu-item-' . $post_id . ' > a > .menu-sub-label{';
							$sub_style .= 'background-color: ' . $sub_label_bg_color . ';';
							$sub_style .= '}';
							$sub_style .= '.menu-item-' . $post_id . ' > a > .menu-sub-label:before{';
							$sub_style .= 'border-left-color: ' . $sub_label_bg_color . ';';
							$sub_style .= 'border-right-color: ' . $sub_label_bg_color . ';';
							$sub_style .= '}';
							
							$style .= $sub_style;
						}
					}
				}
				
				set_transient('ts_mega_menu_custom_css', str_replace($site_url, '[site_url]', $style), MONTH_IN_SECONDS);
			}
			else{
				$style = str_replace('[site_url]', $site_url, $style);
			}
			
			if( $style ){
				wp_add_inline_style('zamona-style', $style);
			}
		}
	}
}
new Zamona_Mega_Menu();

/* Custom Html Menu Item */
if( !class_exists('Zamona_Custom_Mega_Menu') ){
	class Zamona_Custom_Mega_Menu extends Walker_Nav_Menu{
		function __construct(){
		
		}
		
		function start_lvl(&$output, $depth = 0, $args = array()){}
		
		function end_lvl(&$output, $depth = 0, $args = array()){}
		
		/* Display html */
		function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
			$num_widget = (int) zamona_get_theme_options('ts_menu_num_widget');
		
			global $_wp_nav_menu_max_depth;
			$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
			
			ob_start();
			
			$item_id = esc_attr( $item->ID );
			$removed_args = array(
				'action'
				,'customlink-tab'
				,'edit-menu-item'
				,'menu-item'
				,'page-tab'
				,'_wpnonce'
			);

			$original_title = '';
			if ( 'taxonomy' == $item->type ) {
				$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
				if ( is_wp_error( $original_title ) )
					$original_title = false;
			} elseif ( 'post_type' == $item->type ) {
				$original_object = get_post( $item->object_id );
				$original_title = get_the_title( $original_object->ID );
			}

			$classes = array(
				'menu-item menu-item-depth-' . $depth,
				'menu-item-' . esc_attr( $item->object ),
				'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
			);

			$title = $item->title;

			if ( ! empty( $item->_invalid ) ) {
				$classes[] = 'menu-item-invalid';
				/* translators: %s: title of menu item which is invalid */
				$title = sprintf( esc_html__( '%s (Invalid)','zamona' ), $item->title );
			} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
				$classes[] = 'pending';
				/* translators: %s: title of menu item in draft status */
				$title = sprintf( esc_html__('%s (Pending)','zamona'), $item->title );
			}

			$title = empty( $item->label ) ? $title : $item->label;

			?>
			<li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo implode(' ', $classes ); ?>">
				<dl class="menu-item-bar">
					<dt class="menu-item-handle">
						<span class="item-title"><?php echo esc_html( $title ); ?></span>
						<span class="item-controls">
							<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
							<span class="item-order hide-if-js">
								<a href="<?php
									echo wp_nonce_url(
										esc_url(
											add_query_arg(
												array(
													'action' => 'move-up-menu-item',
													'menu-item' => $item_id,
												),
												remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
											)
										),
										'move-menu_item'
									);
								?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up','zamona'); ?>">&#8593;</abbr></a>
								|
								<a href="<?php
									echo wp_nonce_url(
										esc_url(
											add_query_arg(
												array(
													'action' => 'move-down-menu-item',
													'menu-item' => $item_id,
												),
												remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
											)
										),
										'move-menu_item'
									);
								?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down','zamona'); ?>">&#8595;</abbr></a>
							</span>
							<a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>" title="<?php esc_attr_e('Edit Menu Item','zamona'); ?>" href="<?php
								echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : esc_url( add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) ) );
							?>"><?php esc_html_e( 'Edit Menu Item','zamona' ); ?></a>
						</span>
					</dt>
				</dl>

				<div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
					<?php if( 'custom' == $item->type ) : ?>
						<p class="field-url description description-wide">
							<label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
								<?php esc_html_e( 'URL','zamona' ); ?><br />
								<input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
							</label>
						</p>
					<?php endif; ?>
					<p class="description description-thin">
						<label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e( 'Navigation Label','zamona' ); ?><br />
							<input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
						</label>
					</p>
					<p class="description description-thin">
						<label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e( 'Title Attribute','zamona' ); ?><br />
							<input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
						</label>
					</p>
					<p class="field-link-target description">
						<label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
							<input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
							<?php esc_html_e( 'Open link in a new window/tab','zamona' ); ?>
						</label>
					</p>
					<p class="field-css-classes description description-thin">
						<label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e( 'CSS Classes (optional)','zamona' ); ?><br />
							<input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
						</label>
					</p>
					<p class="field-xfn description description-thin">
						<label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e( 'Link Relationship (XFN)','zamona' ); ?><br />
							<input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
						</label>
					</p>
					<p class="field-description description description-wide">
						<label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e( 'Description','zamona' ); ?><br />
							<textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
							<span class="description"><?php esc_html_e('The description will be displayed in the menu if the current theme supports it.','zamona'); ?></span>
						</label>
					</p>        
					<?php
					/*
					 * This is the added fields
					 */
					do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args ); /* Compatible with the Nav Menu Roles plugin */
					?>
					<p class="field-ts-sub-label-text description description-thin">
						<label for="edit-menu-item-ts-sub-label-text-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e('Sub Label Text', 'zamona'); ?>
						</label>
						<input value="<?php echo esc_attr($item->ts_sub_label_text); ?>" type="text" id="edit-menu-item-ts-sub-label-text-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-ts-sub-label-text" name="menu-item-ts-sub-label-text[<?php echo esc_attr($item_id); ?>]" />
					</p>
					
					<p class="field-ts-sub-label-bg-color description description-thin">
						<label for="edit-menu-item-ts-sub-label-bg-color-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e('Sub Label Background Color', 'zamona'); ?>
						</label>
						<input value="<?php echo esc_attr($item->ts_sub_label_bg_color); ?>" type="text" id="edit-menu-item-ts-sub-label-bg-color-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-ts-sub-label-bg-color" name="menu-item-ts-sub-label-bg-color[<?php echo esc_attr($item_id); ?>]" />
					</p>
					
					<p class="field-ts-is-megamenu description description-wide ts-custom-menu ts-active-lv0">
						<label for="edit-menu-item-ts-is-megamenu-<?php echo esc_attr($item_id); ?>">
							<?php $ts_is_megamenu = (int)$item->ts_is_megamenu;?>
							<input value="1" type="checkbox" id="edit-menu-item-ts-is-megamenu-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-ts-is-megamenu" name="menu-item-ts-is-megamenu[<?php echo esc_attr($item_id); ?>]" <?php checked($ts_is_megamenu, 1); ?> />
							<?php esc_html_e('Enable Mega Menu', 'zamona'); ?>
						</label>
					</p>		
					
					<p class="field-ts-megamenu-column description description-thin ts-custom-menu ts-active-lv0">
						<label for="edit-menu-item-ts-megamenu-column-<?php echo esc_attr($item_id); ?>">
							<?php $ts_megamenu_column = esc_attr( $item->ts_megamenu_column );?>
							<?php esc_html_e( 'Columns','zamona' ); ?><br />
							<select id="edit-menu-item-ts-megamenu-column-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-ts-megamenu-column" name="menu-item-ts-megamenu-column[<?php echo esc_attr($item_id); ?>]">
							   <option value="-1" <?php selected(-1, $ts_megamenu_column); ?> ><?php esc_html_e('Fullwidth & Stretch', 'zamona') ?></option>
							   <option value="0" <?php selected(0, $ts_megamenu_column); ?> ><?php esc_html_e('Fullwidth', 'zamona') ?></option>
							   <option value="1" <?php selected(1, $ts_megamenu_column); ?> ><?php esc_html_e('1 column', 'zamona') ?></option>
							   <option value="2" <?php selected(2, $ts_megamenu_column); ?> ><?php esc_html_e('2 columns', 'zamona') ?></option>
							   <option value="3" <?php selected(3, $ts_megamenu_column); ?> ><?php esc_html_e('3 columns', 'zamona') ?></option>
							   <option value="4" <?php selected(4, $ts_megamenu_column); ?> ><?php esc_html_e('4 columns', 'zamona') ?></option>
							</select> 
						</label>
					</p>	

					<?php if( $num_widget > 0):?>
							<p class="field-wide-widget description description-thin ts-custom-menu ts-active-lv0">
								<label for="edit-menu-item-ts-sidebars-<?php echo esc_attr($item_id); ?>">
									<span class="description"><?php esc_html_e('Display A Widget Area','zamona'); ?></span>
									<?php echo Zamona_Mega_Menu::select_sidebar_html($item_id, $num_widget); ?>
								</label>
							</p>
					<?php endif ?>	
					
					<p class="field-ts-static-html description description-wide ts-custom-menu ts-active-lv0">
						<label for="edit-menu-item-ts-static-html-<?php echo esc_attr($item_id); ?>">
							<?php esc_html_e( 'Static HTML','zamona' ); ?><br />
							<?php wp_editor( stripslashes( $item->ts_static_html ), 'edit-menu-item-ts-static-html-'.$item_id, array('textarea_name'=>'menu-item-ts-static-html['.$item_id.']') ); ?>
						</label>
					</p>

					<p class="field-ts-thumbnail description description-thin">
						<label>
							<?php 
								$ts_thumbnail = wp_get_attachment_url( $item->ts_thumbnail_id );
								esc_html_e( 'Set Thumbnail', 'zamona' ); 
							?>
							<br />
							<a id="<?php echo esc_attr($item_id); ?>" class="ts_mega_menu_upload_image" href="javascript:void(0)" style="display:<?php echo !empty($ts_thumbnail) ? 'none' : 'inline'; ?>;"><?php esc_html_e('Select Image', 'zamona'); ?></a>
							<span class="preview-thumbnail-wrapper">
								<?php if( $ts_thumbnail ): ?>
									<img src="<?php echo esc_url($ts_thumbnail); ?>" width="32" height="32" id="thumbnail-menu-item-<?php echo esc_attr($item_id); ?>" title="menu-item-<?php echo esc_attr($item_id); ?>-thumbnail" alt="menu-item-<?php echo esc_attr($item_id); ?>-thumbnail">
								<?php endif; ?>
							</span>
							<a id="<?php echo esc_attr($item_id); ?>" class="ts_mega_menu_clear_image" href="javascript:void(0)" style="display:<?php echo !empty($ts_thumbnail) ? 'inline' : 'none'; ?>;"><?php esc_html_e('Remove Image', 'zamona'); ?></a>
							<input type="hidden" id="edit-menu-item-ts-thumbnail-id-<?php echo esc_attr($item_id); ?>" class="widefat thumbnail-id-hidden" name="menu-item-ts-thumbnail-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->ts_thumbnail_id ); ?>" />
						 </label>	
					</p>

					<p class="field-ts-background description description-thin ts-custom-menu ts-active-lv0">
						<label>
							<?php 
								$ts_background = wp_get_attachment_url( $item->ts_background_id );
								esc_html_e( 'Set Background', 'zamona' ); 
							?>
							<br />
							<a id="<?php echo esc_attr($item_id); ?>" class="ts_mega_menu_upload_image" href="javascript:void(0)" style="display:<?php echo !empty($ts_background) ? 'none' : 'inline'; ?>;"><?php esc_html_e('Select Image', 'zamona'); ?></a>
							<span class="preview-thumbnail-wrapper">
								<?php if( $ts_background ): ?>
									<img src="<?php echo esc_url($ts_background); ?>" width="32" height="32" id="background-menu-item-<?php echo esc_attr($item_id); ?>" title="menu-item-<?php echo esc_attr($item_id); ?>-background" alt="menu-item-<?php echo esc_attr($item_id); ?>-background">
								<?php endif; ?>
							</span>
							<a id="<?php echo esc_attr($item_id); ?>" class="ts_mega_menu_clear_image" href="javascript:void(0)" style="display:<?php echo !empty($ts_background) ? 'inline' : 'none'; ?>;"><?php esc_html_e('Remove Image', 'zamona'); ?></a>
							<input type="hidden" id="edit-menu-item-ts-background-id-<?php echo esc_attr($item_id); ?>" class="widefat thumbnail-id-hidden" name="menu-item-ts-background-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->ts_background_id ); ?>" />
						 </label>	
					</p>
					
					<p class="field-ts-background-repeat description description-thin ts-custom-menu ts-active-lv0">
						<label for="edit-menu-item-ts-background-repeat-<?php echo esc_attr($item_id); ?>">
							<?php $ts_background_repeat = esc_attr( $item->ts_background_repeat );?>
							<?php esc_html_e( 'Background Repeat', 'zamona' ); ?><br />
							<select id="edit-menu-item-ts-background-repead-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-ts-background-repeat" name="menu-item-ts-background-repeat[<?php echo esc_attr($item_id); ?>]">
							   <option value="no-repeat" <?php selected('no-repeat', $ts_background_repeat); ?> ><?php esc_html_e('No Repeat', 'zamona') ?></option>
							   <option value="repeat" <?php selected('repeat', $ts_background_repeat); ?> ><?php esc_html_e('Repeat', 'zamona') ?></option>
							   <option value="repeat-x" <?php selected('repeat-x', $ts_background_repeat); ?> ><?php esc_html_e('Repeat X', 'zamona') ?></option>
							   <option value="repeat-y" <?php selected('repeat-y', $ts_background_repeat); ?> ><?php esc_html_e('Repeat Y', 'zamona') ?></option>
							</select> 
						</label>
					</p>
					
					<p class="field-ts-background-position description description-thin ts-custom-menu ts-active-lv0">
						<label for="edit-menu-item-ts-background-position-<?php echo esc_attr($item_id); ?>">
							<?php $ts_background_position = esc_attr( $item->ts_background_position );?>
							<?php esc_html_e( 'Background Position', 'zamona' ); ?><br />
							<select id="edit-menu-item-ts-background-position-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-ts-background-position" name="menu-item-ts-background-position[<?php echo esc_attr($item_id); ?>]">
							   <option value="left-top" <?php selected('left-top', $ts_background_position); ?> ><?php esc_html_e('Left Top', 'zamona') ?></option>
							   <option value="left-bottom" <?php selected('left-bottom', $ts_background_position); ?> ><?php esc_html_e('Left Bottom', 'zamona') ?></option>
							   <option value="left-center" <?php selected('left-center', $ts_background_position); ?> ><?php esc_html_e('Left Center', 'zamona') ?></option>
							   <option value="right-top" <?php selected('right-top', $ts_background_position); ?> ><?php esc_html_e('Right Top', 'zamona') ?></option>
							   <option value="right-bottom" <?php selected('right-bottom', $ts_background_position); ?> ><?php esc_html_e('Right Bottom', 'zamona') ?></option>
							   <option value="right-center" <?php selected('right-center', $ts_background_position); ?> ><?php esc_html_e('Right Center', 'zamona') ?></option>
							   <option value="center-top" <?php selected('center-top', $ts_background_position); ?> ><?php esc_html_e('Center Top', 'zamona') ?></option>
							   <option value="center-bottom" <?php selected('center-bottom', $ts_background_position); ?> ><?php esc_html_e('Center Bottom', 'zamona') ?></option>
							   <option value="center-center" <?php selected('center-center', $ts_background_position); ?> ><?php esc_html_e('Center Center', 'zamona') ?></option>
							</select> 
						</label>
					</p>
					
					<?php
					/*
					 * end the added fields
					 */
					?>
					<div class="menu-item-actions description-wide submitbox">
						<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
							<p class="link-to-original">
								<?php printf( esc_html__('Original: %s','zamona'), '<a href="' . esc_url( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
							</p>
						<?php endif; ?>
						<a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
						echo wp_nonce_url(
							esc_url(
								add_query_arg(
									array(
										'action' => 'delete-menu-item',
										'menu-item' => $item_id,
									),
									remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
								)
							),
							'delete-menu_item_' . $item_id
						); ?>"><?php esc_html_e('Remove','zamona'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
							?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php esc_html_e('Cancel','zamona'); ?></a>
					</div>

					<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
					<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
					<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
					<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
					<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
					<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
				</div><!-- .menu-item-settings-->
				<ul class="menu-item-transport"></ul>
			<?php
			
			$output .= ob_get_clean();
		}
	}
}

/* Display Menu on Frontend */
if( !class_exists('Zamona_Walker_Nav_Menu') ){
	class Zamona_Walker_Nav_Menu extends Walker_Nav_Menu{
		public $menu_config = array();
		public $parent_megamenu;
		public $megamenu_column;
		public $parent_sidebar;
		public $parent_static_html;
		function __construct(){
			$this->menu_config['num_widget'] = (int) zamona_get_theme_options('ts_menu_num_widget');
			$this->menu_config['thumb_width'] = (int) zamona_get_theme_options('ts_menu_thumb_width');
			$this->menu_config['thumb_height'] = (int) zamona_get_theme_options('ts_menu_thumb_height');
		}
	
		function start_lvl( &$output, $depth = 0, $args = array() ){
			$indent = str_repeat("\t", $depth);
			$output .= "\n$indent<ul class=\"sub-menu\">\n";
		}
		
		function end_lvl( &$output, $depth = 0, $args = array() ){
			$indent = str_repeat("\t", $depth);
			$output .= "$indent</ul>\n";
		}
		
		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ){
			global $wp_query;
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			$item_output = '';
			
			$sub_label_text = get_post_meta( $item->ID, '_menu_item_ts_sub_label_text', true );
			$is_megamenu = get_post_meta( $item->ID, '_menu_item_ts_is_megamenu', true );
			$megamenu_column = get_post_meta( $item->ID, '_menu_item_ts_megamenu_column', true );
			$static_html = get_post_meta( $item->ID, '_menu_item_ts_static_html', true );
			$static_html = stripslashes( $static_html );
			$sidebar = get_post_meta( $item->ID, '_menu_item_ts_sidebars', true );
			$thumbnail_id = get_post_meta( $item->ID, '_menu_item_ts_thumbnail_id', true );
			$background_id = get_post_meta( $item->ID, '_menu_item_ts_background_id', true );
			$background_repeat = get_post_meta( $item->ID, '_menu_item_ts_background_repeat', true );
			$background_position = get_post_meta( $item->ID, '_menu_item_ts_background_position', true );
			
			if( $depth === 0 ){
				$this->parent_megamenu = $is_megamenu;
				$this->megamenu_column = $megamenu_column;
				$this->parent_sidebar = $sidebar;
				$this->parent_static_html = $static_html;
			}
			
			/* Add item link - title */
			if( $depth === 0 || ($depth > 0 && !$this->parent_megamenu) ){
				$atts = array();
				$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
				$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
				$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
				$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

				$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
				
				if( $thumbnail_id ){
					if( isset($atts['class']) ){
						$atts['class'] .= ' has-icon';
					}
					else{
						$atts['class'] = 'has-icon';
					}
				}

				$attributes = '';
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}
					
				if( is_object($args) && isset($args->before) ){
					$item_output = $args->before;
				}else{
					$item_output = '';
				}
				
				$item_output .= "\n{$indent}\t<a". $attributes .'>';
				
				if( !isset($item->title) || strlen($item->title) <= 0 ){
					$item->title = $item->post_title;
				}
				$title = '<span class="menu-label">'.apply_filters( 'the_title', $item->title, $item->ID ).'</span>';
				
				if( $sub_label_text ){
					$title .= '<span class="menu-sub-label">'.esc_html($sub_label_text).'</span>';
				}
				
				$icon_html = '';
				if( $thumbnail_id > 0 ){
					$icon_html = '<span class="menu-icon">'.wp_get_attachment_image( $thumbnail_id, 'zamona_menu_icon_thumb', false).'</span>';
				}
				$item_output .= $icon_html;
				
				if( is_object($args) && isset($args->link_before) && isset($args->link_after) ){
					$item_output .= $args->link_before . $title . $args->link_after;
				}else{
					$item_output .= $title;
				}
				
				if( strlen($item->description) > 0 ){
					$item_output .= '<div class="menu-desc menu-desc-lv'.$depth.'">'.esc_html($item->description).'</div>';
				}
				
				$item_output .= '</a>';
				
				if( $item->sub_count > 0 || $this->parent_megamenu ){
					$item_output .= '<span class="ts-menu-drop-icon"></span>';
				}
			}
			
			if( $depth === 0 && $item->sub_count == 0 && $this->parent_megamenu && (!empty($sidebar) || !empty($static_html)) ){
				$item_output .= "\n$indent<ul class=\"sub-menu\">\n";
			}
			
			if( $this->parent_megamenu && ( ( $depth === 0 && $item->sub_count == 0 ) || $depth === 1 ) ){
				/* Add sidebar */
				if( $this->parent_sidebar ){
					$item_output .= '<li><div class="ts-megamenu-widgets-container ts-megamenu-container"><ul>';
					ob_start();
					dynamic_sidebar( $this->parent_sidebar );
					$item_output .= ob_get_clean();
					$item_output .= '</ul></div></li>';
					$this->parent_sidebar = '';
				}
				
				/* Add static html */
				if( $this->parent_static_html ){
					$item_output .= '<li><div class="ts-megamenu-static-html-container ts-megamenu-container">';
					$item_output .= do_shortcode($this->parent_static_html);
					$item_output .= '</div></li>';
					$this->parent_static_html = '';
				}
			}
			
			if( $depth === 0 && $item->sub_count == 0 && $this->parent_megamenu && (!empty($sidebar) || !empty($static_html)) ){
				$item_output .= "</ul>";
			}
			
			/* Add content into li */
			$class_names = $value = '';
			$classes = empty( $item->classes ) ? array() : ( array ) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
			if( $depth === 0 && $is_megamenu ){
				$classes[] = 'hide ts-megamenu ts-megamenu-columns-'.$megamenu_column;
				if( $megamenu_column == 0 ){
					$classes[] = 'ts-megamenu-fullwidth';
				}
				if( $megamenu_column == -1 ){
					$classes[] = 'ts-megamenu-fullwidth ts-megamenu-fullwidth-stretch';
				}
				if( empty($sidebar) && empty($static_html) ){
					$classes[] = 'no-sub-menu';
				}
			}
			
			if( $depth === 0 && !$is_megamenu ){
				$classes[] = 'ts-normal-menu';
			}
			
			if( $item->sub_count || ($depth === 0 && $this->parent_megamenu) ){
				$classes[] = 'parent';
			}
			
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
			
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
			
			$output .= $indent . '<li' . $id . $value . $class_names .'>';
			
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
		
		function end_el( &$output, $item, $depth = 0, $args = array() ) {
			$output .= "</li>\n";
		}
	}
}
?>