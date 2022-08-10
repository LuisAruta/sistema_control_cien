<template>
  <b-card class="text-center" header="Oficios" header-bg-variant="primary"
          header-text-variant="secondary"
  >
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="fullPage"
    />
    <detalle-oficio-modal :row="selectedOficio" @cambio-detalle-oficio="changeDatos($event)" />
    <b-container class="mb-3">
      <b-table responsive="lg" striped hover :items="arrayOficios" :fields="fields">
        <template #cell(acciones)="row">
          <b-button
            v-if="permissions.includes('editar_oficio')"
            v-b-tooltip.hover="'Editar Oficio'"
            size="sm"
            type="button"
            variant="primary"
            data-toggle="modal"
            @click="abrirModalOficio(row.item)"
          >
            <b-icon aria-hidden="true" icon="pencil" />
          </b-button>
          <b-button
            v-if="permissions.includes('eliminar_oficio')"
            v-b-tooltip.hover="'Borrar'"
            size="sm"
            variant="danger"
            @click="eliminarOficio($event, row.item)"
          >
            <b-icon aria-hidden="true" icon="trash" />
          </b-button>
        </template>
      </b-table>
    </b-container>
  </b-card>
</template>

<script>
import Loading from 'vue-loading-overlay'
import axios from 'axios'

import 'vue-loading-overlay/dist/vue-loading.css'
import 'vue-select/dist/vue-select.css'
import detalleOficioModal from '../oficio/detalleOficioModal'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2'

export default {
  // eslint-disable-next-line vue/component-definition-name-casing
  name: 'tablaOficios',
  components: {
    Loading,
    detalleOficioModal
  },
  middleware: 'auth',
  props: {
    oficios: {
      type: Array,
      default: () => ([])
    }
  },
  validations: {
    oficios: {}
  },
  data () {
    return {
      selectedOficio: null,
      fields: [
        {
          key: 'fecha_ingreso',
          label: 'Fecha Ingreso',
          class: 'text-center',
          formatter: value => {
            const fecha = value.split(' ')[0].split('-')
            return fecha[2] + '-' + fecha[1] + '-' + fecha[0]
          }
        },
        {
          key: 'instancia_solicitante.nombre',
          label: 'Instancia Solicitante',
          class: 'text-center'
        },
        {
          key: 'estado_oficio.nombre',
          label: 'Estado',
          class: 'text-center'
        },
        {
          key: 'acciones',
          label: 'Acciones',
          sortable: false,
          class: 'text-center'
        }
      ],
      isLoading: false,
      fullPage: true,
      arrayOficios: []
    }
  },
  computed: {
    ...mapGetters({
      permissions: 'auth/permissions',
      user: 'auth/user'
    })
  },
  watch: {
    oficios: function (newVal) {
      this.arrayOficios = newVal
    }
  },
  mounted () {
    this.arrayOficios = this.oficios
  },
  methods: {
    changeDatos ($event) {
      // eslint-disable-next-line vue/custom-event-name-casing
      this.$emit('changeOficios')
    },
    abrirModalOficio (data) {
      this.selectedOficio = data
      this.selectedOficio.tipo = data.oficiable_type.split('\\')[2]
      this.$bvModal.show('detalleOficioModal')
    },
    eliminarOficio ($event, row) {
      Swal.fire({
        title: '¿Desea eliminar el Oficio?',
        text: 'Eliminar un Oficio es permanente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#F5365C',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          this.mostrarSpinner(true)
          axios.delete('api/oficio/' + row.id)
            .then(response => {
              this.$bvToast.toast('El Oficio fue borrado correctamente', {
                title: 'Exito',
                autoHideDelay: 3000
              })
              // eslint-disable-next-line vue/custom-event-name-casing
              this.$emit('changeOficios')
              this.mostrarSpinner(false)
            }).catch(error => {
              this.mostrarSpinner(false)
              this.mostrarErrores(error.response.data.errors)
            })
        }
      })
    },
    mostrarErrores (errores) {
      let mensajeError = ''
      for (var prop in errores) {
        if (mensajeError === '') {
          mensajeError = errores[prop]
        } else {
          mensajeError = mensajeError + '\n' + errores[prop]
        }
      }

      Swal.fire({
        title: 'Error',
        text: mensajeError,
        icon: 'error',
        showCancelButton: false,
        confirmButtonColor: '#F5365C',
        confirmButtonText: 'Confirmar'
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

.margen-oficios{
  margin-bottom: 10px;
}
</style>
