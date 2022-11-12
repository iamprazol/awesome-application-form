<?php
/**
 * Awesome Application Form Table List
 *
 * @version 1.0.0
 * @package  AwesomeApplicationForm/ListTable
 */

namespace  AwesomeApplicationForm\Admin;


defined( 'ABSPATH' ) || exit;

if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Appications table list class.
 */
class ListTable extends \WP_List_Table {

	/**
	 * Initialize the Appications table list.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'application',
				'plural'   => 'applications',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Retrieve applications data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_applications( $per_page = 5, $page_number = 1, $search ) {

		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}applicant_submissions";

		if ( '' !== $search ) {
			$sql .= $wpdb->prepare( " WHERE ( first_name LIKE %s ) OR  ( last_name LIKE %s ) OR  ( email LIKE %s ) OR  ( post_name LIKE %s )", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%");
		}

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}

	/**
	 * Delete a application record.
	 *
	 * @param int $id application ID
	 */
	public static function delete_application( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}applicant_submissions",
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}

	/**
	 * Get applications columns.
	 *
	 * @return array
	 */
	public function get_columns(){
		$columns = array(
			'cb'      => '<input type="checkbox" />',
			'name'     => esc_html__( 'Name', 'awesome-application-form' ),
			'address'    => esc_html__( 'Address', 'awesome-application-form' ),
			'email'      => esc_html__( 'Email', 'awesome-application-form' ),
			'phone'     => esc_html__( 'Mobile', 'awesome-application-form' ),
			'post_name'  => esc_html__( 'Post Name', 'awesome-application-form' ),
			'cv'         => esc_html__( 'CV', 'awesome-application-form' ),
			'date'       => esc_html__( 'Date', 'awesome-application-form' ),
		);

		return $columns;
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-item-selection[]" value="%s" />', $item['ID']
		);
	}

	/**
	 * Prepare table list items.
	*/
	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'applications_per_page', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();
		$search = '';

		// Handle the search query.
		if ( ! empty( $_REQUEST['s'] ) ) {
			$search = sanitize_text_field( trim( wp_unslash( $_REQUEST['s'] ) ) );
		}

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_applications( $per_page, $current_page, $search );
	}

	/**
	 * Counts the total applications in database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}applicant_submissions";

		return $wpdb->get_var( $sql );
	}

	/**
	 * Renders the columns.
	 *
	 * @param  object $application Application object.
	 * @param  string $column_name Column Name.
	 * @return string
	 */
	public function column_default( $application, $column_name ) {
		switch( $column_name ) {
			case 'name':
				$delete_nonce = wp_create_nonce( 'aaf-delete-application' );

				$title = '<strong>' . $application['first_name'] . ' ' . $application['last_name']. '</strong>';
				$actions = [
					'delete' => sprintf( '<a href="?page=%s&action=%s&application=%s&_wpnonce=%s">'. esc_html__( "Delete", "awesome-application-form" ) . '</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $application['ID'] ), $delete_nonce )
				];
				return $title . $this->row_actions( $actions );

			case 'address':
			case 'email':
			case 'phone':
			case 'post_name':
				return $application[ $column_name ];
			case 'cv':
				return '<a href="' . esc_url_raw( wp_get_attachment_url( $application[ $column_name ] ) ) . '" target="_blank" >' .  basename( get_attached_file( $application[ $column_name ] ) ) . '</a>';
			case 'date':
				$t_time = mysql2date(
					__( 'Y/m/d g:i:s A', 'awesome-application-form' ),
					$application['submitted_at'],
					true
				);
				$m_time = $application['submitted_at'];
				$time   = mysql2date( 'G', $application['submitted_at'] ) - get_option( 'gmt_offset' ) * 3600;

				$time_diff = time() - $time;

				if ( $time_diff > 0 && $time_diff < 24 * 60 * 60 ) {
					$h_time = sprintf(
						__( '%s ago', 'awesome-application-form' ),
						human_time_diff( $time )
					);
				} else {
					$h_time = mysql2date( __( 'Y/m/d', 'awesome-application-form' ), $m_time );
				}

				return '<abbr title="' . $t_time . '">' . $h_time . '</abbr>';
			default:
			return print_r( $application, true ) ; //Show the whole array for troubleshooting purposes
		}
	}


	/**
	 * Get a list of sortable columns.
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		$sortable_columns = array(
			'date' => array( 'submitted_at', false ),
		);

		return $sortable_columns;
	}


	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */

	protected function get_bulk_actions() {
		$actions = array(
			'bulk-delete'    => esc_html__('Delete', 'awesome-application-form' )
		);
		return $actions;
	}

	/**
	 * Render the list table page, including header, notices, status filters and table.
	 */
	public function display_page() {
		$this->prepare_items();
		?>
			<div class="wrap">
				<h1 class="wp-heading-inline"><?php esc_html_e( 'Awesome Applications' ); ?></h1>
				<hr class="wp-header-end">
				<form id="application-list" method="get">
					<input type="hidden" name="page" value="awesome-application-form" />
					<?php
						$this->views();
						$this->search_box( esc_html__( 'Search Applications', 'awesome-application-form' ), 'application' );
						$this->display();

						wp_nonce_field( 'save', 'awesome_application_list_nonce' );
					?>
				</form>
			</div>

		<?php
	}

	/**
	 * Process Bulk Action.
	 */
	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'aaf-delete-application' ) ) {
				die( esc_html__( 'Nonce error please reload', 'awesome-application-form' ) );
			}
			else {
				self::delete_application( absint( $_GET['application'] ) );
				wp_redirect( esc_url_raw( admin_url() . '?page=awesome-application-form') );
				exit;
			}

		}

		// If the delete bulk action is triggered
		$action = $this->current_action();
		if ( $action == 'bulk-delete' ) {
			$delete_ids = esc_sql( $_GET['bulk-item-selection'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_application( $id );

			}

			wp_redirect( esc_url_raw( admin_url() . '?page=awesome-application-form') );
			exit;
		}
	}

}
