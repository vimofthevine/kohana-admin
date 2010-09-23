# Admin Module :: Extension Routing and Processing 

Admin extension routes take the form of

    admin/<extension>(/<action>(/<id>))

and are routed to the [Admin Extension Controller](admin.terms#extension-controller).
The Admin Extension Controller performs the following steps:

1. Loads the requested [Admin Extension](admin.terms#admin-extension)
    1. Checks that extension is in the list of registered extensions
        1. If it is not registered, a 404 not found page is shown
    1. Checks that requested extension exists
        1. If it exists, creates an instance of the extension
        1. If it does not exist, checks if requested extension is an
            [ORM Model](admin.terms#orm-model)
            1. If the [ORM Model](admin.terms#orm-model) exists, creates
                an instance of the [Default Admin Extension](admin.terms#default-extension)
                with the [ORM Model](admin.terms#orm-model)
        1. If the [Admin Extension](admin.terms#admin-extension) and
            [ORM Model](admin.terms#orm-model) do not exist, a 404 not found
            page is shown.
    1. Checks that requested action exists for the loaded extension
        1. If the action does not exist, a 404 not found page is shown
1. Executes the extension action
1. According to the results of the extension action, the [Admin Extension Controller](admin.terms#extension-controller) will
    1. Redirect to the login page if access is denied (403 status) and the user is not logged in
    1. Redirect to the previous page if access is denied (403 status) and the user is logged in
        1. Setting a session-based flash message if a user message is given
    1. Redirect to the given redirect URL if the resource is invalid (404 status)
        1. Setting a session-based flash message if a user message is given
    1. Redirect to the given redirect URL if given with a success or failure status
        1. Setting a session-based flash message if a user message is given
    1. Display the HTML response, injecting it into the given layout style
