#
# Table structure for table 'tx_arcglossary_glossary_entries'
#
CREATE TABLE tx_arcglossary_glossary_entries
(
    term        tinytext              NOT NULL,
    title       tinytext              NOT NULL,
    language    char(2)    DEFAULT '' NOT NULL,
    type        varchar(7) DEFAULT '' NOT NULL,
    description text                  NOT NULL,
    alias       text                  NOT NULL,
    link        tinytext              NOT NULL,

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
