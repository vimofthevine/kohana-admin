# Admin Module :: Terms and Definitions 

The terms listed below are used throughout the Admin Module guide.

## ORM Model {#orm-model}

An ORM model is an application model created with one of the supported
ORM libraries (Sprig, Jelly, or Hive).  Every ORM Model that is
manageable through the Admin Module is associated with at least one
Admin Extension.

## Model Mapper {#model-mapper}

A Model Mapper is an adapter for the different ORM libraries used by
the [CRUD Admin Extension](#crud-extension).  A mapper class is
provided for all supported ORM libraries.  Rather than interfacing
with the [ORM Model](#orm-model) directly, the [Default Admin Extension]
(#default-extension) will execute methods of the Model Mapper,
which will perform the ORM library-specific methods for the given
ORM Model.

## Admin Extension {#admin-extension}

An Admin Extension is a set of actions and views for managing an
application resource.  Extensions are responsible for checking access
control and data validation.  Extensions may be associated with one
or more ORM Models.

Admin Extensions provide four types of results.

 - Status Code : corresponds to a subset of the HTTP response codes,
    indicating failure, success, etc.
     - 301 : indicates a permanent redirect
     - 200 : indicates that the action performed was successful
     - 400 : indicates that the action performed was unsuccessful
     - 403 : indicates that the requested action is not allowed
     - 404 : indicates that a requested resource was invalid or could
        not be found
 - Redirect URL : a URL to redirect to, typically post-action or
    upon an error
 - User Message : a message to display to the user, typically
    indicating success or failure
 - HTML response : an HTML view to display to the user
 - Layout Style : the intended layout style to inject the HTML
    response into (ie, full, narrow, wide, etc.)

## CRUD Admin Extension {#crud-extension}

The CRUD Admin Extension provides automated CRUD operations for an
[ORM Model](#orm-model).  The CRUD Admin Extension class must be
extended to be used and can be customized. See
[Developer's Guide: Using the CRUD Admin Extension](admin.crudextension).

## Default Admin Extension {#default-extension}

If no customization is desired for the [CRUD Admin Extension](#crud-extension)
, the Default Admin Extension may be used, specifying only the
[ORM Model](#orm-model).

## Admin Extension Controller {#extension-controller}

The Admin Extension Controller routes an external request to a specific
[Admin Extension](#admin-extension) and executes the requested action.
Based on the result of the extension action, the Admin Extension
Controller will perform a redirect or display the HTML view of the extension.

## Admin App {#admin-app}

The Admin App represents all pages associated to the administration of
a Kohana application.  It includes the login page, the home page
(dashboard), and all registered extensions.

