
import Form from '@/http/Form'

export default class Model extends Form
{
	constructor(src, fields)
	{
		super(fields);
		this.src = src;
	}

	all()
	{
		return this.submit('get');
	}

	get(id)
	{
		let promise = this.submit('get', '/' + id);

		promise.then(data => {
			this.setData(data);
			return data;
		});

		return promise;
	}

	add()
	{
		return this.submitForm('post', '');
	}

	put(id)
	{
		return this.submitForm('put', '/' + id);
	}

	delete(id)
	{
		return this.submit('delete', '/' + id);
	}

	submitForm(method, uri)
	{
		let data = this.toJson();
		
		return this.submit(method, uri, data)
			.then(data => {
				this.resetErrors()
				this.addData(data);
				return data;
			})
			.catch(errors => {
				this.setErrors(errors);
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