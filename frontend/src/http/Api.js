
import Category from '@/http/models/Category'
import Product from '@/http/models/Product'
import User from '@/http/models/User'

export default class Api
{
	constructor()
	{
		this.category = new Category();
		this.product = new Product();
		this.user = new User();
	}
}