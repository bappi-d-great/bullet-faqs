<?php

class FAQ_Widget extends WP_Widget {
    
    public $options;
    
    //Constructor
    public function __construct() {
        parent::__construct('faq_widget', __('FAQ Widget', LANG_DOMAIN), array( 'description' => __( 'A FAQ Widget', LANG_DOMAIN ), ));
        $this->options = get_option('faq_options');
    }
    
    /*
     * 
     * Widget Initialization
     * 
     */
    public function widget( $args, $instance ) {
        $catid = apply_filters( 'widget_title', $instance['catid'] );
        if ( ! empty( $catid ) ) {
            $cat = get_term( $catid, 'faq_categories' );
            $html = '';
            $data = $this->options;
            if(!isset($data['theme'])) $data['theme'] = 'theme-1';
            if(!isset($data['expand'])) $data['expand'] = 'false';
            if(!isset($data['faq_speed']) || $data['faq_speed'] == '') $data['faq_speed'] = 500;
            include plugin_dir_path( __FILE__ ).'../templates/widget_view.php';
            echo $html;
        }
    }
    
    /*
     * 
     * Widget Front End
     * 
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'catid' ] ) ) {
            $catid = $instance[ 'catid' ];
        }
        else {
            $catid = __( 'New ID', 'faq' );
        }
        ?>
        <p>
        <label for="<?php echo $this->get_field_name( 'catid' ); ?>"><?php _e( 'Category ID:', LANG_DOMAIN ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'catid' ); ?>" name="<?php echo $this->get_field_name( 'catid' ); ?>" type="text" value="<?php echo esc_attr( $catid ); ?>" />
        </p>
        <?php 
    }
       
    /*
     * 
     * Widget Update
     * 
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['catid'] = ( ! empty( $new_instance['catid'] ) ) ? strip_tags( $new_instance['catid'] ) : '';

        return $instance;
    }
    
}


