<?php

namespace Morepress\Meta_Box\Nav_Menu;

use \Morepress\Meta_Box as Meta_Box;
use \Morepress\Post_Type as Post_Type;

class Post_Type_Archive extends Meta_Box
{

	public function callback()
	{
		global $_nav_menu_placeholder, $nav_menu_selected_id;

		$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;
		
		/* get custom post types with archive support */
		$args = array('show_in_nav_menus' => true, 'has_archive' => true);
		$post_types = Post_Type::find($args, 'object');

		/* hydrate the necessary object properties for the walker */
		foreach ($post_types as &$post_type)
		{
			$post_type->classes = array();
			$post_type->type = $post_type->name;
			$post_type->object_id = $post_type->name;
			$post_type->title = $post_type->labels->name.' '.__('Archive', 'default');
			$post_type->object = 'cpt-archive';

			$post_type->menu_item_parent = null;
			$post_type->url = null;
			$post_type->xfn = null;
			$post_type->db_id = null;
			$post_type->target = null;
			$post_type->attr_title = null;
		}

		$walker = new \Walker_Nav_Menu_Checklist(array());
		?>
		<div id="cpt-archive" class="posttypediv">
			 <div id="tabs-panel-cpt-archive" class="tabs-panel tabs-panel-active">
				  <ul id="ctp-archive-checklist" class="categorychecklist form-no-clear">
						<?php echo walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $post_types), 0, (object) array('walker' => $walker)); ?>
				  </ul>
			 </div>
		</div>
		<p class="button-controls">
			 <span class="add-to-menu">
				  <input type="submit"<?php wp_nav_menu_disabled_check($nav_menu_selected_id); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-ctp-archive-menu-item" id="submit-cpt-archive" />
				  <span class="spinner"></span>
			 </span>
		</p>
		<?php
	}

	function menuFilter($items, $menu, $args)
	{
		/* alter the URL for cpt-archive objects */
		foreach ($items as &$item)
		{
			if ($item->object != 'cpt-archive') {
				continue;
			}

			$item->url = get_post_type_archive_link($item->type);

			/* set current */
			if (get_query_var('post_type') == $item->type)
			{
				$item->classes [] = 'current-menu-item';
				$item->current = true;
			}
		}

		return $items;
	}

}
