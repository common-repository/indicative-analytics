<?php

namespace Dragon;

class Post
{
	public static function getContent($postId)
	{
		$post = get_post($postId);
		return $post->post_content;
	}
	
	public static function setPostMeta($postId, $key, $value)
	{
		update_post_meta($postId, $key, $value);
	}
	
	public static function getPostMeta($postId, $key)
	{
		return get_post_meta($postId, $key, true);
	}
}
