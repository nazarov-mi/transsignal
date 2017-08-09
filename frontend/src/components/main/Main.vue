
<template>
	<div class="main">
		<header class="header">
			<div class="center">
				<b class="header__phoneumber">
					<i class="fa fa-phone" aria-hidden="true"></i>
					+7 999 999-99-99
				</b>
				<nav class="header__menu">
					<router-link to="products">Товары</router-link>
					<router-link to="categories">Категории</router-link>
					<a @click="openModalAboutUs">О Нас</a>
					<a @click="openModalContacts">Контакты</a>
				</nav>
			</div>
		</header>
		<section class="section">
			<div class="center">
				<div class="cover">
					<h1 class="cover__title">ООО ПКФ “Транссигнал”</h1>
					<p class="cover__subtitle">99 лет успешной работы!</p>
					<search :filter-data="filterData" :show-full="fullSearch"></search>
				</div>
			</div>
		</section>
		<router-view :filter-data="filterData"></router-view>
		<footer class="footer">
			<nav class="footer__menu">
				<router-link to="products">Товары</router-link>
				<router-link to="categories">Категории</router-link>
				<a @click="openModalAboutUs">О Нас</a>
				<a @click="openModalContacts">Контакты</a>
			</nav>
			<a class="footer__author" href="//nazarov-mi.ru" target="_blank">© nazarov-mi.ru {{ (new Date()).getFullYear() }}</a>
		</footer>
		<modal-card ref="modalAboutUs">
			<div class="about-us">
				<img class="about-us__logo" src="static/assets/logo.jpg" />
				<div class="about-us__desc">
					<p><b>Lorem Ipsum</b> - это текст-"рыба", часто используемый в печати и вэб-дизайне. <b>Lorem Ipsum</b> является стандартной "рыбой" для текстов на латинице с начала XVI века. В то время некий <i>безымянный печатник создал большую коллекцию размеров и форм шрифтов</i>, используя <b>Lorem Ipsum</b> для распечатки образцов.</p>
					<p><b>Lorem Ipsum</b> не только успешно пережил без заметных изменений пять веков, но и перешагнул в электронный дизайн. Его популяризации в новое время послужили публикация листов Letraset с образцами <b>Lorem Ipsum</b> в 60-х годах и, в более недавнее время, программы электронной вёрстки типа <i>Aldus PageMaker</i>, в шаблонах которых используется <b>Lorem Ipsum</b>.</p>
				</div>
			</div>
			<hr/>
			<div class="level">
				<div class="level-item">
					<div class="counter">
						<div class="counter__title">11</div>
						<div class="counter__subtitle">лет на рынке</div>
					</div>
				</div>
				<div class="level-item">
					<div class="counter">
						<div class="counter__title">256</div>
						<div class="counter__subtitle">заказов выполнено</div>
					</div>
				</div>
				<div class="level-item">
					<div class="counter">
						<div class="counter__title">3</div>
						<div class="counter__subtitle">склада</div>
					</div>
				</div>
				<div class="level-item">
					<div class="counter">
						<div class="counter__title">12</div>
						<div class="counter__subtitle">поставщиков</div>
					</div>
				</div>
			</div>
		</modal-card>
		<modal-card ref="modalContacts">modalContacts</modal-card>
	</div>
</template>

<script>
	import ModalCard from '@/components/ui/ModalCard';
	import Search from '@/components/main/Search';

	require('../../../static/css/main.scss');

	export default {
		name: 'Main',

		components: {
			Search,
			ModalCard
		},

		data() {
			return {
				filterData: {
					filterKey: '',
					sortKey: 'name',
					sortDir: 1,
					categoryId: null
				},
				fullSearch: false
			}
		},

		watch: {
			'$route': {
				handler(val) {
					this.fullSearch = (val.path == '/products');
				},
				immediate: true
			}
		},

		methods: {
			openModalAboutUs() {
				this.$refs.modalAboutUs.open();
			},
			openModalContacts() {
				this.$refs.modalContacts.open();
			}
		}
	}
</script>