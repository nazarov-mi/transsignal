
import Model from '@/http/Model'

export default class Imageable extends Model
{
	constructor(src, fields)
	{
		super(src, fields);
	}

	getPictures(id)
	{
		return this.submit('get', id + '/pictures');
	}

	addPicture(id, data)
	{
		return this.submit('post', id + '/pictures', data);
	}
}