<template>
  <b-container fluid style="padding-left: 0px !important; padding-right: 0px !important; overflow-x: hidden;">
    <dependencia-modal :row="selectedRow" @cambios-array-dependencias="getData()" />
    <b-card style="background-color: transparent;">
      <b-row>
        <b-col class="my-1" lg="5">
          <b-form-group
            class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-input
                id="filter-input"
                v-model="campoBusqueda"
                placeholder="Escriba para buscar"
                type="search"
                v-on:keyup.enter="getData"
              />
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col class="my-1" lg="5">
          <b-button
            class="mb-0"
            size="sm"
            style="margin-bottom: 10px;"
            type="button"
            variant="info"
            @click="getData"
          >
            Buscar
          </b-button>
          <b-button v-b-toggle.collapse-filtros size="sm" variant="primary">
            Busqueda Avanzada
          </b-button>
        </b-col>
        <b-col lg="2" style="text-align: end;">
          <b-button
            v-if="permissions.includes('crear_dependencia')"
            size="sm"
            type="button"
            variant="primary"
            @click="abrirModal(null)"
          >
            Nueva Dependencia
          </b-button>
        </b-col>
      </b-row>
      <b-collapse id="collapse-filtros" class="mt-2" size="sm">
        <b-card
          border-variant="primary"
          header="Parametros de busqueda"
          header-bg-variant="primary"
          header-text-variant="white"
          align="center"
        >
          <b-card-text>
            <b-row align-v="center">
              <b-col sm="2">
                <label size="sm">Nombre: </label>
              </b-col>
              <b-col sm="4">
                <b-form-input v-model="filtros.nombre" placeholder="Ingrese Nombre" size="sm"/>
              </b-col>
              <b-col sm="2">
                <label size="sm">Grupo: </label>
              </b-col>
              <b-col sm="4">
                <b-form-input v-model="filtros.grupo" placeholder="Ingrese Grupo" size="sm"/>
              </b-col>
            </b-row>
          </b-card-text>
          <b-card-text>
            <b-row align-v="center">
              <b-col sm="2">
                <label size="sm">Cientifica: </label>
              </b-col>
              <b-col sm="4">
                <b-form-select v-model="filtros.cientifica" :options="opcionesCientifica" size="sm"/>
              </b-col>
            </b-row>
          </b-card-text>
          <template #footer>
            <b-button size="sm" variant="danger" @click="limpiarFiltros">
              Limpiar
            </b-button>
            <b-button
              v-b-toggle.collapse-filtros
              class="mb-0"
              size="sm"
              style="margin-bottom: 10px;"
              type="button"
              variant="primary"
              @click="getData()"
            >
              Buscar
            </b-button>
          </template>
        </b-card>
      </b-collapse>
    </b-card>
    <b-table id="dependenciatabla"
             responsive="sm"
             :fields="fields"
             :per-page="perPage"
             :current-page="currentPage"
             :items="items" :total-rows="rows" head-variant="dark" primary-key="id"
    >
      <template #cell(acciones)="row">
        <b-button
          v-if="permissions.includes('editar_dependencia')"
          v-b-tooltip.hover="'Editar'"
          size="sm"
          type="button"
          variant="primary"
          data-toggle="modal"
          @click="abrirModal(row.item)"
        >
          <b-icon aria-hidden="true" icon="pencil" />
        </b-button>
        <b-button
          v-if="permissions.includes('eliminar_dependencia')"
          v-b-tooltip.hover="'Borrar'"
          size="sm"
          variant="danger"
          @click="eliminarRow($event, row.item)"
        >
          <b-icon aria-hidden="true" icon="trash" />
        </b-button>
      </template>
    </b-table>
    <b-pagination
      v-model="currentPage"
      :total-rows="rows"
      :per-page="10"
      aria-controls="dependenciatabla"
      @change="getData($event)"
    />
  </b-container>
</template>

<script>

import axios from 'axios'
import { mapGetters } from 'vuex'
import dependenciaModal from './dependenciaModal'
import Swal from 'sweetalert2'

export default {
  name: 'DependenciasVue',
  components: { dependenciaModal },
  middleware: 'auth',
  data () {
    return {
      isLoading: false,
      url: 'api/dependencia/search',
      total: 0,
      modal: dependenciaModal,
      fields: [
        {
          key: 'nombre',
          label: 'Dependencia',
          sortable: true,
          class: 'text-center'
        },
        {
          key: 'grupo',
          label: 'Grupo',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'acciones',
          label: 'Acciones',
          sortable: false,
          class: 'text-center'
        }
      ],
      items: [],
      selectedRow: {
        id: '',
        nombre: '',
        grupo: '',
        cientifica: false
      },
      totalRows: 1,
      currentPage: 1,
      perPage: 5,
      pageOptions: [5, 10, 15],
      sortBy: '',
      sortDesc: false,
      sortDirection: 'asc',
      opcionesCientifica: [
        { value: null, text: 'Seleccione si es de cientifica' },
        { value: 'NO', text: 'NO' },
        { value: 'SI', text: 'SI' }
      ],
      campoBusqueda: null,
      filtros: {
        nombre: null,
        grupo: null,
        cientifica: null
      }
    }
  },
  computed: {
    rows () {
      return this.total
    },
    sortOptions () {
      // Create an options list from our fields
      return this.fields
        .filter(f => f.sortable)
        .map(f => {
          return { text: f.label, value: f.key }
        })
    },
    ...mapGetters({
      permissions: 'auth/permissions'
    })
  },
  created () {
    this.getData()
  },
  methods: {
    abrirModal (data) {
      if (data) {
        this.selectedRow = data
      } else {
        this.selectedRow = {}
      }
      this.$bvModal.show(this.modal.name)
    },
    limpiarFiltros () {
      this.filtros.nombre = null
      this.filtros.grupo = null
      this.filtros.cientifica = null
    },
    getData (event = 1) {
      const page = typeof event === 'number' ? event : 1
      this.mostrarSpinner(true)
      const url = this.url + '?page=' + page
      let data = null
      if (this.filtros.nombre || this.filtros.grupo || this.filtros.cientifica) {
        data = { filter: this.campoBusqueda, filtros: this.filtros }
      } else {
        data = { filter: this.campoBusqueda, filtros: null }
      }
      axios.post(url, data)
        .then(response => {
          this.items = response.data.data
          this.perPage = response.data.per_page
          this.currentPage = response.data.meta.current_page
          this.total = response.data.meta.total
          this.mostrarSpinner(false)
        }).catch(error => {
          this.mostrarSpinner(false)
          this.mostrarErrores(error.response.data.errors)
          this.items = []
        })
    },
    eliminarRow (event, row) {
      Swal.fire({
        title: '¿Desea eliminar la dependencia?',
        text: 'Eliminar una dependencia es permanente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#F5365C',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        this.mostrarSpinner(true)
        axios.delete('api/dependencia/' + row.id)
          .then(response => {
            this.$bvToast.toast('La dependencia fue borrada correctamente', {
              title: 'Exito',
              autoHideDelay: 3000
            })
            this.getData()
            this.mostrarSpinner(false)
          }).catch(error => {
            this.mostrarSpinner(false)
            this.mostrarErrores(error.response.data.errors)
          })
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
        }, 2000)
      } else {
        this.isLoading = false
      }
    },
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    }
  }
}
</script>

<style scoped>

</style>
