
<template>
	<div class="row">
		<div class="col-l">
			<div class="block is-white">
				<loading-overlay :shown="model.inProcessing">
					<h3>{{ title }}</h3>
					<p>
						<label>Логин</label>
						<input type="text" v-model="model.values.username" />
						<label class="help" v-for="error in model.errors.username">
							{{ error }}
						</label>
					</p>
					<p>
						<label>Пароль</label>
						<input type="text" v-model="model.values.password" />
						<label class="help" v-for="error in model.errors.password">
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
	import User from '@/http/models/User';

	export default {
		name: 'User',

		components: {
			LoadingOverlay
		},

		data() {
			return {
				id: this.$route.params.id,
				model: new User()
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
				return this.id !== undefined ? 'Изменить данные' : 'Добавить пользователя';
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