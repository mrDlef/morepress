# Morepress

Stuff made easily on Wordpress.

## Installation

```shell
composer require daidais/morepress
```
## Features

### Post

Create from id:

```php
$mp_post = new \Morepress\Post(23);
```

Create from \WP_Post:

```php
global $post;
$mp_post = new \Morepress\Post($post);
```

Check if exists:

```php
if($mp_post->exists())
{
    // Do stuff
}
```

Get attribute:

```php
$mp_post->ID;
$mp_post->post_name;
// etc.
```

Update post:

```php
$mp_post->update(array(
    'post_status' => 'publish',
));
```

Add meta:

```php
$mp_post->addMeta('meta_key', 'meta_value', true);
```

Update meta:

```php
$mp_post->updateMeta('meta_key', 'meta_value', 'prev_meta_value')
```

Get meta:

```php
$mp_post->getMeta('meta_key', true);
```

Delete meta:

```php
$mp_post->deleteMeta('meta_key', 'meta_value');
```

Get time:

```php
$mp_post->getTime();
```
