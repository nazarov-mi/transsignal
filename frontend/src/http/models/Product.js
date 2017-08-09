
import Imageable from '@/http/Imageable'

export default class Product extends Imageable
{
	constructor()
	{
		super(
			'product',
			{
				id: undefined,
				name: '',
				short_desc: '',
				desc: '',
				price: '',
				categories: null,
				pictures: null,
				active: true,
				sort: '100'
			}
		);
	}

	getCategories(id)
	{
		return this.submit('get', id + '/categories');
	}
}