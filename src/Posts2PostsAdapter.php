<?php

namespace bermanco\Posts2PostsAdapter;

/**
 * Adapter for working with the Posts2Posts WordPress plugins.  Requires both 
 * WordPress (for $wpdb) and the Posts 2 Posts plugin to work properly
 */

class Posts2PostsAdapter {

	protected $wpdb;

	public function __construct(\wpdb $wpdb){
		$this->wpdb = $wpdb;
	}

	/**
	 * Get posts connected to a specific post ID and connection type.  This is
	 * using the Post2Posts native method, so it will be ordered properly but 
	 * may not perform as well as the direct database query.
	 * @param  int $post_id            WordPress post ID
	 * @param  string $connection_type P2P connection type
	 * @param  string|array $post_type Desired post type or array of post types
	 * @return array                   Array of post objects
	 */
	public function get_connected_objects(
		$post_id,
		$connection_type,
		$post_type = 'any',
		$number = -1
	){

		$query_args = array(
			'connected_type' => $connection_type,
			'connected_items' => $post_id,
			'suppress_filters' => false,
			'post_type' => $post_type,
			'posts_per_page' => $number
		);

		return $query_args;

	}

	/**
	 * Get Posts2Posts meta data value for a specific connection between
	 * two posts
	 * @param  int    $from_post_id    WP post ID for the "from" post
	 * @param  int    $to_post_id      WP post ID for the "to" post
	 * @param  string $connection_type P2P connection type
	 * @param  string $meta_key        Desired meta key
	 * @return string                  Connection meta data value
	 */
	public function get_connection_meta(
		$from_post_id,
		$to_post_id,
		$connection_type,
		$meta_key
	){

		$wpdb = $this->wpdb;
		$connection_table = $wpdb->prefix . 'p2p';
		$meta_table = $wpdb->prefix . 'p2pmeta';

		$query = "SELECT $meta_table.meta_value FROM $meta_table
LEFT JOIN $connection_table ON $connection_table.p2p_id = $meta_table.p2p_id
WHERE $connection_table.p2p_from = %d
AND $connection_table.p2p_to = %d
AND $meta_table.meta_key = %s";

		$prepared_query = $wpdb->prepare(
			$query,
			$from_post_id,
			$to_post_id,
			$meta_key
		);

		return $wpdb->get_var($prepared_query);

	}

	/**
	 * Get connected posts matching with matching connection meta key/value
	 * @param  int    $post_id         Post ID of the object
	 * @param  string $connection_type Posts2Posts connection type
	 * @param  string $key             Connection meta key 
	 * @param  string $value           Connection meta value
	 * @return array                   Array of post objects
	 */
	public function get_objects_by_connection_meta(
		$post_id,
		$connection_type,
		$key,
		$value
	){

		$query = array(
			'connected_items' => $post_id,
			'connected_type' => $connection_type,
			'connected_meta' => array(
				array(
					'key' => $key,
					'value' => $value,
					'compare' => '='
				)
			)
		);

		return $query;

	}

	/////////////
	// Factory //
	/////////////

	public static function create(){
		global $wpdb;
		return new self($wpdb);
	}

}

