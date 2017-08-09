<style lang="scss">
	.base-select {
		position: relative;
		min-height: 48px;
		cursor: pointer;
		z-index: 10;
		
		font-weight: 100;

		-webkit-user-select: none;
		 -khtml-user-select: none;
			-moz-user-select: none;
			  -o-user-select: none;
				  user-select: none;

		&:before {
			content: '';
			position: absolute;
			width: 12px;
			height: 12px;
			top: 50%;
			right: 40px;
			margin-top: -8px;

			border: 1px solid transparent;
			border-right-color: #00a885;
			border-bottom-color: #00a885;

			-webkit-transform: rotate(45deg);
				-moz-transform: rotate(45deg);
					  transform: rotate(45deg);

			z-index: 2;
		}

		&__wrapper {
			position: absolute;
			width: 100%;
			top: 0;
			left: 0;

			background-color: #fff;
			border: 1px solid #ededed;

			-webkit-transition: all 350ms ease-in;
					  transition: all 350ms ease-in;

		}

		&__input {
			padding: 3px 70px 3px 3px;
			overflow: hidden;
			line-height: 42px;
			z-index: 2;

			color: #999;
			white-space: nowrap;
			text-overflow: ellipsis;
		}

		&__placeholder {
			padding-left: 40px;
		}

		&__selected-list {
			margin: 0;
			padding: 0;
			list-style: none;
		}

		&__selected {
			position: relative;
			height: 36px;
			margin: 3px;
			padding: 0 32px 0 10px;
			float: left;
			line-height: 36px;

			background-color: #00a885;
			color: #fff;
			word-break: break-all;
			white-space: initial;
		}

		&__delete {
			position: absolute;
			width: 14px;
			height: 14px;
			top: 50%;
			right: 10px;
			margin-top: -8px;

			&:before,
			&:after {
				content: '';
				position: absolute;
				width: 2px;
				height: 16px;
				top: 0;
				left: 7px;

				background-color: #fff;
			}

			&:before {
				-webkit-transform: rotate(45deg);
					-moz-transform: rotate(45deg);
						  transform: rotate(45deg);
			}

			&:after {
				-webkit-transform: rotate(-45deg);
					-moz-transform: rotate(-45deg);
						  transform: rotate(-45deg);
			}

			&:hover:before,
			&:hover:after {
				background-color: #eee;
			}
		}

		&__option-list {
			max-height: 0;
			top: 0;
			left: 0;
			margin: 0;
			padding: 0;
			overflow: hidden;
			z-index: 1;

			opacity: 0;
			list-style-type: none;

			-webkit-transition: all 500ms cubic-bezier(0.42, 0, 0.2, 1);
					  transition: all 500ms cubic-bezier(0.42, 0, 0.2, 1);
		}

		&__option {
			padding: 4px 40px;
			color: #555;

			&_select {
				background-color: #fafaff;
			}
		}

		&_open {
			z-index: 20;

			&:before {
				margin-top: 0;

				-webkit-transform: rotate(225deg);
					-moz-transform: rotate(225deg);
						  transform: rotate(225deg);
			}
		}

		&_open &__wrapper {
			background-color: #fff;
			border-color: transparent;

			-webkit-box-shadow: 0 2px 20px rgba(0, 0, 51, .1);
				-moz-box-shadow: 0 2px 20px rgba(0, 0, 51, .1);
					  box-shadow: 0 2px 20px rgba(0, 0, 51, .1);
		}

		&_open &__option-list {
			max-height: 500px;
			padding-top: 8px;
			padding-bottom: 8px;
			opacity: 1;
		}
	}
</style>

<template>
	<div class="base-select" :class="{ 'base-select_open': isOpened, 'base-select_multi': isMulti }" :style="{ 'z-index': zIndex, 'height': getInputHeight() }">
		<div class="base-select__wrapper">
			<div class="base-select__input" ref="input" @click.stop="toggle">
				<div class="base-select__placeholder" v-if="selected.length == 0">
					{{ placeholder }}
				</div>
				<div class="base-select__placeholder" v-else-if="!isMulti">
					{{ firstLabel }}
				</div>
				<ul class="base-select__selected-list" v-else>
					<li class="base-select__selected" v-for="option in selectedOptions">
						{{ option.label }}
						<div class="base-select__delete" @click.stop="select(option.value)"></div>
					</li>
				</ul>
			</div>
			<ul class="base-select__option-list" @click.stop="closeHandler">
				<li class="base-select__option" v-if="canClear" @click="clear()">
					<a>Очистить</a>
				</li>
				<li class="base-select__option" v-for="(label, value) in computedOptions" :class="{ 'base-select__option_select': selected.indexOf(value) >= 0 }" @click="select(value)">
					{{ label }}
				</li>
			</ul>
		</div>
		<div v-show="false">
			<select :multiple="isMulti" ref="select">
				<option v-for="option in selectedOptions" :value="option.value">
					{{ option.label }}
				</option>
			</select>
		</div>
	</div>
</template>

<script>
	import DataMixin from '@/components/ui/mixins/DataMixin';

	export default {
		name: 'DropdownList',

		mixins: [DataMixin],
		
		model: {
			prop: 'value',
			event: 'select'
		},

		props: {
			value: [String, Number, Array],

			options: {
				type: [Array, Object, Function],
				required: true
			},
			
			optValue: {
				type: String,
				default: 'id'
			},
			optLabel: {
				type: String,
				default: 'name'
			},

			placeholder: {
				type: String,
				default: 'Не выбрано'
			},
			prefix: {
				type: String,
				default: ''
			},
			postfix: {
				type: String,
				default: ''
			},

			canClear: {
				type: Boolean,
				default: true
			},

			isMulti: {
				type: Boolean,
				default: false
			},

			haveMoreOptions: {
				type: Boolean,
				default: false
			}
		},

		data() {
			return {
				data: this.options,
				
				computedOptions: {},
				selected: [],

				isMobile: !!document.ontouchstart,
				isOpened: false,
				zIndex: undefined,
				transitionEndHandler: this.onTransitionEnd.bind(this),
				clickHandler: this.onClick.bind(this)
			}
		},

		watch: {
			value(val, od) {
				let old = this.selected;

				if (val instanceof Array ? val.join() != old.join() : val != old) {
					this.setSelected(val);
				}
			}
		},

		mounted() {
			let {
					$el,
					transitionEndHandler,
					clickHandler
				} = this;

			$el.addEventListener('webkitTransitionEnd', transitionEndHandler);
			$el.addEventListener('transitionend', transitionEndHandler);
			document.addEventListener('click', clickHandler);

			!this.haveMoreOptions && this.updateData();
		},

		beforeDestroy() {
			let {
					$el,
					transitionEndHandler,
					clickHandler
				} = this;

			$el.removeEventListener('webkitTransitionEnd', transitionEndHandler);
			$el.removeEventListener('transitionend', transitionEndHandler);
			document.removeEventListener('click', clickHandler);
		},

		computed: {
			firstSelected() {
				let selected = this.selected;

				return selected.length > 0 ? selected[0] : undefined;
			},
			firstLabel() {
				let {
						placeholder,
						prefix,
						postfix,
						computedOptions,
						firstSelected
					} = this;

				return firstSelected ? prefix + ' ' +  computedOptions[firstSelected] + ' ' + postfix : placeholder;
			},
			selectedOptions() {
				let {
						prefix,
						postfix,
						computedOptions,
						selected
					} = this
				  , res = []
				  , l = selected.length
				  , i, key;
				
				for (i = 0; i < l; ++ i) {
					key = selected[i];
					res.push({
						value: key,
						label: prefix + ' ' + computedOptions[key] + ' ' + postfix
					});
				}

				return res;
			},
			inputHeight() {
				return this.$refs.input.offsetHeight + 'px';
			}
		},

		methods: {
			setData(options) {
				let res = {};

				if (options instanceof Array || options instanceof Object) {
					if (options instanceof Array && options.length > 0) {
						let l = options.length
						  , i = -1
						  , key;

						if (options[0] instanceof Object) {
							let {
									optValue,
									optLabel
								} = this
							  , option;

							while (++i < l) {
								option = options[i];
								key = option[optValue];
								res[key] = option[optLabel];
							}
						} else {
							while (++i < l) {
								key = options[i];
								res[key] = key;
							}
						}
					} else {
						res = Object.assign(res, options);
					}

					this.clear();
					this.computedOptions = res;

					this.setSelected(this.value);

					this.fireUpdate();
				}
			},
			setSelected(selected) {
				selected = selected || [];

				if (selected && !(selected instanceof Array)) {
					selected = [selected];
				}

				this.selected = selected.map(v => String(v));

				this.fireSelect();
			},
			select(val) {
				let {
						selected,
						firstSelected,
						isMulti
					} = this;

				if (isMulti) {
					let i = selected.indexOf(val);

					if (i < 0) {
						selected.push(val);
					} else {
						selected.splice(i, 1);
					}
				} else {
					if (firstSelected === val) return;
					
					selected[0] = val;
				}

				this.selected = selected.slice();

				this.fireSelect();
			},
			clear() {
				this.selected = [];

				this.fireClear();
				this.fireSelect();
			},

			open() {
				if (this.isMobile) {
					if ('createEvent' in document) {
						var evt = document.createEvent('HTMLEvents');
						evt.initEvent('change', false, true);
						this.$refs.select.dispatchEvent(evt);
					}
					else
						this.$refs.select.fireEvent('onchange');

					return;
				}

				this.isOpened = true;
				this.updateZIndex();
			},
			close() {
				this.isOpened = false;
			},
			toggle() {
				if (this.isOpened) {
					this.close();
				} else {
					this.open()
				}
			},
			closeHandler() {
				!this.isMulti && this.close();
			},

			onTransitionEnd() {
				this.updateZIndex();
			},
			onClick() {
				if (this.isOpened) {
					this.close();
				}
			},
			updateZIndex() {
				this.zIndex = this.isOpened ? 20 : undefined;
			},

			getInputHeight() {
				let input = this.$refs.input;
				
				return (input ? input.offsetHeight + 'px' : 'auto');
			},

			fireUpdate() {
				this.$emit('updated', Object.assign({}, this.computedOptions));
			},
			fireClear() {
				this.$emit('clear');
			},
			fireSelect() {
				this.$emit('select', this.isMulti ? this.selected.slice() : this.firstSelected);
			}
		}
	}
</script>