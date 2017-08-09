
<template>
	<transition name="modal">
		<div class="modal" v-show="isShown">
			<div class="modal-content" :style="modalContentStyle">
				<div class="modal-close close" @click="close"></div>
				<slot></slot>
			</div>
		</div>
	</transition>
</template>

<script>
	export default {
		name: 'ModalCard',

		props: {
			autoWidth: {
				type: Boolean,
				default: false
			}
		},

		data() {
			return {
				isShown: false
			}
		},

		mounted() {
			document.body.appendChild(this.$el);
		},

		destroyed() {
			document.body.removeChild(this.$el);
		},

		computed: {
			modalContentStyle() {
				let res = {};
				
				if (this.autoWidth) {
					res.width = 'auto';
				}

				return res;
			}
		},

		methods: {
			open()
			{
				document.getElementsByTagName('html')[0].classList.add('is-clipped');
				this.isShown = true;
				this.$emit('open');
			},

			close()
			{
				document.getElementsByTagName('html')[0].classList.remove('is-clipped');
				this.isShown = false;
				this.$emit('close');
			},

			toggle()
			{
				this.isShown ? this.close() : this.open();
			}
		}
	}
</script>