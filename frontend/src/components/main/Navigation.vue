
<template>
	<nav>
		<router-link :to="{ name: 'categories' }">Категории</router-link>
		<router-link :to="{ name: 'products' }">Товары</router-link>
		<a @click="showAboutUs">О Нас</a>
		<a @click="showContacts">Контакты</a>
	</nav>
</template>

<script>
	export default {
		name: 'Navigation',

		data() {
			return {}
		},

		methods: {
			showAboutUs() {

			},
			showContacts() {
				
			}
		}
	}
</script>