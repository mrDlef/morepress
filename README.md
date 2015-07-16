# Morepress

Stuff made easily on Wordpress.

## Installation

```shell
composer require daidais/morepress
```

## Post Features

### Create from wordpress post ID

```php
$mp_post = new \Morepress\Post(23);
```

### Create from wordpress post object

```php
global $post;
$mp_post = new \Morepress\Post($post);
```

### Check if post exists

```php
if($mp_post->exists())
{
    // Do stuff
}
```

### Get post attribute

```php
$mp_post->ID;
$mp_post->post_name;
// etc.
```

### Update post

```php
$mp_post->update(array(
    'post_status' => 'publish',
));
```

### Add post meta

```php
$mp_post->addMeta('meta_key', 'meta_value', true);
```

### Update post meta

```php
$mp_post->updateMeta('meta_key', 'meta_value', 'prev_meta_value')
```

### Get post meta

```php
$mp_post->getMeta('meta_key', true);
```

## Delete post meta

```php
$mp_post->deleteMeta('meta_key', 'meta_value');
```

### Get post time

```php
$mp_post->getTime();
```
