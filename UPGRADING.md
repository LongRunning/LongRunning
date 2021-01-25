# Upgrading

From 0.5.0 to 1.0

* Repository `long-running/long-running` has now been split into separate packages.

* The following plugins are not ported because I don't personally use them:
  - DoctrineDBALPlugin
  - MonologPlugin
  - SwiftMailerPlugin
  If you need support for them, feel free to provide a PR.

* The following plugins are removed because the projects are dead:
  - BernardPlugin
  - SimpleBusRabbitMQPlugin

* All packages now depend on `long-running/core`.



