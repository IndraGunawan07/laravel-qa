export default{
    data(){
        return {
            editing: false,
        }
    },

    methods: {
        edit(){
            this.setEditCache();
            this.editing = true;
        },

        cancel(){
            this.restoreFromCache();
            this.editing = false;
        },

        setEditCache() {},

        restoreFromCache() {},

        update(){
            axios.put(this.endpoint, this.payload())
            .catch(({ response }) => {
                alert(response.data.message)
            })
            .then(({ data }) => {
                this.bodyHtml = data.body_html;
                alert(data.message);
                this.editing = false;
            })
        },

        payload() {},

        destroy() {
            if(confirm('Are you sure?')){
                this.delete();
            }
        },

        delete() {}
    }
}