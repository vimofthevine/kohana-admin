# Admin Module :: Using the CRUD Admin Extension 

The [CRUD Admin Extension](admin.terms#crud-extension) automates the
typical object management functions of

1. Listing object records
1. Creating a new object record
1. Updating an object record
1. Deleting an object record
1. Listing the modification history of an object record

To use the [CRUD Admin Extension](admin.terms#crud-extension), a new
extension class must be created that extends the `Admin_Extension_Crud`
extension class.  This new class must set a few necessary configuration
parameters.  These can be set in the class constructor before calling
the parent constructor.

    public function __construct()
    {
        // The ORM Model class name, without the "Model_" prefix
        public $model;

        // The Model Mapper class name or object instance
        public $mapper;

        parent::__construct();
    }

If no customization of the CRUD extension is necessary, the
[ORM Model](admin.terms#orm-model) class name may be used for registering an extension
and the [Default Admin Extension](admin.terms#default-extension) will be used.
The [Default Admin Extension](admin.terms#default-extension) may be used
directly, passing the [ORM Model](admin.terms#orm-model) class name and,
optionally, the [Model Mapper](admin.terms#model-mapper) class name to
the constructor.

    // Create a default extension for the user model
    $ext = new Default_Admin_Extension('user');

    // Create a default extension for the Sprig article model
    $ext = new Default_Admin_Extension('article', 'Admin_Model_Mapper_Sprig');

If no [Model Mapper](admin.terms#model-mapper) is defined, the [CRUD Admin
Extension](admin.terms#crud-extension) will attempt to determine the ORM
type from the list of supported ORM libraries.  If a custom
[Model Mapper](admin.terms#model-mapper) is being used, it must be defined
by extending the [CRUD Admin Extension](admin.terms#crud-extension).

## Authorization {#authorization}

User access for the CRUD extension's actions is determined by the A2
authorization module. Configuration of users and authorization rules
is done in the `a2.php` configuration file.

The resource used to check user access is determined through the
following approach:

1. If a custom resource is defined for the extension via the
    `acl_resource` property, use the given resource for ACL checks
1. Else if the associated model implements the `A2_Resource_Interface`,
    use the resource id of the model
1. Else use the standard default resource ("resource")

The privileges used for ACL checks are the same as the action names

    Admin::PRIVILEGE_LIST    // "list"
    Admin::PRIVILEGE_CREATE  // "create"
    Admin::PRIVILEGE_UPDATE  // "update"
    Admin::PRIVILEGE_DELETE  // "delete"
    Admin::PRIVILEGE_HISTORY // "history"

## The List Action {#list-action}

The default page for the CRUD extension is the list action.  The list
action displays a table of database records, with user controls for
creating and modifying records, filtering the displayed records,
sorting the displayed records, and searching for records.

The list action is accessed by the public method, `action_list()`, or
through the following route.

    Route::get('admin/extension')->uri(array(
        'extension' => 'user',
        'action'    => 'list', // optional, list is default
    ));

### Specifying the Displayed Columns

By default, the list will display the string result of the `__toString()`
method for each object. Alternatively, the object fields (properties or
methods) to display, and their order, can be defined by setting the
`list_fields` property as an array of property or method names. The
order in which the fields are defined is the same order in which the
resulting columns will be displayed.

    public $list_fields = array('field1', 'field2', 'method3');

By default, the column titles will be determined using the `label($field)`
method of the associated [Model Mapper](admin.terms#model-mapper).
Alternatively, column titles can be defined manually by setting the
`list_titles` property as an array of key and value pairs, where the
key is the field and the value is the title.

    public $list_titles = array('field1' => 'Column Title');

By default, the first column will contain a link to the update action
for each record. Alternatively, any number of fields can contain the
update link by defining the `list_links` property as an array of
model fields. To specify a link to an action of the current extension
other than the update action, specify an item of the `list_links`
array as a key and value pair where the key is the field and the
value is the action. Several special fields are recognized by the
CRUD extension and result in an icon to be displayed, rather than
the record fields.

    // the 1st and 3rd columns will contain update links
    public $list_links = array('field1', 'method3');

    // the 2nd column will contain a link to the delete action
    public $list_links += array('field2' => 'delete');

    // the last column will contain a delete link with an icon
    public $list_links += array('admin_delete_icon' => 'delete');

The following special icon fields are recognized by the CRUD extension.

 - `admin_view_icon` : displays a magnifying glass graphic
 - `admin_delete_icon` : displays an "X" graphic
 - `admin_update_icon` : displays a paper graphic
 - `admin_history_icon` : displays a clock graphic

### Pagination

The CRUD extension will automatically paginate the results of the
list to span multiple pages, if necessary. By default, the number
of results displayed per page is the global value defined in the
`admin.php` config file.  Alternatively, the number of results
can be defined by setting the `list_per_page` property.

    public $list_per_page = 25;

### Sorting

The CRUD extension can display records sorted according to a
particular field. By default, the records are displayed sorted
by the record ID, descending. Alternatively, the field used
for default sorting can be defined by setting the key and value
pair, where the key is the field and the value is the sorting
direction.

    public $list_sorting = array('date' => 'ASC');

Additional options for sorting the results can be provided by
specifying which fields to use for sorting. The fields specified
must represent actual database fields, since sorting takes place
when querying the database.

    public $sort_fields = array('username', 'registered_date');

### Filters

The CRUD extension can filter the records that are displayed
according to a particular field. Controls for filtering the results
can be enabled by defining the `filter_fields` property as an array
of fields that are filterable. The fields specified must
represent actual database fields, since filtering takes place
when querying the database.

    public $filter_fields = array('role');

[!!] May provide date/alphabetical filtering in the future

### Search

A search box can be added to the record list by specifying which
fields to use for creating the search query.

    public $search_fields = array('title', 'excerpt');

[!!] Will offer different search options (match exactly, match beginning, etc.)

### Multiple-Item Actions

[!!] This feature will come at a later date. It is not known right now how it
will be implemented or configured.

### Customizing the View

By default, the list view is created using the `View_Admin_List` view
class and the `admin/list.mustache` template file. Both the template
and the view class can be overridden to customize the list view.

    // Replace the view class, uses template associated with the class
    public $list_view_class = 'View_Admin_MyList';

    // OR

    // Use a custom template with the default view class
    public $list_view_template = 'admin/mylist.mustache';

## The Create Action {#create-action}

The create action is provided by the CRUD extension to facilitate the
creation of new model records. The create action displays a blank
form and handles the submission and processing of the form.

The create action is accessed by the public method, `action_create()`, or
through the following route.

    Route::get('admin/extension')->uri(array(
        'extension' => 'user',
        'action'    => 'create',
    ));

### Specifying the Form Fields

By default, the form fields are generated through the `inputs()` method
of the associated [Model Mapper](amdin.terms#model-mapper).  Alternatively,
the fields to be displayed on the create form can be defined in the
`create_fields` property as an array of fields. These fields are used to
input fields through the `input()` method of the
[Model Mapper](admin.terms#model-mapper).

    public $create_fields = array('username', 'birthdate');

Field labels are created automatically created through the `label()`
method of the [Model Mapper](admin.terms#model-mapper). Alternatively,
labels can be defined for any individual field by setting the `create_labels`
property as an array of key and value pairs, where the key is the field
and the value is the label.

    public $create_labels = array('username' => 'Your name:');

### Customizing the View

By default, the create view is created using the `View_Admin_Form` view
class and the `admin/form.mustache` template file. Both the template
and the view class can be overridden to customize the create view.

    // Replace the view class, uses template associated with the class
    public $create_view_class = 'View_Admin_MyForm';

    // OR

    // Use a custom template with the default view class
    public $create_view_template = 'admin/myform.mustache';

## The Update Action {#update-action}

The update action is provided by the CRUD extension to facilitate the
modification of model records. The update action displays a modification
form and handles the submission and processing of the form.

The update action is accessed by the public method, `action_update()`, or
through the following route.

    Route::get('admin/extension')->uri(array(
        'extension' => 'user',
        'action'    => 'update',
        'id'        => 1,
    ));

### Specifying the Form Fields

By default, the form fields are generated through the `inputs()` method
of the associated [Model Mapper](amdin.terms#model-mapper).  Alternatively,
the fields to be displayed on the update form can be defined in the
`update_fields` property as an array of fields. These fields are used to
input fields through the `input()` method of the
[Model Mapper](admin.terms#model-mapper).

    public $update_fields = array('username', 'birthdate');

Field labels are created automatically created through the `label()`
method of the [Model Mapper](admin.terms#model-mapper). Alternatively,
labels can be defined for any individual field by setting the `update_labels`
property as an array of key and value pairs, where the key is the field
and the value is the label.

    public $update_labels = array('username' => 'New name:');

### Customizing the View

By default, the update view is created using the `View_Admin_Form` view
class and the `admin/form.mustache` template file. Both the template
and the view class can be overridden to customize the update view.

    // Replace the view class, uses template associated with the class
    public $update_view_class = 'View_Admin_MyForm';

    // OR

    // Use a custom template with the default view class
    public $update_view_template = 'admin/myform.mustache';

## The Delete Confirmation Action {#delete-action}

The delete action is provided by the CRUD extension to facilitate the
deletion of model records. The delete action displays a confirmation
page prompting the user to confirm the deletion of the specified
record. If the deletion is confirmed, the record is deleted.

The delete action is accessed by the public method, `action_delete()`, or
through the following route.

    Route::get('admin/extension')->uri(array(
        'extension' => 'user',
        'action'    => 'delete',
        'id'        => 1,
    ));

### Customizing the View

By default, the confirmation view is created using the
`View_Admin_Confirmation` view class and the `admin/form.mustache`
template file. Both the template and the view class can be overridden
to customize the deletion confirmation view.

    // Replace the view class, uses template associated with the class
    public $confirmation_view_class = 'View_Admin_MyConfirm';

    // OR

    // Use a custom template with the default view class
    public $confirmation_view_template = 'admin/myconfirm.mustache';

## The History Action {#history-action}

The CRUD extension provides the capability to record modifications
made to [ORM Models](admin.terms#orm-model). By default, change
history is disabled. To enable history, the `history_enabled`
property must be set.

    public $history_enabled = TRUE;

[!!] The history table must be created in the database with
the schema provided in `queries/schemas/history.sql`.

If history is enabled, the CRUD extension will store the time
of the modification, the user ID of the user who performed
the modification, and a brief comment listing the fields that
were modified. The list of modified fields is determined by
the `changed` method of the [Model Mapper](admin.terms#model-mapper).

The history action is accessed by the public method, `action_history()`, or
through the following route.

    Route::get('admin/extension')->uri(array(
        'extension' => 'user',
        'action'    => 'history',
        'id'        => 1,
    ));

### Limiting Change History

If change history is desired only for a subset of fields of the
[ORM Model](admin.terms#orm-model) (ie, only record changes to a
user's email), the `history_fields` property can be defined as an
array of fields. By default, all field modifications will be
recorded.

    public $history_fields = array('email');

### Customizing the View

By default, the history view is created using the `View_Admin_History` view
class and the `admin/history.mustache` template file. Both the template
and the view class can be overridden to customize the history view.

    // Replace the view class, uses template associated with the class
    public $history_view_class = 'View_Admin_MyHistory';

    // OR

    // Use a custom template with the default view class
    public $history_view_template = 'admin/myhistory.mustache';

