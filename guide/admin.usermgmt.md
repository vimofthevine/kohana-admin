# Admin Module :: Creating the First User 

In order to access the [Admin App](admin.terms#admin-app), you
must be able to log in as a user.
The Admin Module comes with a command-line interface for managing a
single admin user.  If multiple users are desired, the
User Admin Extension must be installed to add multiple users.

1. Copy the `MODPATH/admin/config/admincli.php` config file to
    `APPPATH/config` and modify as needed
1. To create the admin user, run from DOCROOT `php admin.php
    createadmin`
    1. Enter password when prompted 
1. To change the password, run `php admin.php changepassword` 
    1. Enter the old password and new password when prompted 
