# Admin Module :: Creating a Custom Extension 

A custom extension is created in accordance with the following conventions:

- File must reside in the classes/admin/extension folder and the class named Admin_Extension_EXTENSIONNAME 
    - Extension names may have underscores (and sub-folders accordingly)
    - Extension class must implement the Admin_Extension_Interface interface
        - Required methods to implement are
            - get_status   : returns the result of the action as an HTTP response code
            - get_redirect : returns a post-action redirection URL
            - get_message  : returns a short message to display to the user
            - get_response : returns an HTML response (form, list, etc.)
            - get_layout   : returns the admin layout view to use 
            - action_index : the default action.  If this is not the default action for the custom extension, set a 301 status and redirect to the desired default action
        - All action methods must be prepended with "action_" 

URLs for the extension may be obtained using the `admin/extension` route.

    $uri = Route::get('admin/extension')->uri(array(
        'extension' => 'extension_name',
        'action'    => 'extension_action',
        'id'        => 'id_if_needed',
    ));
