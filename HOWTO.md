# HOW TO

## Naming

Replace:

- 'A small utility to install/uninstall/enable/disable xdebug.' => A description for the library
- xdebug-manager => The library name
- XdebugManager => The library namespace

Go through the README.md and adapt to your situation

## Folder structure

- **bin** (_application binaries, the dependencies binaries should go in vendor/bin_)
- **docs** (_application documentation_)
- **lib**
    - **abstract-core**  (_interfaces that plugins must implement in order to integrate with our application_)
    - **generic-subdomain** (_libraries that **do not connect** to outside the application_)
    - **ports-adapters** (_interfaces, and their adapters, that **connect** to outside the application_)
    - **shared-kernel** (_application and domain code shared among all bounded contexts_)
- **src**
    - **Core** (_the main application code_)
    - **Presentation** (_the user facing applications, controllers, views and related code units_)
- **storage** (_artifacts needed for running the application in production_)
- **tests** (_unit, integration, functional, acceptance tests_)
    - **storage** (_artifacts needed for running the application tests, like a test DB template_)
- **var** (_volatile artifacts like logs, cache, temporary test databases, generated code, uploaded code like user plugins, ..._)
- **vendor** (_distributable libraries_)
