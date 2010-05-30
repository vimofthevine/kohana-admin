# The Admin Framework - Layout Integration

The admin module also provides a framework for creating administration pages.
The abstract base admin controller, `Controller_Admin`, may be extended to make
use of the template and other common functions.  The common functions provided
by the base admin controller are ACL checking (using Wouter's A2 library) and
request handling (differentiating between main/external requests and ajax/internal
requests).

## Request Handling and Layout Integration

Methods of admin controllers should create a view with minimal HTML and no concern
for the 960 grid used by the admin template.  This view should be assigned to
`$this->template->content`.  The `after()` method of the base admin controller will
insert the view in `$this->template->content` into a pre-defined layout view, which
integrates with the 960 CSS grid.

The layout view to use for each admin controller's methods can be defined in the
protected variable `$this->_view_map`.  Custom layout views may be created and used.
The base admin controller will set the `menu` and `content` variables for each layout
view.  The default map will be used if no mapping is defined.  For example,

    protected $_view_map = array(
        'list'    => 'admin/layout/narrow_column_with_menu',
        'edit'    => 'admin/layout/wide_column_with_menu',
        'history' => 'admin/layout/custom_layout',
        'default' => 'admin/layout/narrow_column',
    );

Admin controllers may define a private `_menu()` function to create a custom
sidebar-style menu which will be included with any layouts ending in "_with_menu".
The `_menu()` function should return either a string or View object.

