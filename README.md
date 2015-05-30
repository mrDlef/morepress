# Morepress

Stuff made easily on Wordpress.

## Post

```php
// Create from wordpress post ID
$mp_post = new \Morepress\Post(23);

// Create from wordpress post object
global $post;
$mp_post = new \Morepress\Post($post);

// Check if post exists
if($mp_post->exists())
{
    // Do stuff
}

// Get post attribute
$mp_post->ID;
$mp_post->post_name;
// etc.

// Update post
$mp_post->update(array(
    'post_status' => 'publish',
));

// Add post meta
$mp_post->addMeta('meta_key', 'meta_value', true);

// Update post meta
$mp_post->updateMeta('meta_key', 'meta_value', 'prev_meta_value')

// Get post meta
$mp_post->getMeta('meta_key', true);

// Delete post meta
$mp_post->deleteMeta('meta_key', 'meta_value');

// Get post time
$mp_post->getTime();->ID, $meta_key, $meta_value);
```