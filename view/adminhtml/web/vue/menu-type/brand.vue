<template>
    <div class="admin__field field">
        <auto-complete
                :label="config.translation.brand"
                :description="config.translation.brandId"
                :item="item"
                :options="config.fieldData.brand.snowMenuAutoCompleteField.options"
                :config="config"
        >
        </auto-complete>
    </div>
</template>
<script>
    define(["Vue"], function(Vue) {
        Vue.component("brand", {
            template: template,
            props: ['config', 'item']
        });
    });
</script>
