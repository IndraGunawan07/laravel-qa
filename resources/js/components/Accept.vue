<template>
    <div>
        <a v-if="canAccept" title="Mark this answer as best answer" 
        :class="classes"
        @click.prevent="create">
            <i class="fa fa-check fa-2x"></i>
        </a>
        <!-- <form id="accept-answer-{{ $model->id }}" action="{{ route('answers.accept', $model->id) }}" method="POST" style="display: none;">
            @csrf
        </form> -->
        <a v-if="accepted" title="The question owner accepted this answer as best answer" 
            :class="classes">
                <i class="fa fa-check fa-2x"></i>
        </a>
    </div>
</template>

<script>
export default {
    props: ['answer'],

    data(){
        return {
            isBest: this.answer.is_best,
            id: this.answer.id
        };
    },

    methods: {
        create() {
            axios.post(`/answers/${this.id}/accept`)
            .then(res => {
                alert(res.data.message);
                this.isBest = true;
            })
        }
    },

    computed: {
        canAccept(){
            return true;
        },

        accepted(){
            return !this.canAccept && this.isBest;
        },

        classes(){
            return [
                'mt-2',
                this.isBest ? 'vote-accepted' : ''
            ];
        }
    }
}
</script>