<script setup lang="ts">
import ApartmentForm, { type ApartmentFormData } from '@/components/ApartmentForm.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Apartment, BreadcrumbItem, Service } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{ apartment: Apartment; services: Service[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'I miei alloggi', href: route('host.apartments.index') },
    { title: 'Modifica', href: route('host.apartments.edit', props.apartment.slug) },
];

const form = useForm<ApartmentFormData>({
    title: props.apartment.title,
    description: props.apartment.description,
    rooms: props.apartment.rooms,
    beds: props.apartment.beds,
    bathrooms: props.apartment.bathrooms,
    square_meters: props.apartment.square_meters,
    address: props.apartment.address,
    city: props.apartment.city,
    price_per_night: props.apartment.price_per_night,
    is_visible: props.apartment.is_visible,
    services: props.apartment.services?.map((s) => s.id) ?? [],
    cover: null,
});

// I file non viaggiano con PUT multipart: si usa POST con method spoofing.
const submit = () =>
    form
        .transform((data) => ({ ...data, _method: 'put' }))
        .post(route('host.apartments.update', props.apartment.slug), { forceFormData: true });
</script>

<template>
    <Head :title="`Modifica: ${apartment.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 sm:p-6">
            <Heading title="Modifica alloggio" :description="apartment.title" />
            <ApartmentForm :form="form" :services="services" :current-cover="apartment.cover_url" submit-label="Salva modifiche" @submit="submit" />
        </div>
    </AppLayout>
</template>
