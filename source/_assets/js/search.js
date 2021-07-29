import Fuse from 'fuse.js'
import axios from 'axios'

export default function Search() {
    return {
        fuse: null,
        searching: false,
        query: '',

        get results() {
            return this.query ? this.fuse.search(this.query) : []
        },

        showInput() {
            this.searching = true
            this.$nextTick(() => {
                this.$refs.search.focus()
            })
        },
        reset() {
            this.query = ''
            this.searching = false
        },

        init() {
            axios('/index.json').then(response => {
                this.fuse = new Fuse(response.data, {
                    minMatchCharLength: 6,
                    keys: ['title', 'snippet', 'categories'],
                })
            })
        }
    }
}