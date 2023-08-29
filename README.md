# Archriss Glossary Parser #

## This extension parse the content to add glossary term to the output ##

Wrap content with provided viewhelper

Or

Wrap content with `<!--ARCGLOSSARY_begin-->|<!--ARCGLOSSARY_end-->` and watch the result.

Abbr tag can be changed in viewhelper configuration (new version) or extension configuration (old version).

For more information on handling watch ParseContentViewHelper.php or Parser.php

## How it work

new improved and cachable version :
wrap content with the provided viewHelper, example below :
```
        <!-- abbrTag is the tag definition, default to : <abbr title="%s">%s</abbr> -->
        <!-- typolinkConfiguration is the link surrounding the tag, default to none -->
        <!-- wordsPid is the storage pid of words definition, default to none -->
        <arcGlossary:parseContent
                abbrTag="<abbr class=\"s-abbreviation__abbr\" title=\"%s\">%s</abbr>"
                typolinkConfiguration="{parameter: settings.pid.glossary, 'section.': {field: 'uid', wrap: 'c'}, ATagParams: 'class=\"s-abbreviation\"'}"
                wordsPid="27"
        >
            {bodytext}
        </arcGlossary:parseContent>
```

old version :
- Enable parsing in extension settings
- Wrap code with `<!--ARCGLOSSARY_begin-->|<!--ARCGLOSSARY_end-->`
- Abbr tag can be overrided in extension configuration
- Typolink configuration can be altered in typoscript
- Add extension typoscript configuration
- Specify glossary storage uids in site configuration : settings: glossaryStorageUidList
- OR
- Specify glossary storage uids in constants using (override site configuration) : glossaryStorageUidList
- Clear cache
