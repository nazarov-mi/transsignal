
<template>
	<section class="section section_strip">
		<loading-overlay ref="loading">
			<div class="center">
				<div class="products">
					<div class="products__list">
						<div class="products__item" v-for="p in filteredList">
							<div class="product-item">
								<div class="product-item__img-wrapper">
									<img width="100%" v-if="p.pictures.length" :src="p.pictures[0].preview" :title="p.name" :alt="p.name" />
								</div>
								<div class="product-item__content">
									<h4 class="product-item__title">{{ p.name }}</h4>
									<p class="product-item__desc">{{ p.short_desc }}</p>
									<h3 class="product-item__price">
										<span>{{ p.price }}</span>
										руб
									</h3>
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
		name: 'Products',

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
				list: [],
				categoryId: this.filterData.categoryId
			}
		},

		watch: {
			filterData: {
				handler(val) {
					let newId = val.categoryId
					  , oldId = this.categoryId;

					if (newId !== oldId) {
						this.categoryId = newId;
						this.load();
					}
				},
				deep: true
			}
		},

		mounted() {
			this.load();
		},

		computed: {
			filteredList() {
				let {
						filterData,
						list
					} = this
				  , {
				  		filterKey,
				  		sortKey,
				  		sortDir
					} = filterData;

				if (filterKey) {
					list = list.filter(function (row) {
						return String(row['name']).toLowerCase().indexOf(filterKey) > -1;
					});
				}

				if (sortKey) {
					sortDir = sortDir || 1;

					list = list.sort(function (a, b) {
						a = a[sortKey];
						b = b[sortKey];

						return (a !== b ? (a > b ? 1 : -1) : 0) * sortDir;
					});
				}

				return list;
			}
		},

		methods: {
			load() {
				let categoryId = this.categoryId
				  , uri = (categoryId ? '/api/category/' + categoryId + '/products' : '/api/product')
				  , loading = this.$refs.loading;

				loading.show();
				this.$http.get(uri).then(response => {
					this.list = response.data;
					loading.hide();
				}, () => {
					loading.hide();
				});
			},
			change(product) {
				this.$router.push('/products');
				
			}
		}
	}
</script>