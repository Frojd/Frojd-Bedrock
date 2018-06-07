# ACF Field Type Template

Welcome to the Advanced Custom Fields field type template repository.
Here you will find a starter-kit for creating a new ACF field type. This starter-kit will work as a normal WP plugin.

For more information about creating a new field type, please read the following article:
http://www.advancedcustomfields.com/resources/tutorials/creating-a-new-field-type/

-----------------------

# ACF Icon Field

Icon selector field for acf

-----------------------

### Description

Icon selector field for acf which takes all the files in a specific directory in your theme and creates a selector with it in admin.

### Compatibility

This ACF field type is compatible with:
* ACF 5

### Installation

1. Copy the `acf-field-icon` folder into your `wp-content/plugins` folder
2. Activate the Icon plugin via the plugins admin page
3. Create a directory in the root of your theme called 'acf-field-icons' and add your icons in it.
4. Create a new field via ACF and select the Icon type
5. Define specific or other file formats instead of default "svg,jpg,png" for each field
6. Define specific or other icon directory for each field instead of "acf-field-icons"

### Changelog
Please see `readme.txt` for changelog
