### Posts 2 Posts Adapter Class

Small class to make it a little easier to work with the WordPress Posts 2 Posts plugin in an object oriented manner.

Posts 2 Posts: https://github.com/scribu/wp-posts-to-posts/

### Example Usage

```php
use bermanco\Posts2PostsAdapter\Posts2PostsAdapter;

$p2p = Posts2PostsAdapter::create();
$p2p->get_connected_objects(
	1234 // WordPress Post ID of the post that you'd like to find connected objects for
	'posts_to_videos' // the handle of the Posts 2 Posts connection type
	'videos' // Restrict the results to a specific post type (optional)
	20 // Restrict the number of results returned (optional)
);
```
