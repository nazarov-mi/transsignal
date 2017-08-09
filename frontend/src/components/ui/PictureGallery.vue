
<style scoped lang="scss">
	.gallery {

		&__cont {
			margin: .5rem -.5rem -.5rem;

			&:before,
			&:after {
				content: ' ';
				display: table;
			}

			&:after {
				clear: both;
			}
		}

		&__tile {
			width: 50%;
			padding: .5rem;
			float: left;
		}

		@media screen and (min-device-width: 1200px) {
			&__tile {
				width: 33.333333%;
			}
		}

		@media screen and (min-device-width: 1600px) {
			&__tile {
				width: 25%;
			}
		}

		&__item {
			position: relative;
			width: 100%;
			overflow: hidden;

			& > img {
				display: block;
			}

			&__nav {
				position: absolute;
				width: 100%;
				height: 100%;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				opacity: 0;

				background-color: rgba(0, 0, 51, .2);
				color: #fff;
				font-size: 2rem;

				-webkit-transition: opacity 150ms ease-in;
					-moz-transition: opacity 150ms ease-in;
						  transition: opacity 150ms ease-in;

				&:hover {
					opacity: 1;
				}

				& > * {
					color: inherit;
					font-size: inherit;
					cursor: pointer;

					&:hover {
						color: #00a885;
					}
				}
			}
		}
	}
</style>

<template>
	<div>
		<image-cutter :save-url="saveUrl" @saved="addPicture"></image-cutter>
		<loading-overlay ref="loading">
			<div class="gallery__cont">
				<div class="gallery__tile" v-if="pictures.length > 0" v-for="pic in pictures">
					<div class="gallery__item">
						<div class="gallery__item__nav level">
							<div class="level-item">
								<i class="fa fa-search" aria-hidden="true" @click="openFullPicture(pic)"></i>
							</div>
							<div class="level-item">
								<i class="fa fa-times" aria-hidden="true" @click="deletePicture(pic)"></i>
							</div>
						</div>
						<img :src="pic.preview" width="100%" height="100%" title="" />
					</div>
				</div>
				<div v-else>
					<h3>Пусто</h3>
				</div>
			</div>
		</loading-overlay>
		<modal-card ref="modal" :auto-width="true">
			<img v-if="selectedPicture" :src="selectedPicture.full" width="100%" />
		</modal-card>
	</div>
</template>

<script>
	import LoadingOverlay from '@/components/ui/LoadingOverlay';
	import ImageCutter from '@/components/ui/ImageCutter';
	import ModalCard from '@/components/ui/ModalCard';

	export default {
		name: 'PictureGallery',

		model: {
			prop: 'value',
			event: 'change'
		},

		components: {
			LoadingOverlay,
			ImageCutter,
			ModalCard
		},

		props: {
			getUrl: {
				type: String,
				default: undefined
			},
			saveUrl: {
				type: String,
				required: true
			},
			deleteUrl: {
				type: String,
				required: true
			},
			value: {
				type: Array,
				default: null
			}
		},

		data() {
			return {
				pictures: this.value || [],
				selectedPicture: null
			}
		},

		watch: {
			value(val) {
				this.pictures = val || [];
			},
			getUrl(val) {
				this.load();
			}
		},

		mounted() {
			if (this.getUrl) {
				this.load();
			}
		},

		methods: {
			load()
			{
				let {
						getUrl,
						$root,
						$refs,
						$http
					} = this
				  , loading = $refs.loading;

				loading.show();
				$http.get(getUrl).then(response => {
					this.pictures = response.data;
					loading.hide();
				}, errors => {
					$root.$emit('global-error', {
						text: 'PictureGallery: не удалось загрузить список изображений (' + getUrl + ')',
						errors: errors.data
					});
					loading.hide();
				});
			},
			addPicture(pic)
			{
				this.pictures.push(pic);
				this.$emit('change', this.pictures);
			},
			deletePicture(pic)
			{
				let {
						deleteUrl,
						$root,
						$refs,
						$http
					} = this
				  , url = deleteUrl + '/' + pic.id
				  , loading = $refs.loading;

				loading.show();
				$http.delete(url).then(response => {
					this.pictures = this.pictures.filter(p => p.id !== pic.id);
					loading.hide();
				}, errors => {
					$root.$emit('global-error', {
						text: 'PictureGallery: не удалось удалить изображение (' + url + ')',
						errors: errors.data
					});
					loading.hide();
				});
			},
			openFullPicture(pic)
			{
				this.selectedPicture = pic;
				this.$refs.modal.open();
			}
		}
	}
</script>