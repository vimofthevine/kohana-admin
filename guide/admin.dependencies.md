# Dependencies

The admin module depends on the following modules

 - The official [Database module](http://github.com/kohana/database)
 - Wouter's [A2](http://github.com/Wouterrr/A2) and
	[ACL](http://github.com/Wouterrr/ACL) modules (for authorization)

Out of the box, the admin module depends on the following modules, but for
features that are "customizable"

 - Wouter's [A1 module](http://github.com/Wouterrr/A1) (for authentication)
 - My [Sentry module](http://github.com/vimofthevine/sentry) (modifies A1 to be Sprig-compatible)
 - Shadowhand's [Sprig module](http://github.com/shadowhand/sprig) (for user management)
 - My [Grid module](http://github.com/vimofthevine/grid) (for user management)

**Note:** Future versions will have support for ORM, Jelly, and Sprig.  This will
eliminate the dependencies on A1/Sentry and Sprig.  At that point, any authentication
library compatible with A2 will suffice.
