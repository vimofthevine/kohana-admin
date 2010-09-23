# Admin Module :: Creating a Sub-Menu

If an extension requires its own navigation menu, a sub-menu can
be created that will be displayed as a side-bar menu in the
[Admin App](admin.terms#admin-app). The sub-menu must be defined
as a KOstache view class and should use the `admin/menu` mustache
template.  The Kohana request object will be passed
to the view through the set() method to the `_request` variable.
