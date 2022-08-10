<template>
  <b-card class="text-center" header="Informes Laboratorio" header-bg-variant="primary"
          header-text-variant="secondary"
  >
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="fullPage"
    />
    <b-container class="mb-3">
      <b-row class="margen-informes-laboratorio" align-v="center">
        <b-col sm="5">Tipo Informe</b-col>
        <b-col sm="5">Número Informe</b-col>
        <b-col sm="2">Acciones</b-col>
      </b-row>
      <span v-for="(informe, index) in arrayInformesLaboratorio" :key="informe.id">
        <b-row class="margen-informes-laboratorio">
          <b-col sm="5">
            <b-form-select v-model="informe.tipo_informes_laboratorio_id" :options="tipos_informes_laboratorio" :reduce="tipo_informes_laboratorio => tipo_informes_laboratorio.id"
                           text-field="nombre" value-field="id"
                           label="nombre" placeholder="Seleccione un tipo de informes de laboratorio" @change="changeDatos"
            />
          </b-col>
          <b-col sm="5">
            <b-form-input :class="{'is-invalid': $v.arrayInformesLaboratorio.$each[index].numero_informe.$error}" v-model="informe.numero_informe" placeholder="Ingrese Número de Informe" @change="changeDatos"></b-form-input>
            <b-form-invalid-feedback id="input-numero_informe-feedback">
              Máximo 30 caracteres
            </b-form-invalid-feedback>
          </b-col>
          <b-col sm="2">
            <b-button
              v-b-tooltip.hover="'Borrar'"
              size="sm"
              variant="danger"
              @click="arrayInformesLaboratorio.splice(index, 1)"
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
              @click="arrayInformesLaboratorio.push({id: null, tipo_informes_laboratorio_id: 1, numero_informe: ''})"
            >
              <b-icon icon="plus-circle-fill" aria-hidden="true"/>
              Agregar Informe
            </b-button>
          </label>
        </b-col>
      </b-row>
    </b-container>
  </b-card>
</template>

<script>
import { maxLength, required } from 'vuelidate/lib/validators'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import 'vue-select/dist/vue-select.css'

export default {
  name: 'tablaInformesLaboratorio',
  components: {
    Loading
  },
  props: {
    informes_laboratorio: {
      type: Array,
      default: () => ([])
    },
    tipos_informes_laboratorio: {
      type: Array,
      default: () => ([])
    }
  },
  validations () {
    return {
      arrayInformesLaboratorio: {
        $each: {
          tipo_informes_laboratorio_id: { required },
          numero_informe: { required, maxLength: maxLength(30) }
        }
      }
    }
  },
  data () {
    return {
      fields: [
        {
          key: 'tipo_informes_laboratorio_id',
          label: 'Tipo Indicio',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'numero_informe',
          label: 'Numero Informe',
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
      arrayInformesLaboratorio: []
    }
  },
  mounted () {
    this.arrayInformesLaboratorio = this.informes_laboratorio
  },
  watch: {
    informes_laboratorio: function (newVal) {
      this.arrayInformesLaboratorio = newVal
    }
  },
  methods: {
    changeDatos () {
      // eslint-disable-next-line vue/custom-event-name-casing
      this.$emit('changeInformesLaboratorioTomados', {
        informes_laboratorio: this.arrayInformesLaboratorio
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

.margen-informes-laboratorio{
  margin-bottom: 10px;
}

</style>
