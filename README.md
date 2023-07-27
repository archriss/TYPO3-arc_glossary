# Archriss Glossary Parser #

## This extension parse the content to add glossary term to the output ##

Wrap content with `<!--ARCGLOSSARY_begin-->|<!--ARCGLOSSARY_end-->` and watch the result.

Abbr tag can be changed in extension configuration.

For more information on handling watch Parser.php

## How it work

- Enable parsing in extension settings
- Wrap code with `<!--ARCGLOSSARY_begin-->|<!--ARCGLOSSARY_end-->`
- Abbr tag can be overrided in extension configuration
- Typolink configuration can be altered in typoscript
- Add extension typoscript configuration
- Specify glossary storage uids in site configuration : settings: glossaryStorageUidList
- OR
- Specify glossary storage uids in constants using (override site configuration) : glossaryStorageUidList
- Clear cache
