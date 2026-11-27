<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatPrice } from '@/lib/format';
import type { Apartment, BreadcrumbItem, SharedData } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Eye, EyeOff, ImageOff, Pencil, Plus, Trash2 } from 'lucide-vue-next';

defineProps<{ apartments: Apartment[] }>();

const page = usePage<SharedData>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'I miei alloggi', href: route('host.apartments.index') }];

const destroy = (apartment: Apartment) => {
    if (confirm(`Eliminare "${apartment.title}"? L'operazione non si può annullare.`)) {
        router.delete(route('host.apartments.destroy', apartment.slug), { preserveScroll: true });
    }
};
</script>

<template>
    <Head title="I miei alloggi" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold">I miei alloggi</h1>
                    <p class="text-sm text-muted-foreground">Gestisci gli spazi che metti a disposizione degli ospiti.</p>
                </div>
                <Button as-child class="bg-rose-500 hover:bg-rose-600">
                    <Link :href="route('host.apartments.create')"><Plus class="size-4" /> Nuovo alloggio</Link>
                </Button>
            </div>

            <p v-if="page.props.flash.success" class="rounded-lg bg-green-100 px-4 py-2 text-sm text-green-900 dark:bg-green-900/30 dark:text-green-200">
                {{ page.props.flash.success }}
            </p>

            <div v-if="!apartments.length" class="rounded-xl border border-dashed p-12 text-center">
                <p class="font-medium">Non hai ancora pubblicato alloggi</p>
                <p class="mt-1 text-sm text-muted-foreground">Crea il primo annuncio in pochi minuti.</p>
            </div>

            <ul v-else class="divide-y rounded-xl border">
                <li v-for="apartment in apartments" :key="apartment.id" class="flex flex-wrap items-center gap-4 p-4">
                    <div class="size-20 shrink-0 overflow-hidden rounded-lg bg-muted">
                        <img v-if="apartment.cover_url" :src="apartment.cover_url" :alt="apartment.title" class="size-full object-cover" />
                        <ImageOff v-else class="m-auto mt-7 size-6 text-muted-foreground" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <Link :href="route('apartments.show', apartment.slug)" class="font-medium hover:underline">{{ apartment.title }}</Link>
                        <p class="text-sm text-muted-foreground">{{ apartment.city }} · {{ formatPrice(apartment.price_per_night) }} / notte</p>
                        <p class="mt-1 inline-flex items-center gap-1 text-xs" :class="apartment.is_visible ? 'text-green-700 dark:text-green-400' : 'text-muted-foreground'">
                            <component :is="apartment.is_visible ? Eye : EyeOff" class="size-3.5" />
                            {{ apartment.is_visible ? 'Pubblico' : 'Nascosto' }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <Button as-child variant="outline" size="sm">
                            <Link :href="route('host.apartments.edit', apartment.slug)"><Pencil class="size-4" /> Modifica</Link>
                        </Button>
                        <Button variant="outline" size="sm" class="text-red-600 hover:text-red-700" @click="destroy(apartment)">
                            <Trash2 class="size-4" /> Elimina
                        </Button>
                    </div>
                </li>
            </ul>
        </div>
    </AppLayout>
</template>
