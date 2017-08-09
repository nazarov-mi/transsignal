<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('categories')->delete();

		\DB::table('categories')->insert([
			['name' => 'Светофоры'],
			['name' => 'Сигнальные столбики'],
			['name' => 'Дорожные ограждения'],
			['name' => 'Искусственные дорожные неровности'],
			['name' => 'Дорожные знаки'],
			['name' => 'Сигнальные фонари'],
			['name' => 'Пластиковые ёмкости'],
			['name' => 'Дорожная геосетка'],
		]);
	}
}
