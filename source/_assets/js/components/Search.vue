<template>
    <div class="flex items-center justify-end flex-1 px-4 text-right">
        <div
            class="absolute top-0 left-0 z-10 justify-end w-full px-4 bg-white md:relative mt-7 md:mt-0 md:px-0"
            :class="{'hidden md:flex': ! searching}"
        >
            <label for="search" class="hidden">Search</label>

            <input
                id="search"
                v-model="query"
                ref="search"
                class="relative block w-full h-10 px-4 pt-px pb-0 text-gray-700 transition-all duration-200 ease-out bg-gray-100 border border-gray-500 outline-none cursor-pointer lg:w-1/2 lg:focus:w-3/4 focus:border-blue-400"
                :class="{ 'transition-border': query }"
                autocomplete="off"
                name="search"
                placeholder="Search"
                type="text"
                @keyup.esc="reset"
                @blur="reset"
            >

            <button
                v-if="query || searching"
                class="absolute top-0 right-0 text-3xl leading-snug text-blue-500 font-400 hover:text-blue-600 focus:outline-none pr-7 md:pr-3"
                @click="reset"
            >&times;</button>

            <transition name="fade">
                <div v-if="query" class="absolute left-0 right-0 w-full mb-4 text-left md:inset-auto lg:w-3/4 md:mt-10">
                    <div class="flex flex-col mx-4 bg-white border border-t-0 border-b-0 border-blue-400 rounded-b-lg shadow-search md:mx-0">
                        <a
                            v-for="(result, index) in results"
                            class="p-4 text-xl bg-white border-b border-blue-400 cursor-pointer hover:bg-blue-100"
                            :class="{ 'rounded-b-lg': (index === results.length - 1) }"
                            :href="result.item.link"
                            :title="result.item.title"
                            :key="result.link"
                            @mousedown.prevent
                        >
                            {{ result.item.title }}

                            <span class="block my-1 text-sm font-normal text-gray-700" v-html="result.item.snippet"></span>
                        </a>

                        <div
                            v-if="! results.length"
                            class="w-full p-4 bg-white border-b border-blue-400 rounded-b-lg shadow cursor-pointer hover:bg-blue-100"
                        >
                            <p class="my-0">No results for <strong>{{ query }}</strong></p>
                        </div>
                    </div>
                </div>
            </transition>
        </div>

        <button
            title="Start searching"
            type="button"
            class="flex items-center justify-center h-10 px-3 bg-gray-100 border border-gray-500 rounded-full md:hidden hover:bg-blue-100 focus:outline-none"
            @click.prevent="showInput"
        >
            <img src="/assets/img/magnifying-glass.svg" alt="search icon" class="w-4 h-4 max-w-none">
        </button>
    </div>
</template>

<script>
import Fuse from 'fuse.js';

export default {
    data() {
        return {
            fuse: null,
            searching: false,
            query: '',
        };
    },
    computed: {
        results() {
            return this.query ? this.fuse.search(this.query) : [];
        },
    },
    methods: {
        showInput() {
            this.searching = true;
            this.$nextTick(() => {
                this.$refs.search.focus();
            })
        },
        reset() {
            this.query = '';
            this.searching = false;
        },
    },
    created() {
        axios('/index.json').then(response => {
            this.fuse = new Fuse(response.data, {
                minMatchCharLength: 6,
                keys: ['title', 'snippet', 'categories'],
            });
        });
    },
};
</script>

<style>
input[name='search'] {
    background-image: url('/assets/img/magnifying-glass.svg');
    background-position: 0.8em;
    background-repeat: no-repeat;
    border-radius: 25px;
    text-indent: 1.2em;
}

input[name='search'].transition-border {
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    border-top-left-radius: .5rem;
    border-top-right-radius: .5rem;
}

.fade-enter-active {
    transition: opacity .5s;
}

.fade-leave-active {
    transition: opacity 0s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
