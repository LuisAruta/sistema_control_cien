<template>
  <b-card class="text-center" header="Indicios" header-bg-variant="primary"
          header-text-variant="secondary"
  >
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="fullPage"
    />
    <b-container class="mb-3">
      <b-row class="margen-indicios" align-v="center">
        <b-col sm="4">Tipo Indicio</b-col>
        <b-col sm="2">Cantidad</b-col>
        <b-col sm="4">Detalle</b-col>
        <b-col sm="2">Acciones</b-col>
      </b-row>
      <span v-for="(indicio, index) in arrayIndicios" :key="indicio.id">
        <b-row class="margen-indicios">
          <b-col sm="4">
            <b-form-select v-model="indicio.tipo_indicio_id" :options="tipos_indicios" :reduce="tipo_indicio => tipo_indicio.id"
                           text-field="nombre" value-field="id"
                           label="nombre" placeholder="Seleccione un tipo de indicio" @change="changeDatos"
            />
          </b-col>
          <b-col sm="2">
            <b-form-input :class="{'is-invalid': $v.arrayIndicios.$each[index].cantidad.$error}" v-model="indicio.cantidad" placeholder="Ingrese cantidad" @change="changeDatos"></b-form-input>
            <b-form-invalid-feedback id="input-cantidad-feedback">
              Sólo números (máx: 10 caracteres)
            </b-form-invalid-feedback>
          </b-col>
          <b-col sm="4">
            <b-form-input :class="{'is-invalid': $v.arrayIndicios.$each[index].detalle.$error}" v-model="indicio.detalle" placeholder="Ingrese detalle" @change="changeDatos"></b-form-input>
            <b-form-invalid-feedback id="input-detalle-feedback">
              El máximo de caracteres es 80
            </b-form-invalid-feedback>
          </b-col>
          <b-col sm="2">
            <b-button
              v-b-tooltip.hover="'Borrar'"
              size="sm"
              variant="danger"
              @click="arrayIndicios.splice(index, 1)"
            >
              <b-icon aria-hidden="true" icon="trash" />
            </b-button>
          </b-col>
        </b-row>
      </span>
      <b-row align-v="center" align-h="center">
        <b-col sm="2">
          <label>
            <b-button
              v-b-tooltip.hover="'Agregar nuevo'"
              size="sm"
              type="button"
              variant="primary"
              @click="arrayIndicios.push({id: null, tipo_indicio_id: 1, cantidad: 1, detalle: ''})"
            >
              <b-icon icon="plus-circle-fill" aria-hidden="true"/>
              Agregar Indicio
            </b-button>
          </label>
        </b-col>
      </b-row>
    </b-container>
  </b-card>
</template>

<script>
import { maxLength, required, numeric } from 'vuelidate/lib/validators'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import 'vue-select/dist/vue-select.css'

export default {
  // eslint-disable-next-line vue/component-definition-name-casing
  name: 'tablaIndicios',
  components: {
    Loading
  },
  props: {
    indicios: {
      type: Array,
      default: () => ([])
    },
    tipos_indicios: {
      type: Array,
      default: () => ([])
    }
  },
  validations () {
    return {
      arrayIndicios: {
        $each: {
          tipo_indicio_id: { required },
          cantidad: { required, numeric, maxLength: maxLength(10) },
          detalle: { maxLength: maxLength(80) }
        }
      }
    }
  },
  data () {
    return {
      fields: [
        {
          key: 'tipo_indicio_id',
          label: 'Tipo Indicio',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'cantidad',
          label: 'Cantidad',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'detalle',
          label: 'Detalle',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'acciones',
          label: 'Acciones',
          sortable: false,
          class: 'text-center'
        }
      ],
      isLoading: false,
      fullPage: true,
      arrayIndicios: []
    }
  },
  mounted () {
    this.arrayIndicios = this.indicios
  },
  watch: {
    indicios: function (newVal) {
      this.arrayIndicios = newVal
    }
  },
  methods: {
    changeDatos () {
      // eslint-disable-next-line vue/custom-event-name-casing
      this.$emit('changeIndiciosTomados', {
        indicios: this.arrayIndicios
      })
    },
    mostrarSpinner (boolean) {
      if (boolean) {
        this.isLoading = true
        setTimeout(() => {
          this.isLoading = false
        }, 5000)
      } else {
        this.isLoading = false
      }
    }
  }
}
</script>

<style scoped>

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

.margen-indicios{
  margin-bottom: 10px;
}

</style>
