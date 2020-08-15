<template>
    <div class="media post">
        <vote :model="answer" name="answer"></vote>
        <div class="media-body">
            <form v-if="editing" @submit.prevent="update">
                <div class="form-group">
                    <textarea class="form-control" v-model="body" rows="10" required></textarea>
                </div>
                <button class="btn btn-primary" :disabled="isInvalid">Update</button>
                <button class="btn btn-outline-secondary" @click="cancel" type="button">Cancel</button>
            </form>
            <!-- bisa pake v-if="editing === false" atau !editing atau pake v-else -->
            <div v-else>
                <!-- {!! $answer->body_html !!} -->
                <div v-html="bodyHtml"></div>
                <div class="row">
                    <div class="col-4">
                        <div class="ml-auto">
                            <!-- <a href="{{ route('questions.answers.edit', [$question->id, $answer->id]) }}" class="btn btn-sm btn-outline-info">Edit</a> -->
                            <a v-if="authorize('modify', answer)" @click.prevent="edit" class="btn btn-sm btn-outline-info">Edit</a>
                        
                            <button v-if="authorize('modify', answer)" @click="destroy" class="btn btn-sm btn-outline-danger">Delete</button>
                        </div>
                    </div>
                    <div class="col-4">
        
                    </div>
                    <div class="col-4">
                        <user-info :model="answer" label="Answered"></user-info>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['answer'],

    data(){
        return {
            editing: false,
            body: this.answer.body,
            bodyHtml: this.answer.body_html,
            id: this.answer.id,
            questionId: this.answer.question_id,
            beforeEditCache: null
        }
    },

    methods: {
        edit(){
            this.beforeEditCache = this.body;
            this.editing = true;
        },
        cancel(){
            this.body = this.beforeEditCache;
            this.editing = false;
        },
        // update method
        // when this method is called, we will send to our server a ajax request using axios library
        update(){
            axios.patch(this.endpoint, {
                body: this.body
            })
            .then(res => {
                // console.log(res);
                this.editing = false;
                this.bodyHtml = res.data.body_html;
                alert(res.data.message);
            })
            .catch(err => {
                alert(err.response.data.message);
                // console.log("Something went wrong");
            });
        },
        
        destroy() {
            if(confirm('Are you sure?')){
                axios.delete(this.endpoint)
                .then(res => {
                    this.$emit('deleted')
                });
            }
        }
    },

    computed: {
        isInvalid() {
            return this.body.length < 10;
        },

        endpoint() {
            return `/questions/${this.questionId}/answers/${this.id}`;
        }
    }
}
</script>