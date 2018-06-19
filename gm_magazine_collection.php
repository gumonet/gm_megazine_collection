<?php
/*
Plugin Name:  GM Magazine collection
Plugin URI:   http://gumonet.com
Description:  Crea una colección de revistas
Version:      0.0.1
Author:       Gumonet.com
Author URI:   http://gumonet.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  gmmc
Domain Path:  /languages
*/

/**
 *
 */
class Gm_Magazine_Collection
{
  const SLUG = 'gmmc';
  function __construct()
  {
      // Activation hook
  		register_activation_hook ( __FILE__, array (
  				$this,
  				'activate'
  		) );

  		// deactivation hook
  		register_deactivation_hook ( __FILE__, array (
  				$this,
  				'deactivate'
  		) );

  		// uninstall hook
  		/*register_uninstall_hook ( __FILE__, array (
  				$this,
  				'uninstall'
  		) );*/

      //add to sttyles
      add_action('admin_enqueue_scripts', array($this,'load_libreries'));
  		// init custom post types & taxonomies
  		add_action ( 'init', array (
  				$this,
  				'init'
  		) );
      add_action ( 'save_post_' . self::SLUG, array (
  				$this,
  				'savePost'
  		), 10, 3 );
  }

  public function init(){
    $args = array (
				'label' => 'Revistas',
				'labels' => array (
						'name' => 'Revistas',
						'singular_name' => 'Revista',
						'menu_name' => 'Revistas',
						'name_admin_bar' => 'Revistas',
						'all_items' => 'Revistas',
						'add_new' => 'Add Revista',
						'add_new_item' => 'Add New Revista',
						'edit_item' => 'Edit Revista',
						'new_item' => 'New Revista',
						'view_item' => 'View Revista',
						'search_items' => 'Search Revistas',
						'not_found' => 'No Revistas found',
						'not_found_in_trash' => 'No Revistas found in trash',
						'parent_item_colon' => ''
				),
				'description' => 'Revistas posts',
				'public' => false,
				'show_ui' => true,
				'menu_icon' => 'dashicons-media-text',
				'supports' => array (
						'title',
						'editor',
						//'custom-fields'
				),
				'has_archive' => false,
				'register_meta_box_cb' => array (
						$this,
						'addMetaBox'
				)
		);
     register_post_type ( self::SLUG, $args );
     //Add taxonomy
     $args = array (
         'public' => false,
         'show_ui' => true,
         'show_in_nav_menus' => true,
         'show_tagcloud' => false,
         'show_admin_column' => true,
         'hierarchical' => true
     );

     register_taxonomy ( self::SLUG . '_category', self::SLUG, $args );

  }

  /*
  *Load libraries
  */

 public function load_libreries(){
   //wp_enqueue_media();
    $screen = get_current_screen();
    if ( $screen->post_type == 'gmmc' ) {
        wp_enqueue_media();
    }
   wp_enqueue_script(
     'admin_script',
     plugins_url('admin/js/gmmc_scripts.js',__FILE__),
     ['jquery'],
     '1.0.0',
     true
   );
 }

  /**
	 * Register meta-box for addotional video params
	 */
	public function addMetaBox() {
		add_meta_box ( self::SLUG . '_meta', 'Datos del archivo', array (
				$this,
				'showMetaBox'
		), self::SLUG, 'normal', 'high' );
	}

  /**
	 * Shows meta-box
	 *
	 * @param unknown $post
	 */
	public function showMetaBox($post) {
		echo '
			<label>Archivo PDF:</label>
			<input type="text" name="pdf_url" id="pdf_url" value="' . esc_attr ( get_post_meta ( $post->ID, 'pdf_url', true ) ) . '" size="50">
      <button class="button button-primary button-large" id="gmm_btn_select_pdf_file">Seleccionar archivo</button>
				';
	}

/*Actualizando post*/
  public function savePost($post_id){
    if (isset ( $_POST ['action'] ) && $_POST ['action'] == 'editpost') {
      $current_pdf_url = stripslashes ( isset ( $_POST ['pdf_url'] ) ? $_POST ['pdf_url'] : '' );
      update_post_meta ( $post_id, 'pdf_url', $current_pdf_url );
    }
  }



  /**
	 * Activation function
	 */
	public function activate() {
		$this->init ();
		flush_rewrite_rules ();
		// add some settings
	}
  /**
   * Deactivation function
   */
  public function deactivate() {
  }

  /**
   * Uninstall function
   */
   public function uninstall() {
    // delete all video posts & taxonomies
  }

}

new Gm_Magazine_Collection();
