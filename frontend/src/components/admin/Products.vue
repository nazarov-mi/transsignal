
<template>
	<div class="row">
		<div class="col-l">
			<div class="block is-white">
				<display-table :data="model.all.bind(model)" :delete-cb="model.delete.bind(model)" :head="head" @change="changeRow"></display-table>
			</div>
		</div>
	</div>
</template>

<script>
	import DisplayTable from '@/components/ui/DisplayTable';
	import Product from '@/http/models/Product';

	export default {
		name: 'Products',

		components: {
			DisplayTable
		},

		data() {
			return {
				model: new Product(),
				head: {
					name: 'Название',
					price: 'Цена'
				}
			}
		},

		methods: {
			changeRow(row)
			{
				this.$router.push({
					name: 'admin.products.edit',
					params: {
						id: row.id
					}
				});
			}
		}
	}
</script>