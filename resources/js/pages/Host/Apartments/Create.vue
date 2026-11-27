<script setup lang="ts">
import ApartmentForm, { type ApartmentFormData } from '@/components/ApartmentForm.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, Service } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{ services: Service[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'I miei alloggi', href: route('host.apartments.index') },
    { title: 'Nuovo alloggio', href: route('host.apartments.create') },
];

const form = useForm<ApartmentFormData>({
    title: '',
    description: '',
    rooms: 1,
    beds: 1,
    bathrooms: 1,
    square_meters: 40,
    address: '',
    city: '',
    price_per_night: 80,
    is_visible: true,
    services: [],
    cover: null,
});

const submit = () => form.post(route('host.apartments.store'), { forceFormData: true });
</script>

<template>
    <Head title="Nuovo alloggio" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 sm:p-6">
            <Heading title="Nuovo alloggio" description="Inserisci i dettagli del tuo spazio per pubblicarlo." />
            <ApartmentForm :form="form" :services="services" submit-label="Crea alloggio" @submit="submit" />
        </div>
    </AppLayout>
</template>
