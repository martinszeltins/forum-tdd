let user = window.ApplicationCache.user;

let authorizations = {
    updateReply(reply)
    {
        return reply.user_d === user.id
    }
}

export default authorizations