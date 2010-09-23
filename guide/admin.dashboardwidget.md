# Admin Module :: Creating a Dashboard Widget 

[Admin Extensions](admin.terms#admin-extension) may provide widgets that can be
displayed on the dashboard of the [Admin App's](admin.terms#admin-app) main page.
Widgets are executed in the same way as normal extension actions, however the
action method provided by the extension class must be prepended with "widget_" rather
than "action_".  These widget actions are not callable by external requests.  If the
result status of a widget action is 301, 403, or 404, the widget will not be displayed.
If the result status of a widget is 200 or 400 and a user message is set, the widget
will display the user message, rather than the HTML response.  If neither
of the previous cases apply, then the HTML response is displayed.  Redirect URLs are
ignored in all cases.

If a widget was desired that performed the same action as a regular action, the widget
method could simply invoke the original action with or without run-time configuration
modifications.

    public function widget_list()
    {
        // Could change some configuration settings here

        $this->action_list();
    }

