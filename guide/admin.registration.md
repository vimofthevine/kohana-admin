# Admin Module :: Extension Registration 

## Registering the Extension {#extension-registration}

[Admin Extensions](admin.terms#admin-extension) need to be registered to the
[Admin App](admin.terms#admin-app) in order to
show up in the main navigation and to be active.  Registration typically
takes place in a module's init.php file or in the application's bootstrap.php
file.  Registration involves specifying the name of the extension, the
admin navigation item the extension "belongs" to, and whether the extension
is the primary extension for the navigation item (ie, the extension directed
to by the navigation item link).  If no extension is specified as the primary,
the first extension registered will be used as the primary.  If no navigation
item is specified, it will be added to the navigation menu using the name
of the extension.

    Admin::register('extension_name', 'nav_item', TRUE);
    Admin::register('sub_extension', 'nav_item', FALSE);

[!!] REFINE the wording here regarding the menu.  There is a top-level nav item in the horizontal menu, and a sub-menu automatically added as a side-bar menu containing all extensions assigned to the nav item, if there is more than one

## Registering a Widget {#widget-registration}

If the extension provides a dashboard widget that should be displayed on the admin
home page, it must also be registered with the [Admin App](admin.terms#admin-app).
Registration involves specifying a short name to identify the widget and an
internal URL to generate the widget.

    Admin::dashboard('widget_name', 'widget_url');

Widget URLs may be obtained using the `admin/widget` route.

    $uri = Route::get('admin/widget')-»uri(array(
        'extension' =» 'extension_name',
        'action'    =» 'extension_action',
        'id'        =» 'id_if_needed',
    ));

## Registering a Sub-Menu {#submenu-registration}

If an extension requires a sub-menu to be displayed in the side-bar menu, it
must be registered with the [Admin App](admin.terms#admin-app). Registration
involves specifying the name of the sub-menu view class and an array of
extensions and actions for which to display the menu.

    // Menu is displayed for all user extension actions, and the blog extension's users action
    Admin::menu('View_Admin_Menu_Users', array('user', 'blog/users'));

    // Menu is displayed for the blog extension's comments action
    Admin::menu('View_Admin_Menu_Comments', 'blog/comments');

