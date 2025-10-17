<template>
  <div>
    <h2 style="font-weight: 600; margin-bottom: 8px">Produtos</h2>
    <div class="products-grid">
      <div v-for="product in products" :key="product.name" class="product-card">
        <div>
          <div class="product-name">{{ product.name }}</div>
          <div class="product-price">R$ {{ product.price.toFixed(2) }}</div>
        </div>
        <div style="margin-top: 8px; display: flex; justify-content: flex-end">
          <button class="btn" @click="$emit('add', product)">Adicionar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import api from "../services/api";

export default defineComponent({
  emits: ["add"],
  setup() {
    const products = ref<Array<any>>([]);

    async function loadProducts() {
      try {
        const response = await api.get("/products");
        const rawProducts = response.data.products || [];
        products.value = rawProducts.map((rawProduct: any) => ({
          name: rawProduct.name ?? rawProduct.nome,
          price: rawProduct.price ?? rawProduct.valor,
        }));
      } catch (error) {
        console.error(error);
      }
    }

    onMounted(loadProducts);

    return { products };
  },
});
</script>
