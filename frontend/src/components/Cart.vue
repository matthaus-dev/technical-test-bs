<template>
  <div class="card">
    <h2 class="text-lg font-medium mb-3">Carrinho</h2>
    <div v-if="items.length === 0" class="muted">
      Nenhum produto adicionado.
    </div>

    <ul class="cart-items">
      <li v-for="(it, idx) in items" :key="it.name" class="item-row">
        <div class="item-name" style="flex: 1">
          <div class="font-medium">{{ it.name }}</div>
          <div class="muted-small">R$ {{ it.price.toFixed(2) }}</div>
        </div>

        <div
          class="item-qty"
          style="display: flex; align-items: center; gap: 8px"
        >
          <button
            class="circle-btn"
            @click="decrement(idx)"
            :aria-label="`Diminuir quantidade de ${it.name}`"
            :disabled="it.quantity <= 1"
          >
            -
          </button>
          <div>{{ it.quantity }}</div>
          <button
            class="circle-btn"
            @click="increment(idx)"
            :aria-label="`Aumentar quantidade de ${it.name}`"
          >
            +
          </button>
        </div>

        <div style="width: 120px; text-align: right">
          <div class="item-subtotal">
            R$ {{ (it.price * it.quantity).toFixed(2) }}
          </div>
          <div>
            <button
              class="btn-danger"
              @click="remove(idx)"
              :aria-label="`Remover ${it.name}`"
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
        <select v-model="metodo" class="w-full border rounded px-2 py-1">
          <option value="PIX">Pix (10% desconto)</option>
          <option value="CARTAO_CREDITO">Cartão de Crédito</option>
        </select>
      </div>

      <div v-if="metodo === 'CARTAO_CREDITO'" class="mt-3">
        <label class="muted block mb-1">Parcelas</label>
        <select
          v-model.number="parcelas"
          class="w-full border rounded px-2 py-1"
        >
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

    <div v-if="result" class="mt-4 border-t pt-3">
      <h3 class="font-medium">Resumo</h3>
      <div class="mt-2">
        Principal: <strong>R$ {{ result.principal.toFixed(2) }}</strong>
      </div>
      <div>Metodo: {{ result.payment_method }}</div>
      <div>Installments: {{ result.installments }}</div>
      <div class="text-xl font-semibold mt-2">
        Total Amount: R$ {{ result.total_amount.toFixed(2) }}
      </div>
      <pre class="muted mt-2">{{ result.details }}</pre>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed, ref, watch } from "vue";
import api from "../services/api";

export default defineComponent({
  props: { items: { type: Array, required: true } },
  emits: ["change"],
  setup(props, { emit }) {
    const metodo = ref("PIX");
    const parcelas = ref(1);
    const loading = ref(false);
    const result = ref<any | null>(null);

    watch(
      () => props.items,
      (v) => {
        // noop for now
      }
    );

    function remove(idx: number) {
      const copy = [...props.items];
      copy.splice(idx, 1);
      emit("change", copy);
    }

    function increment(idx: number) {
      const copy = props.items.map((x: any) => ({ ...x }));
      copy[idx].quantidade = (copy[idx].quantidade || 0) + 1;
      emit("change", copy);
    }

    function decrement(idx: number) {
      const copy = props.items.map((x: any) => ({ ...x }));
      if ((copy[idx].quantidade || 0) > 1) copy[idx].quantidade -= 1;
      emit("change", copy);
    }

    async function calculate() {
      loading.value = true;
      result.value = null;
      try {
        const payload = {
          products: props.items.map((i: any) => ({
            name: i.name,
            price: i.price,
            quantity: i.quantity,
          })),
          payment_method: metodo.value,
          installments: parcelas.value,
        };
        const res = await api.post("/cart/total", payload);
        const data = res.data || {};
        result.value = {
          total_amount: data.total_amount,
          principal: data.principal,
          payment_method: data.payment_method,
          installments: data.installments,
          details: data.details,
        };
      } catch (e) {
        console.error(e);
      } finally {
        loading.value = false;
      }
    }

    return {
      metodo,
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
