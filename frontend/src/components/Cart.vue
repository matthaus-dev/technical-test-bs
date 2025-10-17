<template>
  <div class="card">
    <h2 class="text-lg font-medium mb-3">Carrinho</h2>
    <div v-if="items.length === 0" class="muted">
      Nenhum produto adicionado.
    </div>

    <ul class="cart-items">
      <li v-for="(item, index) in items" :key="item.name" class="item-row">
        <div class="item-name" style="flex: 1">
          <div class="font-medium">{{ item.name }}</div>
          <div class="muted-small">R$ {{ item.price.toFixed(2) }}</div>
        </div>

        <div
          class="item-qty"
          style="display: flex; align-items: center; gap: 8px"
        >
          <button
            class="circle-btn"
            @click="decrement(index)"
            :aria-label="`Diminuir quantidade de ${item.name}`"
            :disabled="item.quantity <= 1"
          >
            -
          </button>
          <div>{{ item.quantity }}</div>
          <button
            class="circle-btn"
            @click="increment(index)"
            :aria-label="`Aumentar quantidade de ${item.name}`"
          >
            +
          </button>
        </div>

        <div style="width: 120px; text-align: right">
          <div class="item-subtotal">
            R$ {{ (item.price * item.quantity).toFixed(2) }}
          </div>
          <div>
            <button
              class="btn-danger"
              @click="remove(index)"
              :aria-label="`Remover ${item.name}`"
            >
              Remover
            </button>
          </div>
        </div>
      </li>
    </ul>

    <div v-if="items.length > 0">
      <div class="mt-4">
        <label class="muted block mb-1">Forma de pagamento</label>
        <select v-model="payment_method" >
          <option value="PIX">Pix (10% desconto)</option>
          <option value="CARTAO_CREDITO">Cartão de Crédito</option>
        </select>
      </div>

      <div v-if="payment_method === 'CARTAO_CREDITO'" class="mt-3">
        <label class="muted block mb-1">Quantidade de Parcelas</label>
        <select v-model.number="parcelas">
          <option v-for="n in 12" :key="n" :value="n">{{ n }}x</option>
        </select>
      </div>
      <div class="mt-4">
        <button
          class="btn w-full"
          @click="calculate"
          :disabled="items.length === 0"
        >
          Calcular Total
        </button>
      </div>
    </div>

    <div v-if="loading" class="mt-2 muted">Calculando...</div>

    <div v-if="result && items.length > 0" class="mt-4 border-t pt-3">
      <h3 class="font-medium">Resumo</h3>
      <div class="mt-2">
        Total Carrinho: <strong>R$ {{ result.principal.toFixed(2) }}</strong>
      </div>
      <div class="mt-3">Forma de Pagamento: {{ result.payment_method }}</div>
      <div class="mt-3" v-show="payment_method == 'CARTAO_CREDITO'">Parcelas: {{ result.installments }} x  R$ {{ result.installment_value.toFixed(2) }}</div>
      <div class="mt-3 text-lg">
        SubTotal : R$ {{ result.total_amount.toFixed(2) }}
      </div>      
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from "vue";
import api from "../services/api";

export default defineComponent({
  props: { items: { type: Array, required: true } },
  emits: ["change"],
  setup(props, { emit }) {
    const payment_method = ref("PIX");
    const parcelas = ref(1);
    const loading = ref(false);
    const result = ref<any | null>(null);

    watch(
      () => props.items,
      (newItems) => {
        console.log('Items changed:', newItems);
      }
    );

    function remove(index: number) {
      const updatedItems = [...props.items];
      updatedItems.splice(index, 1);
      emit("change", updatedItems);
    }

    function increment(index: number) {
      const copiedItems = props.items.map((originalItem: any) => ({ ...originalItem }));
      copiedItems[index].quantity = (copiedItems[index].quantity || 0) + 1;
      emit("change", copiedItems);
    }

    function decrement(index: number) {
      const copiedItems = props.items.map((originalItem: any) => ({ ...originalItem }));
      if ((copiedItems[index].quantity || 0) > 1) {
        copiedItems[index].quantity -= 1;
      }
      emit("change", copiedItems);
    }

    async function calculate() {
      loading.value = true;
      result.value = null;
      try {
        const payload = {
          products: props.items.map((product: any) => ({
            name: product.name,
            price: product.price,
            quantity: product.quantity,
          })),
          payment_method: payment_method.value,
          installments: parcelas.value,
        };
        const res = await api.post("/cart/total", payload);
        const data = res.data || {};
        result.value = {
          total_amount: data.total_amount,
          principal: data.principal,
          payment_method: data.payment_method,
          installments: data.installments,
          installment_value: data.installment_value,
          details: data.details,
        };
      } catch (e) {
        console.error(e);
      } finally {
        loading.value = false;
      }
    }

    return {
      payment_method,
      parcelas,
      remove,
      increment,
      decrement,
      calculate,
      loading,
      result,
    };
  },
});
</script>
