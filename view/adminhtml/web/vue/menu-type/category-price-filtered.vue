<template>
    <div class="admin__field field">
        <auto-complete
                :label="config.translation.category"
                :description="config.translation.categoryId"
                :item="item"
                :options="config.fieldData['category_price_filtered'].snowMenuAutoCompleteField.options"
                :config="config"
        >
        </auto-complete>
        <simple-field
                :label="config.translation.min_price"
                id="min_price"
                type="text"
                v-model="item.min"
        >
        </simple-field>
        <simple-field
                :label="config.translation.max_price"
                id="max_price"
                type="text"
                v-model="item.max"
        >
        </simple-field>
    </div>
</template>
<script>
    define(["Vue"], function(Vue) {
        Vue.component("category_price_filtered", {
            template: template,
            props: ['config', 'item']
        });
    });
</script>
