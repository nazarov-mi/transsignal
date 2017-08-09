
<style>
	.imagecutter__drop-area {
		padding: 2rem;
		cursor: pointer;
		background-color: #fafbff;
		border: 2px dashed #f2f4ff;
		text-align: center;

		transition: all 150ms ease-in;
	}

	.imagecutter__drop-area:hover {
		background-color: #f2f4ff;
	}

	.imagecutter__cont {
		position: relative;
		margin: 0 auto 1em;
		overflow: hidden;
	}

	.imagecutter__mask {
		position: absolute;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		z-index: 10;
		background-color: rgba(242, 244, 255, 0.5);
		border: 0 dashed #f66;
	}

	.imagecutter__mask_top {
		bottom: auto;
		border-bottom-width: 2px;
	}

	.imagecutter__mask_right {
		left: auto;
		border-left-width: 2px;
	}

	.imagecutter__mask_bottom {
		top: auto;
		border-top-width: 2px;
	}

	.imagecutter__mask_left {
		right: auto;
		border-right-width: 2px;
	}

	.imagecutter__img {
		position: absolute;
		display: block;
		cursor: move;
		
		-webkit-user-select: none;
			-moz-user-select: none;
			 -ms-user-select: none;
				  user-select: none;
	}

	.imagecutter__preview {
		display: block;
		margin: 0 auto;
	}
</style>

<template>
	<div>
		<div
			class="imagecutter__drop-area"
			@dragleave="preventDefault"
			@dragover="preventDefault"
			@dragenter="preventDefault"
			@click="clickHandler"
			@drop="changeHandler">
			<input type="file" ref="fileinput" v-show="false" @change="changeHandler" />
			<p><i class="fa fa-upload fa-2x" aria-hidden="true"></i></p>
			<p>Нажмите, или перетащите файл в это окно</p>
		</div>
		<modal-card ref="modal" :auto-width="true">
			<loading-overlay ref="loading">
				<div class="imagecutter__cont" :style="imgContStyle">
					<img
						class="imagecutter__img"
						v-show="sourceImgUrl"
						:src="sourceImgUrl"
						:style="imgStyle"
						@drag="preventDefault"
						@dragstart="preventDefault"
						@dragend="preventDefault"
						@dragleave="preventDefault"
						@dragover="preventDefault"
						@dragenter="preventDefault"
						@drop="preventDefault"
						@touchend="preventDefault"
						@touchcancel="preventDefault"
						@mouseup="preventDefault"
						@mouseout="preventDefault"
						@touchmove="preventDefault"
						@mousemove="preventDefault"
						@wheel="wheelHandler"
						@touchstart="startDrag"
						@mousedown="startDrag" />
					<div class="imagecutter__mask imagecutter__mask_top" :style="imgMaskStyle.horizontal"></div>
					<div class="imagecutter__mask imagecutter__mask_right" :style="imgMaskStyle.vertical"></div>
					<div class="imagecutter__mask imagecutter__mask_bottom" :style="imgMaskStyle.horizontal"></div>
					<div class="imagecutter__mask imagecutter__mask_left" :style="imgMaskStyle.vertical"></div>
				</div>
				<p>
					<input type="range" step="1" min="0" max="100" :value="imgData.range" @input="rangeChangeHandler" />
				</p>
				<p>
					<img class="imagecutter__preview" :src="previewImgUrl" :width="size" :height="size" />
				</p>
				<p>
					<button class="is-fullwidth" @click="save">Сохранить</button>
				</p>
			</loading-overlay>
		</modal-card>
		<canvas v-show="false" ref="canvas" :width="size" :height="size"></canvas>
	</div>
</template>

<script>
	import ModalCard from '@/components/ui/ModalCard';
	import LoadingOverlay from '@/components/ui/LoadingOverlay';

	export default {
		name: 'ImageCutter',

		components: {
			ModalCard,
			LoadingOverlay
		},

		props: {
			saveUrl: {
				type: String,
				required: true
			},
			size: {
				type: Number,
				default: 200
			}
		},

		data() {
			return {
				sourceImgUrl: '',
				previewImgUrl: '',
				isSupportTouch: document.hasOwnProperty('ontouchstart'),
				offset: 58,
				imgData: {
					x: 0,
					y: 0,
					w: 0,
					h: 0,
					minW: 0,
					minH: 0,
					naturalW: 0,
					naturalH: 0,
					range: 0,
					maxScale: 1,
					source: null
				},
				mouse: {
					on: false,
					mx: 0,
					my: 0,
					x: 0,
					y: 0
				},
				stopDragHandler: this.stopDrag.bind(this),
				onDragHandler: this.onDrag.bind(this)
			}
		},

		mounted() {
			let {
					stopDragHandler,
					onDragHandler
				} = this;

			document.addEventListener('touchend', stopDragHandler);
			document.addEventListener('touchcancel', stopDragHandler);
			document.addEventListener('touchmove', onDragHandler);
			document.addEventListener('mouseup', stopDragHandler);
			document.addEventListener('mousemove', onDragHandler);
		},

		beforeDestroy() {
			let {
					stopDragHandler,
					onDragHandler
				} = this;

			document.removeEventListener('touchend', stopDragHandler);
			document.removeEventListener('touchcancel', stopDragHandler);
			document.removeEventListener('touchmove', onDragHandler);
			document.removeEventListener('mouseup', stopDragHandler);
			document.removeEventListener('mousemove', onDragHandler);
		},

		computed: {
			imgContStyle() {
				let {
						size,
						offset
					} = this;

				return {
					width: size + offset * 2 + 'px',
					height: size + offset * 2 + 'px'
				}
			},
			imgMaskStyle() {
				let {
						size,
						offset
					} = this
				  , sa = offset + 'px'
				  , sb = size + offset * 2 + 'px';

				return {
					vertical: {
						width: sa,
						height: sb
					},
					horizontal: {
						width: sb,
						height: sa
					}
				}
			},
			imgStyle() {
				let {
						imgData,
						offset
					} = this;

				return {
					top: imgData.y + offset + 'px',
					left: imgData.x + offset + 'px',
					width: imgData.w + 'px',
					height: imgData.h + 'px'
				}
			}
		},

		methods: {
			/* Handlers */

			preventDefault(e)
			{
				e.preventDefault();
				return false;
			},
			clickHandler(e)
			{
				if (e.target !== this.$refs.fileinput) {
					e.preventDefault();
					if (document.activeElement !== this.$refs) {
						this.$refs.fileinput.click();
					}
				}
			},
			changeHandler(e)
			{
				e.preventDefault();

				let files = e.target.files || e.dataTransfer.files;
				this.reset();
				if (this.checkFile(files[0])) {
					this.setSourceImg(files[0]);
				}
			},
			rangeChangeHandler(e)
			{
				this.zoom(e.target.value);
			},
			wheelHandler(e)
			{
				e.preventDefault();

				let imgData = this.imgData
				  , delta = e.wheelDelta > 0 ? 1 : -1
				  , range = imgData.range + 5 * delta;

				this.zoom(range);
			},


			reset()
			{
				this.sourceImgUrl = '';
				this.previewImgUrl = '';
			},
			checkFile(file)
			{
				return (file && file.type.indexOf('image') !== -1);
			},
			setSourceImg(file)
			{
				let fr = new FileReader();
				fr.onload = (e => {
					this.sourceImgUrl = fr.result;
					this.startCrop();
				});
				fr.readAsDataURL(file);
			},
			startCrop()
			{
				let {
						size,
						sourceImgUrl,
						offset,
						imgData
					} = this
				  , img = new Image();

				img.src = this.sourceImgUrl;
				img.onload = (() => {
					let naturalW = img.naturalWidth
					  , naturalH = img.naturalHeight
					  , scale = size / Math.min(naturalW, naturalH)
					  , w = naturalW * scale
					  , h = naturalH * scale;

					if (naturalW < size || naturalH < size) {
						return false;
					}

					imgData.x = (size - w) * .5;
					imgData.y = (size - h) * .5;
					imgData.w = w;
					imgData.h = h;
					imgData.minW = w;
					imgData.minH = h;
					imgData.naturalW = naturalW;
					imgData.naturalH = naturalH;
					imgData.range = 0;
					imgData.maxScale = naturalW / w;
					imgData.source = img;

					this.createPreview();
					this.$refs.modal.open();
				});
			},
			startDrag(e)
			{
				e.preventDefault();
				if (this.isSupportTouch && !e.targetTouches) {
					return false;
				}
				e = e.targetTouches ? e.targetTouches[0] : e;

				let {
						imgData,
						mouse
					} = this;

				mouse.mx = e.screenX;
				mouse.my = e.screenY;
				mouse.x = imgData.x;
				mouse.y = imgData.y;
				mouse.on = true;
			},
			stopDrag(e)
			{
				let mouse = this.mouse;

				if (mouse.on) {
					this.createPreview();
					mouse.on = false;
				}
			},
			onDrag(e)
			{
				if (!this.mouse.on) return false;

				e.preventDefault();
				if (this.isSupportTouch && !e.targetTouches) {
					return false;
				}
				e = e.targetTouches ? e.targetTouches[0] : e;

				let {
						size,
						imgData,
						mouse
					} = this
				  , nx = e.screenX - mouse.mx + mouse.x
				  , ny = e.screenY - mouse.my + mouse.y

				if (nx > 0) {
					nx = 0;
				}

				if (ny > 0) {
					ny = 0;
				}

				if (nx < size - imgData.w) {
					nx = size - imgData.w;
				}

				if (ny < size - imgData.h) {
					ny = size - imgData.h;
				}
				
				imgData.x = nx;
				imgData.y = ny;
			},
			zoom(val)
			{
				val = Math.min(Math.max(val, 0), 100);

				let {
						size,
						imgData
					} = this
				  , {
						x, y,
						w, h,
						minW,
						minH,
						maxScale
					} = imgData
				  , scale = 1 + maxScale * val / 100
				  , nw = minW * scale
				  , nh = minH * scale
				  , nx = size * .5 - (size * .5 - x) * (nw / w)
				  , ny = size * .5 - (size * .5 - y) * (nh / h);

				if (nx > 0) {
					nx = 0;
				}

				if (ny > 0) {
					ny = 0;
				}

				if (nx < size - nw) {
					nx = size - nw;
				}

				if (ny < size - nh) {
					ny = size - nh;
				}
				
				imgData.x = nx;
				imgData.y = ny;
				imgData.w = nw;
				imgData.h = nh;
				imgData.range = val;

				setTimeout(() => {
					if (imgData.range == val) {
						this.createPreview();
					}
				}, 300);
			},
			createPreview()
			{
				if (!this.sourceImgUrl) return;

				let {
						size,
						imgData
					} = this
				  , {
				  		x, y,
				  		w, h,
				  		source
				  	} = imgData
				  , canvas = this.$refs.canvas
				  , ctx = canvas.getContext('2d');

				ctx.clearRect(0, 0, size, size);
				ctx.drawImage(source, x, y, w, h);
				
				this.previewImgUrl = canvas.toDataURL('image/jpeg');
			},
			save()
			{
				if (!this.sourceImgUrl) return;

				let {
						saveUrl,
						size,
						sourceImgUrl,
						imgData,
						$root,
						$refs,
						$http
					} = this
				  , {
				  		x, y,
				  		w, h,
				  		naturalW,
				  		naturalH
					} = imgData
				  , modal = $refs.modal
				  , loading = $refs.loading
				  , scale = naturalW / w
				  , xp = ~~Math.abs(x * scale)
				  , yp = ~~Math.abs(y * scale)
				  , sp = ~~(size * scale)
				  , formData = new FormData()
				  , data, buffer, source, i;

				data = sourceImgUrl.split(',')[1];
				data = window.atob(data);
				buffer = new Uint8Array(data.length);

				for (i = 0; i < data.length; i ++) {
				   buffer[i] = data.charCodeAt(i);
				};
				
				source = new Blob([buffer], { type: 'image/jpeg' });

				formData.append('source', source, 'source.jpg');
				formData.append('x', xp);
				formData.append('y', yp);
				formData.append('size', sp);

				this.$emit('save', {
					source: source,
					x: xp,
					y: yp,
					size: sp
				})

				loading.show();
				$http.post(saveUrl, formData).then(response => {
					this.$emit('saved', response.data);
					modal.close();
					loading.hide();
				}, errors => {
					$root.$emit('global-error', {
						text: 'ImageCutter: не удалось сохранить изображение (' + saveUrl + ')',
						errors: errors.data
					});
					modal.close();
					loading.hide();
				});
			}
		}
	}
</script>