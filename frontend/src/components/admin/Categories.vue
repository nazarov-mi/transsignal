
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
	import Category from '@/http/models/Category';

	export default {
		name: 'Categories',

		components: {
			DisplayTable
		},

		data() {
			return {
				model: new Category(),
				head: {
					name: 'Название'
				}
			}
		},

		methods: {
			changeRow(row)
			{
				this.$router.push({
					name: 'admin.categories.edit',
					params: {
						id: row.id
					}
				});
			}
		}
	}
</script>