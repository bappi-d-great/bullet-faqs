<?php
/*
Plugin Name: Bullet FAQs
Plugin URI: http://bappi-d-great.com
Description: Provides nice Frequently Asked Questions Page with answers hidden untill the question is clicked then the desired answer fades smoothly into view, like accordion. User will have options to add categories, and questions based on those categories. Users can show question from a single category using shortcode. They will have control to change theme (among 9 themes), animation speed and custom CSS.
Version: 2.1.8
Author: Bappi D Great
Author URI: http://bappi-d-great.com
License: GPLv2 or later
*/

define ('LANG_DOMAIN', 'bllfaq');
define ('PLUGIN_PATH_CSS', plugins_url( 'css/faqs.css', __FILE__ ));
define ('PLUGIN_PATH_JS', plugins_url( 'js/faqAccordion.js', __FILE__ ));

require_once 'lib/base.php';
require_once 'lib/widget.php';

//Defining main class
class FAQ extends BASE
{
    
    public $options;
    
    //Constructor
    public function __construct() {
        parent::__construct();
        $this->faq_init();
        $this->options = get_option('faq_options');
	
	add_action('plugin_loaded', array($this, 'localization'));
	
    }
    
    //Language define
    public function localization() {
        if($this->location == 'plugins') {
            load_plugin_textdomain('csslang', FALSE, '/lang/');
        }
        $temp_locale = explode('_', get_locale());
        $this->language = ($temp_locale) ? $temp_locale[0] : 'en';
    }
    
    /*
     * 
     * Initialize all required methods
     * 
     */
    public function faq_init() {
        add_action('init', array($this, 'faq_category'));
        add_action('init', array($this, 'create_post_type'));
        add_shortcode( 'show_faq', array($this, 'faq_shortcode') );
        add_action('admin_menu' , array($this, 'register_faq_settings')); 

        
        //Adding styles and scripts
        add_action( 'wp_enqueue_scripts', array($this, 'user_faq_styles') );
        
        //Adding custom columns in taxonomy
        add_action( "manage_edit-faq_categories_columns",          array($this, 'posts_columns_id') );
        add_filter( "manage_edit-faq_categories_sortable_columns", array($this, 'posts_columns_id') );
        add_filter( "manage_faq_categories_custom_column",         array($this, 'posts_custom_id_columns'), 10, 3 );
        
        
        //Adding widget
        add_action( 'widgets_init', array($this, 'register_faq_widget') );
        
        add_action('admin_init', array($this, 'register_settings_and_fields'));
    }
    
    
    /*
     * 
     * ShortCode Enabling and displaying into front end
     * 
     */
    public function faq_shortcode($atts) {
        extract( shortcode_atts( array(
		'id' => ''
	), $atts ) );
  
        $html = '';
        $data = $this->options;
	
	if(!isset($data['theme'])) $data['theme'] = 'theme-1';
	if(!isset($data['expand'])) $data['expand'] = 'false';
	if(!isset($data['faq_speed']) || $data['faq_speed'] == '') $data['faq_speed'] = 500;
	
        if($id != '')
        {
            $cat = get_term( $id, 'faq_categories' );
            include 'templates/category_view.php';
        }
        else
        {
            $cat = get_terms('faq_categories');
            include 'templates/all_view.php';
        }
        
        return $html;
    }
    
    /*
     * Registering widget
     */
    public function register_faq_widget() {
        register_widget( 'faq_widget' );
    }
    
    /*
     * Adding Settings page in FAQ Menu
     */
    public function register_faq_settings() {
        add_submenu_page('edit.php?post_type=faq', 'FAQ Settings', 'FAQ Settings', 'edit_posts', 'faq_settings', array($this, 'faq_settings'));
        add_action('admin_init', array($this, 'service_settings_store'));
    }
    
    public function service_settings_store() {
        
    }
    
    /*
     * Settings page view in Dashboard
     */
    public function faq_settings() {
        if (!current_user_can('manage_options')) {  
            wp_die(__('You do not have sufficient permissions to access this page.', LANG_DOMAIN));  
        }

        ?>
        <div class="wrap rev-admin">
            <?php screen_icon('tools'); ?>
            <h2><?php _e('Faq Settings', LANG_DOMAIN); ?></h2>
            <form method="post" action="options.php"> 
                <?php settings_fields('faq_options'); ?>
                <?php do_settings_sections('faq_settings'); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    
    /*
     * Fields for FAQ Settings Page
     */
    public function register_settings_and_fields() {
        
        register_setting('faq_options', 'faq_options');
        
        add_settings_section('faq_main_section', __('Faq Settings', LANG_DOMAIN), array($this, 'faq_main_sec_cb'), 'faq_settings');
        add_settings_field('faq_theme', __('Choose a theme:', LANG_DOMAIN), array($this, 'faq_theme'), 'faq_settings', 'faq_main_section');
        add_settings_field('faq_expand', __('Enable expand all rows:', LANG_DOMAIN), array($this, 'faq_expand'), 'faq_settings', 'faq_main_section');
        add_settings_field('faq_speed', __('Animation Speed (in milliseconds):', LANG_DOMAIN), array($this, 'faq_speed'), 'faq_settings', 'faq_main_section');
        add_settings_field('faq_css', __('Custom CSS:', LANG_DOMAIN), array($this, 'faq_css'), 'faq_settings', 'faq_main_section');
    }
    
    /*
     * Callbacks for Settings Page
     */
    public function faq_theme() {
        $html = "<select name='faq_options[theme]'>";
        $html .= "<option value='theme-1' ". (($this->options['theme'] == 'theme-1') ? 'selected' : '').">". __('Beige', LANG_DOMAIN) . "</option>";
        $html .= "<option value='theme-2' ". (($this->options['theme'] == 'theme-2') ? 'selected' : '').">". __('Green', LANG_DOMAIN) . "</option>";
        $html .= "<option value='theme-3' ". (($this->options['theme'] == 'theme-3') ? 'selected' : '').">". __('Cyan', LANG_DOMAIN) . "</option>";
        $html .= "<option value='theme-4' ". (($this->options['theme'] == 'theme-4') ? 'selected' : '').">". __('White', LANG_DOMAIN) . "</option>";
        $html .= "<option value='theme-5' ". (($this->options['theme'] == 'theme-5') ? 'selected' : '').">". __('Olive', LANG_DOMAIN) . "</option>";
        $html .= "<option value='theme-6' ". (($this->options['theme'] == 'theme-6') ? 'selected' : '').">". __('Purple', LANG_DOMAIN) . "</option>";
        $html .= "<option value='theme-7' ". (($this->options['theme'] == 'theme-7') ? 'selected' : '').">". __('Blue', LANG_DOMAIN) . "</option>";
        $html .= "<option value='theme-8' ". (($this->options['theme'] == 'theme-8') ? 'selected' : '').">". __('Brown', LANG_DOMAIN) . "</option>";
        $html .= "<option value='theme-9' ". (($this->options['theme'] == 'theme-9') ? 'selected' : '').">". __('Aquamarine', LANG_DOMAIN) . "</option>";
        $html .= "</select>";
        echo $html;
    }
    
    public function faq_expand() {
        $html = "<select name='faq_options[expand]'>";
        $html .= "<option value='false' ". (($this->options['expand'] == 'false') ? 'selected' : '').">". __('No', LANG_DOMAIN) ."</option>";
        $html .= "<option value='true' ". (($this->options['expand'] == 'true') ? 'selected' : '').">". __('Yes', LANG_DOMAIN) ."</option>";
        $html .= "</select>";
        echo $html;
    }
    
    public function faq_speed() {
        echo "<input type='text' name='faq_options[faq_speed]' value='{$this->options['faq_speed']}' /> ". __('The lower the value, the faster the animation (default: 500)', LANG_DOMAIN);
    }
    
    public function faq_css() {
        echo "<textarea name='faq_options[faq_css]' rows='10' cols='80'>{$this->options['faq_css']}</textarea>";
    }


    public function faq_main_sec_cb() {
        
    }
    
}

$faq = new FAQ();