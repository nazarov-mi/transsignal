
export default class Field
{
	constructor(def)
	{
		this.default = def;
		this.value = def;
		this.errors = [];
	}

	reset()
	{
		this.value = this.default;
		this.errors = [];
	}

	resetErrors()
	{
		this.errors = [];
	}

	setValue(val)
	{
		this.value = val || this.default;
	}

	setErrors(errors)
	{
		this.errors = errors || [];
	}
}