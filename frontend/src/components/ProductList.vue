<template>
  <div>
    <h2 style="font-weight: 600; margin-bottom: 8px">Produtos</h2>
    <div class="products-grid">
      <div v-for="p in products" :key="p.name" class="product-card">
        <div>
          <div class="product-name">{{ p.name }}</div>
          <div class="product-price">R$ {{ p.price.toFixed(2) }}</div>
        </div>
        <div style="margin-top: 8px; display: flex; justify-content: flex-end">
          <button class="btn" @click="$emit('add', p)">Adicionar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import axios from "axios";
import api from "../services/api";

export default defineComponent({
  emits: ["add"],
  setup() {
    const products = ref<Array<any>>([]);

    async function load() {
      try {
        const res = await api.get("/products");
        // Accept products with English keys (name, price) or Portuguese (nome, valor)
        const raw = res.data.products || [];
        products.value = raw.map((p: any) => ({
          name: p.name ?? p.nome,
          price: p.price ?? p.valor,
        }));
      } catch (e) {
        console.error(e);
      }
    }

    onMounted(load);

    return { products };
  },
});
</script>
