
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
						<label>Краткое описание</label>
						<textarea v-model="model.values.short_desc"></textarea>
						<label class="help" v-for="error in model.errors.short_desc">
							{{ error }}
						</label>
					</p>
					<p>
						<label>Описание</label>
						<froala tag="textarea" :config="config" v-model="model.values.desc"></froala>
						<label class="help" v-for="error in model.errors.desc">
							{{ error }}
						</label>
					</p>
					<p>
						<label>Цена</label>
						<input type="number" v-model="model.values.price" />
						<label class="help" v-for="error in model.errors.price">
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
						<label>Категории</label>
						<dropdown-list :options="category.all.bind(category)" :is-multi="true" v-model="model.values.categories"></dropdown-list>
					</p>
					<p>
						<button @click="save">Сохранить</button>
					</p>
				</loading-overlay>
			</div>
		</div>
		<div class="col-s">
			<div class="block is-white" v-if="id !== undefined">
				<picture-gallery :value="model.values.pictures" :save-url="'/api/product/' + id + '/pictures'" delete-url="/api/picture"></picture-gallery>
			</div>
		</div>
	</div>
</template>

<script>
	import PictureGallery from '@/components/ui/PictureGallery';
	import LoadingOverlay from '@/components/ui/LoadingOverlay';
	import DropdownList from '@/components/ui/DropdownList';
	import Category from '@/http/models/Category';
	import Product from '@/http/models/Product';

	window.$ = require('jquery')

	export default {
		name: 'Product',

		components: {
			PictureGallery,
			LoadingOverlay,
			DropdownList
		},

		data() {
			return {
				id: undefined,
				model: new Product(),
				category: new Category(),
				config: {
					placeholderText: 'Начните печатать текст ...',
					charCounterCount: false,
					quickInsertButtons: ['ol', 'ul'],
					toolbarButtons: ['fullscreen', '|', 'undo', 'redo' , '|', 'clearFormatting', 'html', '-', 'bold', 'italic', 'underline', 'formatOL', 'formatUL', 'outdent', 'indent', 'insertLink']
				}
			}
		},

		beforeRouteUpdate(to, from, next) {
			this.update();
			next();
		},

		mounted() {
			this.update();
		},

		computed: {
			title() {
				return this.id !== undefined ? 'Изменить данные' : 'Добавить продукт';
			}
		},

		methods: {
			update()
			{
				let {
						$route,
						model,
					} = this,
					id = $route.params.id;

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