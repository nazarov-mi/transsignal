
<template>
	<section class="section section_strip">
		<loading-overlay ref="loading">
			<div class="center">
				<div class="categories">
					<h4 class="categories__title">Категории товаров</h4>
					<p class="categories__subtitle">Выберите интересующую Вас категорию товаров</p>
					<div class="categories__list">
						<div class="categories__item" v-for="c in categories">
							<div class="category-item" @click="change(c)">
								<h6 class="category-item__title">{{ c.name }}</h6>
								<div class="category-item__img-wrapper">
									<img width="100%" v-if="c.pictures.length" :src="c.pictures[0].preview" :title="c.name" :alt="c.name" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</loading-overlay>
	</section>
</template>

<script>
	import LoadingOverlay from '@/components/ui/LoadingOverlay';

	export default {
		name: 'Categories',

		components: {
			LoadingOverlay
		},

		props: {
			filterData: {
				type: Object,
				default() {
					return {};
				}
			}
		},

		data() {
			return {
				categories: []
			}
		},

		mounted() {
			this.load();
		},

		methods: {
			load() {
				let loading = this.$refs.loading;

				loading.show();
				this.$http.get('/api/category').then(response => {
					this.categories = response.data;
					loading.hide();
				}, () => {
					loading.hide();
				});
			},
			change(category) {
				this.$router.push('/products');
				this.filterData.categoryId = category.id;
			}
		}
	}
</script>