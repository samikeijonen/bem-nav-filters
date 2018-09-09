<?php
/**
 * Filter functions.
 *
 * This file holds functions that are used for filtering.
 *
 * @package BemNavFilters
 */

namespace Foxland\BemNav;

/**
 * Handles filters related to navigation BEM classes.
 *
 * @since  1.0.0
 * @access public
 */
class Filters {
	/**
	 * Adds filters on the appropriate navigation hooks.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function init() {
		add_filter( 'nav_menu_css_class', [ $this, 'nav_menu_css_class' ], 10, 4 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'nav_menu_link_attributes' ], 10, 4 );
		add_filter( 'page_css_class', [ $this, 'page_css_class' ], 10, 5 );
		add_filter( 'page_menu_link_attributes', [ $this, 'page_menu_link_attributes' ], 10, 5 );
		add_filter( 'nav_menu_submenu_css_class', [ $this, 'nav_menu_submenu_css_class' ], 10, 3 );
		add_filter( 'post_class', [ $this, 'entry_classes' ], 10, 3 );
	}

	/**
	 * Filters the CSS classes applied to a menu item's list item element.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
	 * @param  WP_Post  $item    The current menu item.
	 * @param  stdClass $args    An object of wp_nav_menu() arguments.
	 * @param  int      $depth   Depth of menu item. Used for padding.
	 */
	public function nav_menu_css_class( $classes, $item, $args, $depth ) {
		// Get theme location, fallback for `default`.
		$theme_location = $args->theme_location ? $args->theme_location : 'default';

		// Reset all default classes and start adding custom classes to array.
		$_classes = [ 'menu__item' ];

		// Add theme location class.
		$_classes[] = 'menu__item--' . $theme_location;

		// Add a class if the menu item has children.
		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$_classes[] = 'has-children';
		}

		// Add `is-top-level` example class using $depth parameter.
		if ( 0 === $depth ) {
			$_classes[] = 'is-top-level';
		}

		// Return custom classes.
		return $_classes;
	}

	/**
	 * Filters the WP nav menu link attributes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array    $atts {
	 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
	 *
	 *     @type string $title  Title attribute.
	 *     @type string $target Target attribute.
	 *     @type string $rel    The rel attribute.
	 *     @type string $href   The href attribute.
	 * }
	 * @param  WP_Post  $item  The current menu item.
	 * @param  stdClass $args  An object of wp_nav_menu() arguments.
	 * @param  int      $depth Depth of menu item. Used for padding.
	 * @return string   $attr
	 */
	public function nav_menu_link_attributes( $atts, $item, $args, $depth ) {
		// Get theme location, fallback for `default`.
		$theme_location = $args->theme_location ? $args->theme_location : 'default';

		// Start adding custom classes.
		$atts['class'] = 'menu__anchor menu__anchor--' . $theme_location;

		// Add `menu__anchor--button` when there is `button` class in `<li>` element.
		if ( in_array( 'button', $item->classes, true ) && 'primary' === $args->theme_location ) {
			$atts['class'] .= ' menu__anchor--button';
		}

		// Add `is-ancestor` class.
		if ( in_array( 'current-page-ancestor', $item->classes, true ) || in_array( 'current-menu-ancestor', $item->classes, true ) ) {
			$atts['class'] .= ' is-ancestor';
		}

		// Add `is-active` class.
		if ( in_array( 'current-menu-item', $item->classes, true ) ) {
			$atts['class'] .= ' is-active';
		}

		// Add `is-top-level` example class using $depth parameter.
		if ( 0 === $depth ) {
			$atts['class'] .= ' is-top-level';
		}

		// Return custom classes.
		return $atts;
	}

	/**
	 * Filters the list of CSS classes to include with each page item in the list.
	 *
	 * @see    wp_list_pages()
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string[] $css_class    An array of CSS classes to be applied to each list item.
	 * @param  WP_Post  $page         Page data object.
	 * @param  int      $depth        Depth of page, used for padding.
	 * @param  array    $args         An array of arguments.
	 * @param  int      $current_page ID of the current page.
	 */
	public function page_css_class( $css_class, $page, $depth, $args, $current_page ) {
		$css_class = [ 'menu__item menu__item--sub-pages' ];

		if ( in_array( 'page_item_has_children', $css_class, true ) ) {
			$css_class[] = 'has-children';
		}

		if ( in_array( 'current_page_ancestor', $css_class, true ) ) {
			$css_class[] = 'menu__item--ancestor';
		}

		if ( 0 === $depth ) {
			$css_class[] = 'is-top-level';
		}

		return $css_class;
	}

	/**
	 * Filters the HTML attributes applied to a page menu item's anchor element.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array   $atts {
	 *       The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
	 *
	 *     @type string $href The href attribute.
	 * }
	 * @param  WP_Post $page         Page data object.
	 * @param  int     $depth        Depth of page, used for padding.
	 * @param  array   $args         An array of arguments.
	 * @param  int     $current_page ID of the current page.
	 */
	public function page_menu_link_attributes( $atts, $page, $depth, $args, $current_page ) {
		$atts['class'] = 'menu__anchor menu__anchor--sub-pages';

		if ( $current_page === $page->ID ) {
			$atts['class'] .= ' is-active';
		}

		if ( $args['has_children'] ) {
			$atts['class'] .= ' has-children';
		}

		if ( 0 === $depth ) {
			$atts['class'] .= ' is-top-level';
		}

		return $atts;
	}

	/**
	 * Adds a custom class to the submenus in nav menus.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
	 * @param  stdClass $args    An object of `wp_nav_menu()` arguments.
	 * @param  int      $depth   Depth of menu item. Used for padding.
	 */
	public function nav_menu_submenu_css_class( $classes, $args, $depth ) {
		$classes = [ 'menu__sub-menu' ];

		return $classes;
	}

	/**
	 * Remove hentry and add entry at the same time.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string[] $classes An array of post class names.
	 * @param  string[] $class   An array of additional class names added to the post.
	 * @param  int      $post_id The post ID.
	 */
	public function entry_classes( $classes, $class, $post_id ) {
		if ( is_admin() ) {
			return $classes;
		}

		$classes = [];
		$post    = get_post( $post_id );

		// Entry class.
		$classes[] = 'entry';

		// Post field classes.
		$classes[] = sprintf( 'entry--%s', $post_id );
		$classes[] = sprintf( 'entry--type-%s', get_post_type() );

		// Author class.
		$classes[] = sprintf(
			'entry--author-%s',
			sanitize_html_class( get_the_author_meta( 'user_nicename' ), get_the_author_meta( 'ID' ) )
		);

		return $classes;
	}
}
