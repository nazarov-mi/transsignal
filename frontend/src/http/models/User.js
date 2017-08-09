
import Model from '@/http/Model'

export default class User extends Model
{
	constructor()
	{
		super('user');
	}

	login(username, password)
	{
		return this.submit('post', '/login', {
			username: username,
			password: password
		});
	}

	logout(token)
	{
		return this.submit('get', '/logout');
	}

	check(token)
	{
		return this.submit('get', '/check');
	}
}