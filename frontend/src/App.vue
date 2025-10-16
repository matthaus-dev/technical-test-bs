<template>
  <div>
    <header
      style="
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1100px;
        margin: 12px auto 8px;
        padding: 0 16px;
      "
    >
      <h1 class="text-2xl font-semibold">Carrinho de Compras</h1>
      <div>
        <button v-if="view === 'products'" class="btn" @click="view = 'cart'">
          Ver Carrinho ({{ cartCount }})
        </button>
        <button v-else class="btn" @click="view = 'products'">
          Voltar aos Produtos
        </button>
      </div>
    </header>

    <main>
      <section
        v-if="view === 'products'"
        style="max-width: 1100px; margin: 0 auto; padding: 16px"
      >
        <ProductList @add="onAdd" />
      </section>

      <section v-else style="max-width: 820px; margin: 0 auto; padding: 16px">
        <Cart :items="cartItems" @change="onChange" />
      </section>
    </main>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed } from "vue";
import ProductList from "./components/ProductList.vue";
import Cart from "./components/Cart.vue";

export default defineComponent({
  components: { ProductList, Cart },
  setup() {
    const cartItems = ref<Array<any>>([]);
    const view = ref<"products" | "cart">("products");

    const cartCount = computed(() =>
      cartItems.value.reduce((s, i) => s + (i.quantity ?? 0), 0)
    );

    function onAdd(product: any) {
      const name = product.name;
      let existing = cartItems.value.find((i) => i.name === name);
      if (existing) {
        existing.quantity = (existing.quantity ?? 0) + 1;
      } else {
        cartItems.value.push({
          name: product.name,
          price: product.price,
          quantity: 1,
        });
      }
    }

    function onChange(items: Array<any>) {
      cartItems.value = items;
    }

    return { cartItems, onAdd, onChange, view, cartCount };
  },
});
</script>

<style>
/* small local styles (kept minimal - the main styles are in src/styles.css) */
.text-2xl {
  font-size: 1.5rem;
}
.font-semibold {
  font-weight: 600;
}
</style>
