<?php

namespace MarketingRelevance\ScoutShopaholic\Updates;

use MarketingRelevance\ScoutShopaholic\Classes\Helper\MigrationHelper;
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

        MigrationHelper::addIndexes(self::TABLE);
    }

    public function down()
    {
        if (!Schema::hasTable(self::TABLE) || !Schema::hasColumn(self::TABLE, 'search_synonym')) {
            return;
        }

        Schema::table(self::TABLE, function (Blueprint $obTable) {
            $obTable->dropColumn(['search_synonym', 'search_content']);
        });

        MigrationHelper::dropIndexes(self::TABLE);
    }
}
