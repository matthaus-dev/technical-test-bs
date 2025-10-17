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
        <button v-if="currentView === 'products'" class="btn" @click="currentView = 'cart'">
          Ver Carrinho ({{ totalItemsInCart }})
        </button>
        <button v-else class="btn" @click="currentView = 'products'">
          Voltar aos Produtos
        </button>
      </div>
    </header>

    <main>
      <section
        v-if="currentView === 'products'"
        style="max-width: 1100px; margin: 0 auto; padding: 16px"
      >
        <ProductList @add="handleAddToCart" />
      </section>

      <section v-else style="max-width: 820px; margin: 0 auto; padding: 16px">
        <Cart :items="cartItems" @change="handleCartItemsChange" />
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
    const currentView = ref<"products" | "cart">("products");

    const totalItemsInCart = computed(() =>
      cartItems.value.reduce((sum: number, item: { quantity?: number }) => sum + (item.quantity ?? 0), 0)
    );

    function handleAddToCart(product: any) {
      const productName = product.name;
      let existingCartItem = cartItems.value.find((ci: { name: string }) => ci.name === productName);

      if (existingCartItem) {
        existingCartItem.quantity = (existingCartItem.quantity ?? 0) + 1;
      } else {
        cartItems.value.push({
          name: product.name,
          price: product.price,
          quantity: 1,
        });
      }
    }

    function handleCartItemsChange(updatedItems: Array<any>) {
      cartItems.value = updatedItems;
    }

    return {
      cartItems,
      currentView,
      totalItemsInCart,
      handleAddToCart,
      handleCartItemsChange,
    };
  },
});
</script>

<style>
.text-2xl {
  font-size: 1.5rem;
}
.font-semibold {
  font-weight: 600;
}
</style>
