<?php

namespace MarketingRelevance\ScoutShopaholic\Classes\Helper;

use DB;
use Schema;

class MigrationHelper
{
    public static function addIndexes(string $tableName)
    {
        $index = str_replace('lovata_', 'fulltext_', $tableName.'_index');
        $columns = [
            'name',
            'preview_text',
            'description',
            'search_synonym',
            'search_content',
        ];

        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $doctrineTable = $sm->listTableDetails($tableName);

        if (!$doctrineTable->hasIndex($index)) {
            DB::statement(sprintf("ALTER TABLE %s ADD FULLTEXT %s (%s)", $tableName, $index, join(',', $columns)));
        }
    }

    public static function dropIndexes(string $tableName)
    {
        $index = str_replace('lovata_', 'fulltext_', $tableName.'_index');

        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $doctrineTable = $sm->listTableDetails($tableName);

        if ($doctrineTable->hasIndex($index)) {
            DB::statement(sprintf('DROP INDEX %s ON %s', $index, $tableName));
        }
    }
}
