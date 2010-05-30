# The Admin Template

The front-end style used for the admin site is the admin template created by
[Colonel-Rosa](http://github.com/ThePixelDeveloper/Admin-Template).  All controllers
extending the `Controller_Template_Admin` class should put their views in
`$this->template->content`.  Colonel-Rosa used the 960 CSS grid, so the contents of
`$this->template->content` should contain the appropriate 960 grid classes (ie,
`<div class="grid_8">`).

The only configurable aspect of the template is the main navigation.  The config file,
`admin.php`, should be modified to specifiy the navigation menu.

    // admin.php example
    return array(
        'menu' => array(
            'Home' => 'admin/main',
            'Display text' => 'url',
        ),
    );

A controller may designate any of the links in the navigation as the active page by
including the following line, replacing "Display text" with the appropriate value.

    $this->_config['menu']['Display text'] = NULL;

