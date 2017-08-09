<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('products')->delete();

		\DB::table('products')->insert([
			[
				'name' => 'Дорожный сигнальный столбик',
				'short_desc' => 'Длина - 1100-1300мм; Сечение - дуга шириной 120мм;',
				'desc' => 'Длина - 1100-1300мм; Сечение - дуга шириной 120мм;',
				'price' => '1200',
			],
			[
				'name' => 'Светофор транспортный светодиодный трехсекционный',
				'short_desc' => 'Габаритные размеры: Секции Диаметр 300мм',
				'desc' => 'Габаритные размеры: Секции Диаметр 300мм',
				'price' => '2700',
			],
			[
				'name' => 'Сигнальный фонарь ФС-41',
				'short_desc' => 'Мощность - около 1Вт Питание - 2 батарейки АА',
				'desc' => 'Мощность - около 1Вт Питание - 2 батарейки АА',
				'price' => '800',
			],
		]);
	}
}
