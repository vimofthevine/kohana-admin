# Admin Overview

The admin module is meant to facilitate the rapid development of an end-user
administration site.  There are essentially two aspects provided by the module,
the template and the framework.

** The admin template submodule has to be initialized/downloaded **

## Features
 - Template integration, courtesy of Colonel-Rosa
 - Resource handling
 - Access control
 - Automated differentiation between external requests (full layout)
		and internal requests (partial layout)

### The Admin Framework

The admin module also provides a framework for creating administration pages.
The abstract base admin controller, `Controller_Admin`, may be extended to make
use of the template and other common functions.  The common functions provided
by the base admin controller are ACL checking (using Wouter's A2 library) and
request handling (differentiating between main/external requests and ajax/internal
requests).

