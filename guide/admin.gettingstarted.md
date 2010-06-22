# Getting Started

1. Fetch the admin template submodule

        cd MODPATH/kohana-admin
        git submodule update

1. Enable the admin module in bootstrap.php
1. Copy MODPATH/kohana-admin/config/admin.php to the application folder
1. Modify APPPATH/config/admin.php to create the desired navigation menu
1. Copy MODPATH/A2/config/a2.php to APPPATH/config/auth.php and modify accordingly
1. Set up the desired authentication library and user model
1. Go to myhost/admin in a web browser and log in

