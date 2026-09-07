<script setup>
import { router, usePage } from '@inertiajs/vue3'

const page = usePage()
const current = () => page.props.locale ?? 'en'
const locales = () => page.props.locales ?? ['en', 'ar', 'bn', 'da', 'de', 'el', 'es', 'fr', 'he', 'hi', 'it', 'ja', 'nl', 'pa', 'pl', 'pt', 'pt_BR', 'ro', 'ru', 'sv', 'te', 'tr', 'ur', 'vi', 'zh_CN', 'zh_TW']

function switchTo(locale) {
  if (locale !== current()) router.get(`/locale/${locale}`)
}
</script>

<template>
  <select :value="current()" @change="switchTo($event.target.value)" aria-label="Language">
    <option v-for="locale in locales()" :key="locale" :value="locale">{{ locale.toUpperCase() }}</option>
  </select>
</template>