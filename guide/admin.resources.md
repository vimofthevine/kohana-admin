# The Admin Framework - Loading Resources

The admin module also provides a framework for creating administration pages.
The abstract base admin controller, `Controller_Admin`, may be extended to make
use of the template and other common functions.  The common functions provided
by the base admin controller are ACL checking (using Wouter's A2 library) and
request handling (differentiating between main/external requests and ajax/internal
requests).

## Loading Resources

As many back-end CRUD functions require a specific resource (object, database record, etc.)
to be specified and loaded, the base admin controller provides a hook for loading resources.
Additionally, the A2 authorization library allows object-level access, thus the loading
of a resource allows the A2 library to perform an ACL check on the loaded object.

Controllers can define the protected variable `$this->_resource_required` to trigger the
execution of a private function, `_load_resource()` in the `before()` method.
`$this->_resource_required` should be an array of request actions which require a specific
resource to be loaded.  The `_load_resource()` function should store the loaded resource
in the protected variable `$this->_resource`.  For example,

    protected $_resource = 'user';    // default string value
    protected $_resource_required = array('edit', 'delete');    // edit and delete actions require a specific user to be loaded

    private function _load_resource() {
        $id = $this->request->param('id', 0);
        if ($id === 0) return;

        $this->_resource = Sprig::factory('user', array('id' => $id))->load();

        if ( ! $this->_resource->loaded())
        {
            throw new Kohana_Exception('That user does not exist.', NULL, 404);
        }
    }

If a 404 exception is thrown, the base admin controller will set an error message
with the exception message, and redirect to the default method of the current controller.

