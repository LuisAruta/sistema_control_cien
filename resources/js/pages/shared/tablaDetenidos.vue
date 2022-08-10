<template>
  <b-card class="text-center" header="Detenidos" header-bg-variant="primary"
          header-text-variant="secondary"
  >
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="fullPage"
    />
    <b-container class="mb-3">
      <b-row class="margen-detenidos" align-v="center">
        <b-col sm="2">
          Nombre
        </b-col>
        <b-col sm="2">
          Apellido
        </b-col>
        <b-col sm="2">
          Tipo Doc
        </b-col>
        <b-col sm="2">
          Número
        </b-col>
        <b-col sm="3">
          Fecha Nac
        </b-col>
        <b-col sm="1"/>
      </b-row>
      <span v-for="(detenido, index) in arrayDetenidos" :key="detenido.id">
        <b-row class="margen-detenidos">
          <b-col sm="2">
            <b-form-input v-model="detenido.nombre" :class="{'is-invalid': $v.arrayDetenidos.$each[index].nombre.$error}" placeholder="Nombre" @change="changeDatos" />
            <b-form-invalid-feedback id="input-cantidad-feedback">
              Máx: 30 caracteres
            </b-form-invalid-feedback>
          </b-col>
          <b-col sm="2">
            <b-form-input v-model="detenido.apellido" :class="{'is-invalid': $v.arrayDetenidos.$each[index].apellido.$error}" placeholder="Apellido" @change="changeDatos" />
            <b-form-invalid-feedback id="input-cantidad-feedback">
              Máx: 30 caracteres
            </b-form-invalid-feedback>
          </b-col>
          <b-col sm="2">
            <b-form-select v-model="detenido.documento.tipo_documento" :options="tipos_documentos"
                           @change="changeDatos"
            />
          </b-col>
          <b-col sm="2">
            <b-form-input v-model="detenido.documento.numero" :class="{'is-invalid': $v.arrayDetenidos.$each[index].documento.numero.$error}" @change="changeDatos" />
            <b-form-invalid-feedback id="input-cantidad-feedback">
              Sólo números (máx: 11 números)
            </b-form-invalid-feedback>
          </b-col>
          <b-col sm="3">
            <b-form-datepicker v-model="detenido.fecha_nacimiento"
                               :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                               :max="max"
                               :min="min"
                               locale="es"
                               :show-decade-nav="true"
            />
          </b-col>
          <b-col sm="1">
            <b-button
              v-b-tooltip.hover="'Borrar'"
              size="sm"
              variant="danger"
              @click="arrayDetenidos.splice(index, 1)"
            >
              <b-icon aria-hidden="true" icon="trash" />
            </b-button></b-col>
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
              @click="arrayDetenidos.push({id: null, nombre: '', apellido: '', documento: { id: null, tipo_documento: 'DNI', numero: null }, fecha_nacimiento: fecha_actual })"
            >
              <b-icon icon="plus-circle-fill" aria-hidden="true" />
              Agregar detenido
            </b-button>
          </label>
        </b-col>
      </b-row>
    </b-container>
  </b-card>
</template>

<script>
import { maxLength, required, minLength, numeric } from 'vuelidate/lib/validators'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import 'vue-select/dist/vue-select.css'

export default {
  // eslint-disable-next-line vue/component-definition-name-casing
  name: 'tablaDetenidos',
  components: {
    Loading
  },
  props: {
    detenidos: {
      type: Array,
      default: () => ([])
    }
  },
  validations () {
    return {
      arrayDetenidos: {
        $each: {
          nombre: { required, maxLength: maxLength(30) },
          apellido: { required, maxLength: maxLength(30) },
          documento: {
            tipo_documento: { },
            numero: {
              required,
              numeric,
              minLength: minLength(7),
              maxLength: maxLength(11)
            }
          },
          fecha_nacimiento: { }
        }
      }
    }
  },
  data () {
    return {
      min: null,
      max: null,
      fecha_actual: null,
      tipos_documentos: [
        {
          value: 'DNI',
          text: 'DNI'
        },
        {
          value: 'LC',
          text: 'LC'
        },
        {
          value: 'LE',
          text: 'LE'
        },
        {
          value: 'EXT',
          text: 'EXT'
        },
        {
          value: 'Pasaporte',
          text: 'Pasaporte'
        }
      ],
      fields: [
        {
          key: 'nombre',
          label: 'Nombre',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'apellido',
          label: 'Apellido',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'documento.tipo_documento',
          label: 'Tipo',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'documento.numero',
          label: 'Numero',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'fecha_nacimiento',
          label: 'Fecha Nac',
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
      arrayDetenidos: []
    }
  },
  watch: {
    detenidos: function (newVal) {
      this.arrayDetenidos = newVal
    }
  },
  mounted () {
    this.arrayDetenidos = this.detenidos
    const hoy = new Date()
    this.fecha_actual = hoy.getFullYear() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getDate()
    this.max = this.fecha_actual
  },
  methods: {
    changeDatos () {
      // eslint-disable-next-line vue/custom-event-name-casing
      this.$emit('changeDetenidosTomados', {
        detenidos: this.arrayDetenidos
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

.margen-detenidos{
  margin-bottom: 10px;
}

</style>
