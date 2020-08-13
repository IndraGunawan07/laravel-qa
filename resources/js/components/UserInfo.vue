<template>
    <div>
        <span class="text-muted">{{ postDate }}</span>
        <div class="media mt-2">
            <a :href="user.url" class="pr-2">
                <img :src="user.avatar">
            </a>
            <div class="media-body mt-1">
                <a :href="user.url">{{ user.name }}</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    // this property receive a list of attributes that are exposed to accept data 
    //from the parent component, the value can be object or array
    props: ['model' ,'label'],
    // <user-info model="..." label=""></user-info>
    computed: {
        postDate(){
            return this.label + " " + this.model.created_date;
        }
    },

    data(){
        return {
            user: this.model.user
        }
    }
}
</script>

