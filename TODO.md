TODO
====

* Allow specifying columns for toArray
    * Example: $doc->toArray(['name', 'title', 'workHistory' => ['startedAt']]);
* Tests for {pre|post}{insert|update|delete} events with embeddeds too
* Make them work recursively (for embeddeds of embeddeds)
* Use new pecl mongodb driver (works for php >= 5.4 & HHVM 3.9)
* Column hint for queries to/and/or bypass column cache
* Integrate behaviors
* Refactor / rewrite column caching to better handle embeddeds

