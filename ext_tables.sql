#
# Table structure for table 'tx_arcglossary_glossary_entries'
#
CREATE TABLE tx_arcglossary_glossary_entries
(
    term        tinytext              NOT NULL,
    title       tinytext              NOT NULL,
    description text                  NOT NULL,

    KEY parent (pid),
    KEY language (l18n_parent, sys_language_uid)
);

#
# Table structure for table 'pages'
#
CREATE TABLE pages
(
    tx_arcglossary_donotparse tinyint(3) DEFAULT '0' NOT NULL
);
