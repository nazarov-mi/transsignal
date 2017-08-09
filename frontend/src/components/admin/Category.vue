
<template>
	<div class="row">
		<div class="col-l">
			<div class="block is-white">
				<loading-overlay :shown="model.inProcessing">
					<h3>{{ title }}</h3>
					<p>
						<label>Название</label>
						<input type="text" v-model="model.values.name" />
						<label class="help" v-for="error in model.errors.name">
							{{ error }}
						</label>
					</p>
					<p>
						<label>
							<input type="checkbox" v-model="model.values.active" />
							Активен
						</label>
						<label class="help" v-for="error in model.errors.active">
							{{ error }}
						</label>
					</p>
					<p>
						<label>Сортировка</label>
						<input type="number" v-model="model.values.sort" />
						<label class="help" v-for="error in model.errors.sort">
							{{ error }}
						</label>
					</p>
					<p>
						<button @click="save">Сохранить</button>
					</p>
				</loading-overlay>
			</div>
		</div>
	</div>
</template>

<script>
	import LoadingOverlay from '@/components/ui/LoadingOverlay';
	import Category from '@/http/models/Category';

	export default {
		name: 'Category',

		components: {
			LoadingOverlay
		},

		data() {
			return {
				id: this.$route.params.id,
				model: new Category()
			}
		},

		watch: {
			'$route': 'init'
		},

		mounted() {
			this.init();
		},

		computed: {
			title() {
				return this.id !== undefined ? 'Изменить данные' : 'Добавить категорию';
			}
		},

		methods: {
			init()
			{
				let {
						$route,
						model
					} = this
				  , id = $route.params.id;

				this.id = id;

				if (id === undefined) {
					model.reset();
				} else {
					if (id !== model.values.id) {
						model.get(id);
					}
				}
			},
			save()
			{
				let {
						id,
						model
					} = this;
				
				if (id === undefined) {
					model.add();
				} else {
					model.put(id);
				}
			}
		}
	}
</script>