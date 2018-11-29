<template>
    <div>{{ config.translation.aggregatorText }}</div>
</template>
<script>
    define(["Vue"], function(Vue) {
        Vue.component("aggregator", {
            template: template,
            props: ['config', 'item']
        });
    });
</script>
