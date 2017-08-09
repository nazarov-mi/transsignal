
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
	import User from '@/http/models/User';

	export default {
		name: 'Users',

		components: {
			DisplayTable
		},

		data() {
			return {
				model: new User(),
				head: {
					username: 'Логин'
				}
			}
		},

		methods: {
			changeRow(row)
			{
				this.$router.push({
					name: 'admin.users.edit',
					params: {
						id: row.id
					}
				});
			}
		}
	}
</script>