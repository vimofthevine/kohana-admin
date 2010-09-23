# Admin Module :: Configuration 

Copy the admin config file from `MODPATH/admin/config/admin.php` to
`APPPATH/config/admin.php` and modify the settings as needed.

## `a2_instance_name`

The name of the A2 instance to use throughout the Admin Module.
Must match the name of the A2 config file.

## `widgets`

An array defining the order and placement of widgets on the dashboard.  If this array is left blank, the order and placement is determined by the order of widget registration.  The array may contain one to four arrays representing the columns of the dashboard.  Each array contains the name identifiers of dashboard widgets, from top to bottom.  If a widget is not registered, it is not displayed.

For example, a 3-column dashboard with 2 widgets, 3 widgets, and 
1 widget, respectively.

    return array(
        'widgets' => array(
            array('widget1', 'widget2'),
            array('widget3', 'widget4', 'widget5'),
            array('widget6'),
        ),
    );
