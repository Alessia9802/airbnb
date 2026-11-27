<script setup lang="ts">
import { AirVent, Car, Check, CookingPot, Laptop, PawPrint, Sunset, Tv, WashingMachine, Waves, Wifi } from 'lucide-vue-next';
import { computed, type Component } from 'vue';

// Solo le icone usate dai servizi, per non includere tutta la libreria nel bundle.
const icons: Record<string, Component> = { AirVent, Car, CookingPot, Laptop, PawPrint, Sunset, Tv, WashingMachine, Waves, Wifi };

const props = defineProps<{ name: string }>();
const icon = computed(() => icons[props.name] ?? Check);
</script>

<template>
    <component :is="icon" />
</template>
