<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('rinvex.subscriptions.tables.plan_features'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->string('slug');
            $table->{$this->jsonable()}('name');
            $table->{$this->jsonable()}('description')->nullable();
            $table->string('value');
            $table->smallInteger('resettable_period')->unsigned()->default(0);
            $table->char('resettable_interval', 1)->default('m');
            $table->mediumInteger('sort_order')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->unique(['plan_id', 'slug']);
            $table->foreign('plan_id')->references('id')->on(config('rinvex.subscriptions.tables.plans'))
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('rinvex.subscriptions.tables.plan_features'));
    }

    /**
     * Get jsonable column data type.
     *
     * @return string
     */
    protected function jsonable()
    {
        return DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql'
               && version_compare(DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION), '5.7.8', 'ge')
            ? 'json' : 'text';
    }
}
