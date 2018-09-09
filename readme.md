# BEM nav filters

This is a standalone composer package for BEM classes in WordPress navigation.

The article Quick Tip: [BEM Naming and WordPress Filters for Navigation](https://webdesign.tutsplus.com/tutorials/quick-tip-bem-naming-and-wordpress-filters-for-navigation--cms-31268)
explains more about the logic.

## Requirements

* PHP 5.6+ (preferably 7+).
* [Composer](https://getcomposer.org/) for managing PHP dependencies.

## Installation

Open your command line tool and change directories to your WordPress theme folder.

```bash
cd path/to/wp-content/themes/<your-theme-name>
```

Then, use Composer to install the package.

```bash
composer samikeijonen/bem-nav-filters
```

If you're not already including the Composer autoload file for your theme
you'll want something like the following bit of code in your theme's `functions.php` to autoload this package (and any others).

The Composer autoload file will automatically load up filters file for you and make its code available for you to use.

```php
if ( file_exists( get_parent_theme_file_path( 'vendor/autoload.php' ) ) ) {
	require_once( get_parent_theme_file_path( 'vendor/autoload.php' ) );
}
```

Then init filters by this code in `functions.php`:

```php
// Init BEM nav filters.
\Foxland\BemNav\Filters::init();
```
