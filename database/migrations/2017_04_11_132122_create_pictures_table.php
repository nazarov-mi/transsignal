<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicturesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pictures', function (Blueprint $table)
		{
			$table->increments('id');
			$table->integer('imageable_id');
			$table->string('imageable_type');
			$table->string('extension');
			$table->string('path');
			$table->string('full');
			$table->string('preview');
			$table->string('small');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pictures');
	}
}
