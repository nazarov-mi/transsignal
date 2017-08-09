
import Admin from '@/components/admin/Admin'
import Categories from '@/components/admin/Categories'
import Category from '@/components/admin/Category'
import Products from '@/components/admin/Products'
import Product from '@/components/admin/Product'
import Users from '@/components/admin/Users'
import User from '@/components/admin/User'

export default {
	path: '/admin',
	component: Admin,
	meta: {
		requiresAuth: true,
	},
	children: [
		{
			path: '',
			name: 'admin.home',
			redirect: {
				name: 'admin.categories.all'
			}
		},

		{
			path: 'categories/:id(\\d+)/edit',
			name: 'admin.categories.edit',
			component: Category
		},
		{
			path: 'categories/create',
			name: 'admin.categories.create',
			component: Category
		},
		{
			path: 'categories',
			name: 'admin.categories.all',
			component: Categories
		},

		{
			path: 'products/:id(\\d+)/edit',
			name: 'admin.products.edit',
			component: Product
		},
		{
			path: 'products/create',
			name: 'admin.products.create',
			component: Product
		},
		{
			path: 'products',
			name: 'admin.products.all',
			component: Products
		},

		{
			path: 'users/:id(\\d+)/edit',
			name: 'admin.users.edit',
			component: User
		},
		{
			path: 'users/create',
			name: 'admin.users.create',
			component: User
		},
		{
			path: 'users',
			name: 'admin.users.all',
			component: Users
		}
	]
}
