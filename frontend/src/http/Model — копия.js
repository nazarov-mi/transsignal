
import Form from '@/http/Form'

export default class Model extends Form
{
	constructor(src)
	{
		this.src = src;
		this.inProcessing = false;
	}

	startProcessing()
	{
		this.inProcessing = true;
	}

	finishProcessing()
	{
		this.inProcessing = false;
	}

	all()
	{
		return this.submit('get');
	}

	get(id, form)
	{
		let promise = this.submit('get', '/' + id);

		if (form) {
			form.startProcessing();
			promise.then(data => {
				form.setData(data);
				form.finishProcessing();
				return data;
			})
		}

		return promise;
	}

	add(form)
	{
		return this.submitForm('post', '', form);
	}

	put(id, form)
	{
		return this.submitForm('put', '/' + id, form);
	}

	delete(id)
	{
		return this.submit('delete', '/' + id);
	}

	submitForm(method, uri, form)
	{
		let data = form.toJson();

		form.startProcessing();
		
		return this.submit(method, uri, data)
			.then(data => {
				form.resetErrors()
				form.addData(data);
				form.finishProcessing();
				return data;
			})
			.catch(errors => {
				form.setErrors(errors);
				form.finishProcessing();
				return errors;
			});
	}

	submit(method, uri, data)
	{
		let submitter = window.Http ? window.Http : window.Vue.http
		  , rootUri = 'api/' + this.src + (uri || '');

		this.startProcessing();

		return new Promise((resolve, reject) => {
			submitter[method](rootUri, data)
				.then(response => {
					this.finishProcessing();
					resolve(response.data);
				})
				.catch(errors => {
					this.finishProcessing();
					reject(errors.data);
				});
		});
	}
}