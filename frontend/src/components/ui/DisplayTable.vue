
<template>
	<loading-overlay ref="loading">
		<p>
			<dropdown-list :options="[5,10,20,50,100]" :can-clear="false" v-model="max"></dropdown-list>
		</p>

		<table>
			<thead>
				<tr>
					<th class="small-col"></th>
					<th v-for="(val, key) in head" @click="setSortKey(key)">
						{{ val }}
						<span class="sort" :class="sortKey == key ? (sortDir > 0 ? 'sort-asc' : 'sort-desc') : ''"></span>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="small-col">
						<i class="icon-search" aria-hidden="true"></i>
					</td>
					<td v-for="(val, key) in filterKeys">
						<input type="text" v-model="filterKeys[key]" />
					</td>
				</tr>
				<tr v-for="row in pageList" @click="changeRowHandler(row)">
					<td class="small-col tool-col">
						<i class="icon-cancel" v-if="canDelete" @click.stop="deleteRow(row)" aria-hidden="true"></i>
						<slot :row="row"></slot>
					</td>
					<td class="sub-text" v-for="(val, key) in head" :title="row[key]">
						{{ row[key] }}
					</td>
				</tr>
			</tbody>
		</table>

		<div v-if="numPages > 1">
			<ul class="pagination-list">
				<li :class="{'is-disabled': page == 1}" @click="prevPage">&larr; Предыдущая</li>
				<li v-for="id in numPages" :class="{'is-current': id == page}" @click="gotoPage(id)">
					{{ id }}
				</li>
				<li :class="{'is-disabled': page == numPages}" @click="nextPage">Следующая &rarr;</li>
			</ul>
		</div>
	</loading-overlay>
</template>

<script>
	import LoadingOverlay from '@/components/ui/LoadingOverlay';
	import DropdownList from '@/components/ui/DropdownList';
	import DataMixin from '@/components/ui/mixins/DataMixin';

	export default {
		name: 'DisplayTable',

		mixins: [DataMixin],

		components: {
			LoadingOverlay,
			DropdownList
		},

		props: {
			data: {
				type: [Array, Function]
			},

			deleteCb: Function,

			head: {
				type: Object,
				required: true
			},

			canDelete: {
				type: Boolean,
				default: true
			}
		},

		data() {
			let filterKeys = {}
			  , key;

			for (key in this.head) {
				filterKeys[key] = '';
			}

			return {
				page: 1,
				max: 10,
				sortKey: '',
				sortDir: 1,
				filterKeys: filterKeys,
				list: []
			}
		},

		watch: {
			data: 'update',
			max: 'setMax'
		},

		mounted() {
			if (this.$refs.loading) {
				this.$refs.loading.hide();
			}

			this.update();
		},

		computed: {
			filteredList() {
				let {
						filterKeys,
						sortKey,
						sortDir,
						list
					} = this
				  , key, val;

				this.page = 1;
				
				for (key in filterKeys) {
					val = filterKeys[key];
					val = val && val.toLowerCase();
					if (val) {
						list = list.filter(function (row) {
							return String(row[key]).toLowerCase().indexOf(val) > -1;
						})
					}
					if (list.length == 0) {
						break;
					}
				}

				if (sortKey) {
					list = list.slice().sort(function (a, b) {
						a = a[sortKey];
						b = b[sortKey];

						return (a !== b ? (a > b ? 1 : -1) : 0) * sortDir;
					})
				}
				
				return list;			
			},
			pageList() {
				let {
						filteredList,
						max,
						page
					} = this
				  , to = max * page
				  , from = to - max;
				
				return filteredList.slice(from, to);
			},
			numPages() {
				let {
						filteredList,
						max
					} = this;

				return Math.ceil(filteredList.length / max);
			}
		},

		methods: {
			update() {
				if (this.$refs.loading) {
					this.$refs.loading.show();
				}

				this.updateData();
			},

			setData(data) {
				if (this.$refs.loading) {
					this.$refs.loading.hide();
				}

				if (data instanceof Array) {
					this.list = data;

					this.$emit('updated', data.slice());
				}
			},

			deleteRow(row) {
				let {
						$root,
						$refs,
						deleteCb,
					} = this
				  , loading = $refs.loading;

				if (deleteCb) {
					loading.show();
					deleteCb(row.id).then(response => {
						this.deleteRowHandler(row);
						loading.hide();
					}, errors => {
						$root.$emit('global-error', {
							text: 'DisplayTable: не удалось удалить запись',
							errors: errors
						});
						loading.hide();
					});
				} else {
					this.deleteRowHandler(row);
				}
			},
			deleteRowHandler(row) {
				this.list = this.list.filter(p => p.id !== id);
				this.$emit('delete', row);
			},
			changeRowHandler(row) {
				this.$emit('change', row);
			},

			setMax(max) {
				if (max > 0) {
					this.max = max;
					this.page = 1;
				}
			},
			setSortKey(key) {
				if (this.sortKey == key) {
					this.sortDir *= -1;
				} else {
					this.sortKey = key;
					this.sortDir = 1;
				}
			},
			gotoPage(i) {
				if (i >= 1 && i <= this.numPages) {
					this.page = i;
				}
			},
			prevPage() {
				if (this.page > 1) {
					-- this.page;
				}
			},
			nextPage() {
				if (this.page < this.numPages) {
					++ this.page;
				}
			}
		}
	}
</script>