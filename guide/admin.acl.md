# The Admin Framework - Access Control

The admin module also provides a framework for creating administration pages.
The abstract base admin controller, `Controller_Admin`, may be extended to make
use of the template and other common functions.  The common functions provided
by the base admin controller are ACL checking (using Wouter's A2 library) and
request handling (differentiating between main/external requests and ajax/internal
requests).

## Access Control

The base admin controller will perform an ACL check using Wouter's A2 library in the
`before()` method if the given action is defined in the protected variable,
`$this->_acl_required`.  The resource to check against is the value of the protected
`$this->_resource` variable (either the default string or the loaded resource).
The privilege to check against is the value derived from the request action to ACL
privilege array in the protected variable `$this->_acl_map`.  For example,

    protected $_resource = 'user';
    protected $_acl_required = array('list', 'edit', 'delete');
    protected $_acl_map = array(
        'list' => 'view',
        'edit' => 'edit',
        'delete' => 'edit',
        'default' => 'view',
    );

Will check if the current user is allowed...

 - "view" privilege on a "user" resource when accessing action_list
 - "edit" privilege on a "user" resource when accessing action_edit
 - "edit" privilege on a "user" resource when accessing action_delete
 - "view" privilege on a "user" resource for everything else

If no mapping is found, the default mapping will be used.

If the user is not allowed the specified privilege on the resource, the base
admin controller will redirect to

 - a login page if the user is not logged in already
 - the default method of the current controller
 - or the admin home page if the user cannot access the default method of the current controller (controller-level access denied)

