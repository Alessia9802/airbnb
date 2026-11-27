<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import ServiceIcon from '@/components/ServiceIcon.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Service } from '@/types';
import type { InertiaForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

export interface ApartmentFormData {
    title: string;
    description: string;
    rooms: number | string;
    beds: number | string;
    bathrooms: number | string;
    square_meters: number | string;
    address: string;
    city: string;
    price_per_night: number | string;
    is_visible: boolean;
    services: number[];
    cover: File | null;
}

const props = defineProps<{
    form: InertiaForm<ApartmentFormData>;
    services: Service[];
    currentCover?: string | null;
    submitLabel: string;
}>();

const emit = defineEmits<{ submit: [] }>();

const preview = ref<string | null>(props.currentCover ?? null);

const onCoverChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    props.form.cover = file;
    preview.value = file ? URL.createObjectURL(file) : (props.currentCover ?? null);
};

const numberFields = [
    { key: 'rooms', label: 'Stanze', min: 1 },
    { key: 'beds', label: 'Letti', min: 1 },
    { key: 'bathrooms', label: 'Bagni', min: 1 },
    { key: 'square_meters', label: 'Metri quadri', min: 10 },
] as const;
</script>

<template>
    <form class="max-w-3xl space-y-8" @submit.prevent="emit('submit')">
        <section class="grid gap-6">
            <div class="grid gap-2">
                <Label for="title">Titolo</Label>
                <Input id="title" v-model="form.title" required placeholder="Es. Loft luminoso a due passi dal Duomo" />
                <InputError :message="form.errors.title" />
            </div>

            <div class="grid gap-2">
                <Label for="description">Descrizione</Label>
                <textarea
                    id="description"
                    v-model="form.description"
                    required
                    rows="6"
                    class="rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                />
                <InputError :message="form.errors.description" />
            </div>
        </section>

        <section class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div v-for="field in numberFields" :key="field.key" class="grid gap-2">
                <Label :for="field.key">{{ field.label }}</Label>
                <Input :id="field.key" v-model="form[field.key]" type="number" :min="field.min" required />
                <InputError :message="form.errors[field.key]" />
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-[2fr_1fr]">
            <div class="grid gap-2">
                <Label for="address">Indirizzo</Label>
                <Input id="address" v-model="form.address" required placeholder="Via Roma 1" />
                <InputError :message="form.errors.address" />
            </div>
            <div class="grid gap-2">
                <Label for="city">Città</Label>
                <Input id="city" v-model="form.city" required placeholder="Milano" />
                <InputError :message="form.errors.city" />
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2">
            <div class="grid gap-2">
                <Label for="price_per_night">Prezzo a notte (€)</Label>
                <Input id="price_per_night" v-model="form.price_per_night" type="number" min="1" step="0.01" required />
                <InputError :message="form.errors.price_per_night" />
            </div>
            <label class="flex items-center gap-3 self-end pb-2 text-sm font-medium">
                <input v-model="form.is_visible" type="checkbox" class="size-4 rounded border-input text-rose-500 focus:ring-rose-500" />
                Visibile pubblicamente
            </label>
        </section>

        <section class="grid gap-3">
            <Label for="cover">Immagine di copertina</Label>
            <img v-if="preview" :src="preview" alt="Anteprima copertina" class="aspect-[4/3] w-64 rounded-lg object-cover" />
            <input id="cover" type="file" accept="image/*" class="text-sm" @change="onCoverChange" />
            <InputError :message="form.errors.cover" />
        </section>

        <fieldset class="grid gap-3">
            <legend class="mb-3 text-sm font-medium">Servizi</legend>
            <div class="grid gap-3 sm:grid-cols-2">
                <label v-for="service in services" :key="service.id" class="flex cursor-pointer items-center gap-3 rounded-lg border p-3 text-sm has-[:checked]:border-foreground">
                    <input v-model="form.services" type="checkbox" :value="service.id" class="size-4 rounded border-input text-rose-500 focus:ring-rose-500" />
                    <ServiceIcon :name="service.icon" class="size-4" />
                    {{ service.name }}
                </label>
            </div>
            <InputError :message="form.errors.services" />
        </fieldset>

        <div class="flex items-center gap-3">
            <Button type="submit" :disabled="form.processing" class="bg-rose-500 hover:bg-rose-600">
                <LoaderCircle v-if="form.processing" class="size-4 animate-spin" />
                {{ submitLabel }}
            </Button>
            <Button as-child variant="ghost">
                <Link :href="route('host.apartments.index')">Annulla</Link>
            </Button>
        </div>
    </form>
</template>
