
<script>
	export default {

		methods: {
			updateData() {
				let data = this.data;

				if (!data) return;

				if (typeof data == 'function') {
					let response = data();

					if (response.then) {
						response.then(response => {
							this.setData(response);
						}, errors => {
							this.$root.$emit('global-error', {
								text: 'DataMixin: не удалось загрузить данные',
								errors: errors
							});
						});
					} else {
						this.setData(response);
					}
				} else {
					this.setData(data);
				}
			},
			setData(data) {
				// TO DO
			}
		}
	}
</script>