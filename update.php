<?php
rex_sql_table::get('mf_googleplaces_reviews')
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('author_name', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('author_url', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('language', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('profile_photo_url', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('rating', 'int(11)', true))
    ->ensureColumn(new rex_sql_column('text', 'text'))
    ->ensureColumn(new rex_sql_column('time', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('createdate_addon', 'datetime'))
    ->ensureColumn(new rex_sql_column('google_place_id', 'varchar(191)'))
    ->ensure();
?>