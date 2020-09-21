export default {
    props: ["initialRepliesCount"],

    components:
    {
        Replies,
        SubscribeButton
    },

    data()
    {
        return {
            repliesCount: this.initialRepliesCount
        };
    }
};
