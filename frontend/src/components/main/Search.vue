
<template>
	<div>
		<div class="search__archor"></div>
		<div class="search" :class="{ 'search_full': showFull }">
			<input type="text" placeholder="Начните печатать наименование товара" class="search__input" v-model.trim="filterKey" @input="onInputFilterKey" />
			<div class="search__options">
				<dropdown-list :options="category.all.bind(category)" placeholder="Найти по категории" v-model="categoryId" @select="updateSearchData"></dropdown-list>
				<dropdown-list :options="sortByOptions" placeholder="Сортировать по.." prefix="По" v-model="sortBy" @select="updateSearchData"></dropdown-list>
			</div>
			<div class="search__more">
				<router-link to="products">или откройте список всех товаров</router-link>
			</div>
		</div>
	</div>
</template>

<script>
	import DropdownList from '@/components/ui/DropdownList';
	import Category from '@/http/models/Category';
	
	export default {
		name: 'Search',

		components: {
			DropdownList
		},

		props: {
			filterData: {
				type: Object,
				default() {
					return {};
				}
			},
			showFull: {
				type: Boolean,
				default: false
			}
		},

		data() {
			return {
				category: new Category(),
				categoryId: null,
				sortByOptions: {
					price_1: 'Цене (по возростанию)',
					price_0: 'Цене (по убыванию)',
					name_1: 'Алфавиту (от А до Я)',
					name_0: 'Алфавиту (от Я до А)'
				},
				filterKey: '',
				sortBy: 'price_1'
			}
		},

		watch: {
			filterData: {
				handler(val) {
					this.filterKey = val.filterKey;
					this.categoryId = val.categoryId;
					this.sortBy = val.sortKey + '_' + (val.sortDir * .5 + .5);
				},
				deep: true
			}
		},
		/*
		mounted() {
			let scrollHandler = ((e) => {
				let archor = this.$refs.archor
				  , search = this.$refs.search
				  , y = archor.getBoundingClientRect().top
				  , h = y <= 0 ? search.getBoundingClientRect().height : 0;

				search.classList.toggle('search_sticky', y <= 0);
				archor.style.height = h + 'px';
			}).bind(this);
			window.addEventListener('scroll', scrollHandler);
		},
		*/
		methods: {
			onInputFilterKey() {
				let filterKey = this.filterKey;
				setTimeout(() => {
					if (filterKey === this.filterKey) {
						this.updateSearchData();
					}
				}, 300);
			},
			updateSearchData() {
				let {
						filterData,
						filterKey,
						sortBy,
						categoryId
					} = this
				  , sortKey
				  , sortDir;

				if (sortBy) {
					let m = sortBy.split('_');

					sortKey = m[0];
					sortDir = (m[1] - .5) * 2;
				}

				Object.assign(filterData, {
					filterKey: filterKey,
					sortKey: sortKey,
					sortDir: sortDir,
					categoryId: categoryId
				});
			}
		}
	}
</script>