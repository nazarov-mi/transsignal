
import Main from '@/components/main/Main'
import Products from '@/components/main/Products'
import Categories from '@/components/main/Categories'

export default {
	path: '/',
	component: Main,
	children: [
		{
			path: '',
			name: 'home',
			redirect: {
				name: 'categories'
			}
		},

		{
			path: 'categories',
			name: 'categories',
			component: Categories
		},

		{
			path: 'products',
			name: 'products',
			component: Products
		},

		/*{
			path: 'login',
			name: 'login',
			component: Login,
			meta: {
				requiresAuth: false
			}
		}*/
	]
}
