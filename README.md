# LongRunning

Tools for long-running commands.

## Long running Symfony applications/console commands

In a Symfony application enable the bundle `LongRunning\Bundle\LongRunningBundle\LongRunningBundle`. Then use the
`long_running.delegating_cleaner` service to:

- Clear all Doctrine ORM entity managers (to prevent outdated entities from being updated)
- Reset all closed Doctrine ORM entity managers (after a failed transaction)
- Close all database connections (to prevent database timeout errors)
- Clear all Monolog "fingers crossed" handlers (clears messages and resets the handler when there was no failure during the execution of a task)
- Close all Monolog buffer handlers (clears log messages that were buffered during the execution of a task)
- Flush all Swift Mailer "in memory" spools (i.e. send spooled e-mails)
- Flush all unsent Sentry errors (in case they are handled async)

If you also use the SimpleBusRabbitMQBundleBridgeBundle, these clean-up actions will be performed automatically after each
message that was consumed, whether or not consuming it was successful.
