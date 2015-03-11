# LongRunning

Tools for long-running commands.

## Long running Symfony applications/console commands

In a Symfony application enable the bundle `LongRunning\Bundle\LongRunningBundle\LongRunningBundle`. Then use the
`long_running.delegating_cleaner` service to:

- Clear all Doctrine ORM entity managers (to prevent outdated entities from being updated)
- Reset all closed Doctrine ORM entity managers (after a failed transaction)
- Close all database connections (to prevent database timeout errors)

If you also use the SimpleBusRabbitMQBundle, these clean-up actions will be performed automatically after each
message that was consumed, whether or not consuming it was successful.
