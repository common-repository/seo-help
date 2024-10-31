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
 * Qcld_seo_help_Scan_result_List_Table class that will display our custom table
 * records in nice table
 */
class Qcld_seo_help_Scan_result_List_Table extends WP_List_Table
{
    /**
     * [REQUIRED] You must declare constructor and give some basic params
     */
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular'  => 'qcld-seo-help-scan',
            'plural'    => esc_html__('Seo Help', 'seo-help'),
            //does this table support ajax?
            'ajax'      => true
        ));
    }


    /**
     * [OPTIONAL] this is example, how to render specific column
     *
     */
    function column_anchor($item)
    {
        //return $item['anchor'];
        /*$actions = array(
            'edit'          => sprintf('<a href="?page=qcld-seo-help-scan&view=edit_scan&id=%s">%s</a>', $item['loc_id'], __('Edit anchor text', 'qcld-seo-help')),
        );*/

        $actions = array();

        return sprintf('%s %s',
            $item['anchor'],
            $this->row_actions($actions)
        );
        
    }

    /**
     * [OPTIONAL] this is example, how to render specific column
     *
     */
    function column_content($item)
    {
    	//return $item['content'];Edit anchor text

        $item_content = $item['content'];
        if($item_content )

        $actions = array(
            'edit'          => sprintf('<a href="%s" target="_blank">%s</a>', $item['object_edit'], esc_html__('Edit', 'seo-help')),
            'delete'        => sprintf('<a href="%s" target="_blank">%s</a>', $item['object_trash'], esc_html__('Trash', 'seo-help')),
            'show_results'  => sprintf('<a href="%s" target="_blank">%s</a>', $item['object_view'], esc_html__('View', 'seo-help')),
        );

        return sprintf('%s %s',
            $item['content'],
            $this->row_actions($actions)
        );
    	
    }

    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     */
    function column_chunk($item)
    {

       // return $item['chunk'];
        $actions = array(
            'visit' => sprintf('<a href="%s" target="_blank">%s</a>', $item['chunk'], esc_html__('Visit URL', 'seo-help')),
        );

        return sprintf('%s %s',
            $item['chunk'],
            $this->row_actions($actions)
        );
    }

     function column_status_code($item)
    {
    	//return $item['status_code'];

        $actions = array();

        $status_check = esc_attr($item['status_code'], 'seo-help');

        switch ($status_check) {
            case "200":
                $status_check = '<p><strong style="color:green">'.$status_check. ' '.esc_html(' OK ', 'seo-help').' </strong></p>';
                break;
            default:
                $status_check = '<p><strong style="color:red">'.$status_check. ' '.esc_html(' Not Found ', 'seo-help').' </strong></p>';
        }

        return sprintf('%s %s',
            $status_check,
            $this->row_actions($actions)
        );
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['loc_id']
        );
    }

    /**
     * [REQUIRED] This method return columns to display in table
     */
    function get_columns()
    {
        $columns = array(
            'cb' 		=> '<input type="checkbox" />',
            'chunk' 	=> esc_html__('URL', 'qcld-seo-help'),
        );
        //return $columns;
		
		// All columns
		return array_merge($columns, array(
            'status_code' 	=> esc_html__('Status', 'seo-help'),
            'anchor'        => esc_html__('Anchor Text', 'seo-help'),
            'content'       => esc_html__('Content',  'seo-help'),
		));

    }

    /**
     * This method return columns that may be used to sort table
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'chunk'         => array('chunk', true),
            'status_code'   => array('status_code', true),
            'anchor'        => array('anchor', false),
            'content' 	    => array('content', false),
        );

        return $sortable_columns;
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
     * This method processes bulk actions
     */
    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'qcld_seo_help_urls_locations'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE loc_id IN($ids)");
            }
        }
    }

    /**
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $id  = isset($_REQUEST['id']) ? sanitize_text_field($_REQUEST['id']) : ''; // constant, how much records will be shown per page

        $msg  = isset($_REQUEST['msg']) ? sanitize_text_field($_REQUEST['msg']) : '';
        
        $table_name = $wpdb->prefix . "qcld_seo_help_urls_locations"; // do not forget about tables prefix

        $per_page = 20; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        // Data items
		//$columns = $this->setup_items();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        if(empty($msg)){
        $total_items = $wpdb->get_var("SELECT COUNT(loc_id) FROM $table_name WHERE scan_id IN($id)");
        }else{
        $total_items = $wpdb->get_var("SELECT COUNT(loc_id) FROM $table_name WHERE status_code=$msg AND scan_id IN($id)");            
        }

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']) : 1;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'loc_id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';

        $offset   = ( $paged * $per_page ) - $per_page;

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        if(empty($msg)){
            $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name  WHERE scan_id IN($id) ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $offset), ARRAY_A);  
        }else{
            $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name  WHERE status_code=$msg AND scan_id IN($id) ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $offset), ARRAY_A);  

        }

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' 	=> $total_items, // total items defined above
            'per_page' 		=> $per_page, // per page constant defined at top of method
            'total_pages' 	=> ceil($total_items / $per_page) // calculate pages count
        ));
    }
}


/**
 * List page handler
 */
function cltd_seo_help_scan_result_data_view()
{
    global $wpdb;

    $table = new Qcld_seo_help_Scan_result_List_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'seo-help'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>

	<div class="wrap">

	    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
	    <h2><?php esc_html_e('Scan Results', 'seo-help')?> </h2>
	    <?php 

        echo $message; 
        _e('<div class="qcld-sorting below-h2" id="message">');
        _e('<a href="?page=qcld-seo-help-scan&view=view_scan&id='.$_REQUEST['id'].'">All Results</a>', 'seo-help');
        _e('<a href="?page=qcld-seo-help-scan&view=view_scan&id='.$_REQUEST['id'].'&msg=200">200s Success</a>', 'seo-help');
        _e('<a href="?page=qcld-seo-help-scan&view=view_scan&id='.$_REQUEST['id'].'&msg=404">404s Errors</a>', 'seo-help');
        _e('</div>');
        ?>

	    <form id="qcld-scan-body-result-table" method="GET">
	        <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page'], 'seo-help'); ?>"/>
	        <?php $table->display() ?>
	    </form>

	</div>


<?php
	

}






