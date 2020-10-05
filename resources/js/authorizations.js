let user = window.ApplicationCache.user;

let authorizations = {
    owns(model, prop = 'user_id')
    {
        return model[prop] === user.id
    },
}

export default authorizations