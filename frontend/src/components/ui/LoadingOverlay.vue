
<style lang="scss">
	.loading-overlay {
		position: relative;
		min-height: 0;

		-webkit-transition: min-height 500ms ease-in;
				  transition: min-height 500ms ease-in;

		&__spinner {
			position: absolute;
			z-index: 20;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;

			background-color: rgba(255, 255, 255, .8);
			pointer-events: auto;

			&:before {
				content: '';
				position: absolute;
				width: 50px;
				height: 50px;
				top: 50%;
				left: 50%;
				margin: -25px 0 0 -25px;

				border: 2px solid transparent;
				border-top-color: #00a885;
				border-right-color: #00a885;

				-webkit-border-radius: 50px;
						  border-radius: 50px;

				-webkit-animation: spinAround 500ms infinite linear;
						  animation: spinAround 500ms infinite linear;
			}

			&-enter-active,
			&-leave-active {
				transition: opacity 500ms ease-in;
			}

			&-enter,
			&-leave-to {
				opacity: 0;
			}
		}

		&.is-loading {
			min-height: 80px;
		}
	}

	@-webkit-keyframes spinAround {
		from {
			-webkit-transform: rotate(0deg);
					  transform: rotate(0deg);
		}
		to {
			-webkit-transform: rotate(359deg);
					  transform: rotate(359deg);
		}
	}

	@keyframes spinAround {
		from {
			-webkit-transform: rotate(0deg);
					  transform: rotate(0deg);
		}
		to {
			-webkit-transform: rotate(359deg);
					  transform: rotate(359deg);
		}
	}
</style>

<template>
	<div class="loading-overlay" :class="{ 'is-loading': isShown }">
		<transition name="loading-overlay__spinner">
			<div class="loading-overlay__spinner" v-if="isShown"></div>
		</transition>
		<slot></slot>
	</div>
</template>

<script>
	export default {
		name: 'LoadingOverlay',

		props: {
			shown: {
				type: Boolean,
				default: false
			}
		},

		data() {
			return {
				isShown: this.shown
			}
		},

		watch: {
			shown(val) {
				this.isShown = val;
			}
		},

		methods: {
			show()
			{
				this.isShown = true;
			},
			hide()
			{
				this.isShown = false;
			},
			toggle()
			{
				this.isShown ? this.hide() : this.show();
			}
		}
	}
</script>