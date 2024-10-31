<?php

defined('ABSPATH') or die("You can't access this file directly.");

/**
 * PART 2. Defining Custom Table List
 * ============================================================================
 */

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Qcld_seo_help_Scan_List_Table class that will display our custom table
 * records in nice table
 */
class Qcld_seo_help_Scan_header_result_List_Table extends WP_List_Table
{
    /**
     * [REQUIRED] You must declare constructor and give some basic params
     */
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular'  => 'seo_help_menu',
            'plural'    => esc_html__('Seo Help', 'seo-help'),
            //does this table support ajax?
            'ajax'      => true
        ));
    }


    /**
     * [OPTIONAL] this is example, how to render specific column
     *
     */
    function column_config($item)
    {
        
        $config = json_decode($item['config']);

        $data_item = '';

        $data_item .= '<p><b>'.esc_html('Scope', 'seo-help').'</b>: ';
        $data_item_scope = '';
        if(!empty($config->destination_type)):
        $data_item_scope .=  esc_html('Destination type:', 'seo-help').' '. $config->destination_type.', ';
        endif;
        if(!empty($config->destination_type)):
        $data_item_scope .=  esc_html('Time Scope:', 'seo-help').' '. $config->time_scope.', ';
        endif;
        if(!empty($config->notify_default)):
        $data_item_scope .=  esc_html('Notify Default:', 'seo-help').' '. $config->notify_default.', ';
        endif;
        if(!empty($config->notify_address)):
        $data_item_scope .=  esc_html('Notify Address:', 'seo-help').' '. $config->notify_address.', ';
        endif;
        if(!empty($config->notify_address_email)):
        $data_item_scope .=  esc_html('Notify Email Address:', 'seo-help').' '. $config->notify_address_email.', ';
        endif;
        $data_item .= substr($data_item_scope, 0, -2);
        $data_item .= '</p>';

        $data_item .= '<p><b>'.esc_html('Post types', 'seo-help').'</b>: ';
        $data_item_post = '';
        foreach($config->post_types as $post){
            $data_item_post .= $post.', ';

        }

        $data_item .= substr($data_item_post, 0, -2);
        $data_item .= ' </p>';

        $data_item .= '<p><b>'.esc_html('Post types', 'seo-help').'</b>: ';
        $data_item_status = '';
        foreach($config->post_status as $status){
            $data_item_status .= $status.', ';

        }
        $data_item .= substr($data_item_status, 0, -2);
        $data_item .= ' </p>';

        return $data_item;
        
        
    }

    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     */
    function column_name($item)
    {
        if($item['status'] == 'running' || $item['status'] == 'completed'){
            $actions = array(
                'show_results'  => sprintf('<a href="?page=qcld-seo-help-scan&view=view_scan&id=%s">%s</a>', $item['scan_id'], __('Show All results', 'qcld-seo-help')),
                'show_resultss'  => sprintf('<a href="?page=qcld-seo-help-scan&view=view_scan&id=%s&msg=404">%s</a>', $item['scan_id'], __('Show 404s Only', 'qcld-seo-help')),
                'edit'          => sprintf('<a href="?page=qcld-seo-help-scan&view=edit_scan&id=%s">%s</a>', $item['scan_id'], __('Edit Scan', 'qcld-seo-help')),
                'delete'        => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['scan_id'], __('Delete', 'qcld-seo-help')),
            );
        }else{
            $actions = array(
                'show_results'  => sprintf('<a href="?page=qcld-seo-help-scan&view=view_scan&id=%s">%s</a>', $item['scan_id'], __('Show results', 'qcld-seo-help')),
                'edit'          => sprintf('<a href="?page=qcld-seo-help-scan&view=edit_scan&id=%s">%s</a>', $item['scan_id'], __('Edit Scan', 'qcld-seo-help')),
                'start_crawler' => sprintf('<a href="?page=qcld-seo-help-scan&view=start_crawler&id=%s">%s</a>', $item['scan_id'], __('Start crawler', 'qcld-seo-help')),
                'delete'        => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['scan_id'], __('Delete', 'qcld-seo-help')),
            );
        }

        // Created date
        $time_created = strtotime( $item['created_at'].' UTC');
        $offset_time = get_option('gmt_offset') * HOUR_IN_SECONDS;
        
        // Current dates
        $today_date = gmdate('d/m/Y', time() + $offset_time);
        $yesterday_date = gmdate('d/m/Y', time() + $offset_time - 86400);
        
        // Local date and hour
        $created_date = gmdate('d/m/Y', $time_created + $offset_time);
        $created_hour = gmdate('H:i', $time_created + $offset_time);

        // Check today
        if ($created_date == $today_date) {
            
            // Created today
            $timeinfo = sprintf(__('Created today at %s', 'qcld-seo-help'), $created_hour);
        
        // Yesterday
        } elseif ($created_date == $yesterday_date) {
            
            // Created yesterday
            $timeinfo = sprintf(__('Created yesterday at %s', 'qcld-seo-help'), $created_hour);
        
        // Any date
        } else {
            
            // Created date and hour
            $timeinfo = sprintf(__('Created %s at %s', 'qcld-seo-help'), $created_date, $created_hour);
        }

        $status_info = $item['status'];
        if($status_info == 'running'){
            $status_info_msg = '<span class="running_msg">'.esc_html('Running', 'seo-help').'</span>';
            $ready_to_crawler = ''; 
            $scan_results = '';

        }else if($status_info == 'completed'){
            $status_info_msg = '<span class="completed_msg">'.esc_html('Completed', 'seo-help').'</span>';
            $ready_to_crawler = '';

            global $wpdb;
            $id  = $item['scan_id'];
            $table_name_scan = $wpdb->prefix . "qcld_seo_help_urls_locations";
            $total_result_counts = $wpdb->get_var("SELECT COUNT(loc_id) FROM $table_name_scan WHERE scan_id IN($id)");

            $total_unique_url = $wpdb->get_var("SELECT COUNT( DISTINCT(raw_url)) FROM $table_name_scan WHERE scan_id IN($id)");

            $scan_results = '<p><b>'.$total_result_counts.'</b> '.esc_html('Results', 'seo-help').' - <b>'.$total_unique_url.'</b> '.esc_html('Unique URLs', 'seo-help').'</p>';

        }else{

            $status_info_msg = '';   
            $scan_results = '';
            $ready_to_crawler = '<p>'.esc_html('Ready to', 'seo-help').' <a class="qcld_start_crawler" href="?page=qcld-seo-help-scan&view=start_crawler&id='.$item['scan_id'].'">'.esc_html('start the crawler', 'seo-help').'</a></p>';         
        }

        
        $scan_title = '<a href="?page=qcld-seo-help-scan&view=view_scan&id='.$item['scan_id'].'">'.$item['name'].'</a>';
                
        $result = $status_info_msg. $scan_title . $ready_to_crawler.'<p><i class="fa fa-clock-o" aria-hidden="true"></i> '.$timeinfo.'</p>'.$scan_results;


        return sprintf('%s %s',
            $result,
            $this->row_actions($actions)
        );
    }

    function get_columns()
    {
        $columns = array(
            'name'      => esc_html__('Scan info', 'seo-help'),
        );
        
        // All columns
        return array_merge($columns, array(
            'config'    => esc_html__('Configuration',  'seo-help'),
        ));

    }

    /**
     * Return array of bult actions if has any
     */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }


    /**
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $id  = $_REQUEST['id'];
        $table_name = $wpdb->prefix . 'qcld_seo_help_scans'; 

        $per_page = 10;

        $columns = $this->get_columns();
        // Data items
        //$columns = $this->setup_items();
        $hidden = array();
        //$sortable = $this->get_sortable_columns();
        $sortable = array();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
       // $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(scan_id) FROM $table_name WHERE scan_id IN($id)");

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'scan_id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE scan_id IN($id) ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

    }
}

/**
 * List page handler
 */
if ( ! function_exists( 'cltd_seo_help_scan_header_result_data_view' ) ) {
    function cltd_seo_help_scan_header_result_data_view(){

        global $wpdb;

        $table = new Qcld_seo_help_Scan_header_result_List_Table();
        $table->prepare_items();

        $message = '';
        if ('delete' === $table->current_action()) {
            $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'seo-help'), count($_REQUEST['id'])) . '</p></div>';
        }
        ?>

    	<div class="wrap">

    	    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    	    <h2><?php esc_html_e('Scan Results', 'seo-help')?> </h2>
    	    <?php echo $message; ?>

    	    <form id="qcld-scan-header-result-table" method="GET">
    	        <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page'], 'seo-help'); ?>"/>
    	        <?php $table->display() ?>
    	    </form>

    	</div>


    <?php
    	
    }

}
