
import Imageable from '@/http/Imageable'

export default class Category extends Imageable
{
	constructor()
	{
		super(
			'category',
			{
				id: undefined,
				name: '',
				active: true,
				sort: '100'
			}
		);
	}

	getProducts(id)
	{
		return this.submit('get', id + '/products');
	}
}