
<style lang="scss" scoped>
	.admin {

		&__view {
			transition: all 500ms cubic-bezier(.55, 0, .1, 1);
		}

		&__slide {
			&_left-enter,
			&_right-leave-active {
				opacity: 0;
				-webkit-transform: translateX(100px);
						  transform: translateX(100px);
			}

			&_right-enter,
			&_left-leave-active {
				opacity: 0;
				-webkit-transform: translateX(-100px);
						  transform: translateX(-100px);
			}
		}
	}
</style>

<template>
	<div class="admin">
		<nav class="navigation">
			<router-link :to="{ name: 'admin.categories.all' }">Категории</router-link>
			<router-link :to="{ name: 'admin.products.all' }">Продукты</router-link>
			<router-link :to="{ name: 'admin.users.all' }">Пользователи</router-link>
			<router-link to="/admin/settings">Настройки</router-link>
			<hr/>
			<a>Выход</a>
		</nav>
		<div class="container">
			<div class="content">
				<transition mode="out-in" :name="transitionName">
					<router-view class="admin__view"></router-view>
				</transition>
			</div>
		</div>
	</div>
</template>

<script>
	require('froala-editor/js/froala_editor.pkgd.min');

	require("froala-editor/css/froala_editor.pkgd.min.css");
	require('font-awesome/css/font-awesome.css');
	require('froala-editor/css/froala_style.min.css');

	require('normalize-css');

	require('../../../static/css/admin.scss');

	import Vue from 'vue';
	import VueFroala from 'vue-froala-wysiwyg';
	Vue.use(VueFroala);

	export default {
		name: 'Admin',

		data() {
			return {
				transitionName: 'admin__slide_left'
			}
		},

		beforeRouteUpdate (to, from, next) {
			let toDepth = to.path.split('/').length
			  , fromDepth = from.path.split('/').length;

			this.transitionName = toDepth < fromDepth ? 'admin__slide_right' : 'admin__slide_left';
			next();
		}
	}
</script>