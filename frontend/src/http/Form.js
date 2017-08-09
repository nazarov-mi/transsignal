
import Field from '@/http/Field'

export default class Form
{
	constructor(fields)
	{
		this.origin = fields;
		this.values = {};
		this.errors = {};

		this.inProcessing = false;

		this.reset();
	}

	startProcessing()
	{
		this.inProcessing = true;
	}

	finishProcessing()
	{
		this.inProcessing = false;
	}

	reset()
	{
		let {
				origin,
				values
			} = this;

		for (let key in origin) {
			values[key] = origin[key];
		}

		this.resetErrors();
	}

	resetErrors()
	{
		let {
				origin,
				errors
			} = this;

		for (let key in origin) {
			errors[key] = [];
		}
	}

	has(key)
	{
		return this.origin.hasOwnProperty(key);
	}

	addData(data)
	{
		let values = this.values;

		for (let key in data) {
			if (this.has(key)) {
				values[key] = data[key];
			}
		}
	}

	setData(data)
	{
		this.reset();
		this.addData(data);
	}

	setErrors(errs)
	{
		let errors = this.errors;

		this.resetErrors();

		for (let key in errs) {
			if (this.has(key)) {
				errors[key] = errs[key];
			}
		}
	}

	toJson()
	{
		let {
				origin,
				values
			} = this
		  , res = {}
		  , key;

		for (key in origin) {
			res[key] = values[key];
		}

		return JSON.stringify(res);
	}
}