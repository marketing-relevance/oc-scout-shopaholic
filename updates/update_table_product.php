<?php

namespace MarketingRelevance\ScoutShopaholic\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class UpdateTableProduct extends Migration
{
    const TABLE = 'lovata_shopaholic_products';

    public function up()
    {
        if (! Schema::hasTable(self::TABLE) || Schema::hasColumn(self::TABLE, 'search_synonym')) {
            return;
        }

        Schema::table(self::TABLE, function (Blueprint $table) {
            $table->text('search_synonym')->nullable();
            $table->text('search_content')->nullable();
        });
    }

    public function down()
    {
        if (!Schema::hasTable(self::TABLE) || !Schema::hasColumn(self::TABLE, 'search_synonym')) {
            return;
        }

        Schema::table(self::TABLE, function (Blueprint $obTable) {
            $obTable->dropColumn(['search_synonym', 'search_content']);
        });
    }

    protected function addIndexes(string $tableName)
    {
        $index_name = str_replace('lovata_', 'fulltext_', $tableName.'_index');
        $columns = [

        ];
    }
}
