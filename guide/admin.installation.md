# Admin Module :: Installation

After cloning the Admin Module into the modules folder, run
`git submodule update` in the admin module's folder to download
Colonel-Rosa's admin theme.

The Admin Module uses the A2 library for authorization. A2 uses
another library for authentication (A1 or the official auth module).
Install and configure a compatible authentication module.

If the modification history feature of the CRUD Admin Extension will
be used, the `admin_history` table must be created using the schema
in `MODPATH/admin/queries/schemas/admin_history.sql`.
