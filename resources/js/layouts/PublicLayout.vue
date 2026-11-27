<script setup lang="ts">
import BrandLogo from '@/components/BrandLogo.vue';
import { Button } from '@/components/ui/button';
import type { SharedData } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import { ref } from 'vue';

const props = withDefaults(defineProps<{ search?: string; showSearch?: boolean }>(), { search: '', showSearch: true });

const page = usePage<SharedData>();
const query = ref(props.search);

const submitSearch = () => {
    router.get(route('home'), query.value ? { q: query.value } : {}, { preserveState: true });
};
</script>

<template>
    <div class="min-h-screen bg-background text-foreground">
        <header class="sticky top-0 z-20 border-b bg-background/95 backdrop-blur">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-4 px-4 py-3 sm:px-6">
                <Link :href="route('home')" class="shrink-0">
                    <BrandLogo />
                </Link>

                <form
                    v-if="showSearch"
                    class="order-last flex w-full items-center rounded-full border shadow-sm transition-shadow focus-within:shadow-md sm:order-none sm:mx-auto sm:w-auto sm:min-w-[22rem]"
                    @submit.prevent="submitSearch"
                >
                    <input
                        v-model="query"
                        type="search"
                        placeholder="Dove vuoi andare?"
                        aria-label="Cerca per città o titolo"
                        class="min-w-0 flex-1 rounded-l-full border-0 bg-transparent px-5 py-2.5 text-sm focus:ring-0"
                    />
                    <button type="submit" class="m-1 flex size-9 items-center justify-center rounded-full bg-rose-500 text-white hover:bg-rose-600" aria-label="Cerca">
                        <Search class="size-4" />
                    </button>
                </form>

                <nav class="ml-auto flex items-center gap-2 text-sm sm:ml-0">
                    <Button v-if="page.props.auth.user" as-child variant="ghost" class="rounded-full">
                        <Link :href="route('host.apartments.index')">I miei alloggi</Link>
                    </Button>
                    <template v-else>
                        <Button as-child variant="ghost" class="rounded-full">
                            <Link :href="route('login')">Accedi</Link>
                        </Button>
                        <Button as-child variant="outline" class="rounded-full">
                            <Link :href="route('register')">Diventa host</Link>
                        </Button>
                    </template>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6">
            <slot />
        </main>

        <footer class="border-t py-8 text-center text-sm text-muted-foreground">© {{ new Date().getFullYear() }} Airbnb · Progetto didattico Laravel + Vue</footer>
    </div>
</template>
