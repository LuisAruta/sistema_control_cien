<template>
  <b-container fluid style="padding-left: 0px !important; padding-right: 0px !important; overflow-x: hidden;">
    <usuario-modal :row="selectedRow" @cambios-array-usuarios="getData()" />
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
            v-if="permissions.includes('crear_usuario')"
            size="sm"
            type="button"
            variant="primary"
            @click="abrirModal(null)"
          >
            Nuevo Usuario
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
                <b-form-input v-model="filtros.nombre_usuario" placeholder="Ingrese Nombre" size="sm"/>
              </b-col>
              <b-col sm="2">
                <label size="sm">Email: </label>
              </b-col>
              <b-col sm="4">
                <b-form-input v-model="filtros.email" placeholder="Ingrese Email" size="sm"/>
              </b-col>
            </b-row>
          </b-card-text>
          <b-card-text>
            <b-row align-v="center">
              <b-col sm="2">
                <label size="sm">Nombre Completo: </label>
              </b-col>
              <b-col sm="4">
                <b-form-input v-model="filtros.nombre_completo_usuario" placeholder="Ingrese Nombre Completo" size="sm"/>
              </b-col>
              <b-col sm="2">
                <label size="sm">DNI: </label>
              </b-col>
              <b-col sm="4">
                <b-form-input v-model="filtros.dni_usuario" placeholder="Ingrese DNI" size="sm"/>
              </b-col>
            </b-row>
          </b-card-text>
          <b-card-text>
            <b-row align-v="center">
              <b-col sm="2">
                <label size="sm">Dependencia: </label>
              </b-col>
              <b-col sm="4">
                <b-form-select v-model="filtros.dependencia" size="sm" :options="dependencias" value-field="id"
                               text-field="nombre"
                >
                  <template #first>
                    <b-form-select-option :value="null">
                      Seleccione una Dependencia
                    </b-form-select-option>
                  </template>
                </b-form-select>
              </b-col>
              <b-col sm="2">
                <label size="sm">Perito: </label>
              </b-col>
              <b-col>
                <b-form-select v-model="filtros.perito" :options="opcionesPerito" size="sm"/>
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
    <b-table id="usuariostabla"
             responsive="sm"
             :fields="fields"
             :per-page="perPage"
             :current-page="currentPage"
             :items="items" :total-rows="rows" head-variant="dark" primary-key="id"
    >
      <template #cell(acciones)="row">
        <b-button v-if="permissions.includes('consultar_usuario')" v-b-tooltip.hover="'Ver Detalles'" size="sm"
                  variant="info" @click="row.toggleDetails"
        >
          <b-icon aria-hidden="true" icon="search" />
        </b-button>
        <b-button
          v-if="permissions.includes('editar_usuario')"
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
          v-if="permissions.includes('editar_usuario')"
          v-b-tooltip.hover="'Blanquear contraseña'"
          size="sm"
          type="button"
          variant="warning"
          @click="blanquearClave($event, row.item)"
        >
          <b-icon aria-hidden="true" icon="gear" />
        </b-button>
        <b-button
          v-if="permissions.includes('eliminar_usuario')"
          v-b-tooltip.hover="'Borrar'"
          size="sm"
          variant="danger"
          @click="eliminarRow($event, row.item)"
        >
          <b-icon aria-hidden="true" icon="trash" />
        </b-button>
      </template>
      <template #row-details="row">
        <b-card class="text-center" header="Datos Generales" header-bg-variant="primary"
                header-text-variant="secondary"
        >
          <b-row>
            <b-col>
              <b>Dependencia:</b> {{
                row.item.dependencia && row.item.dependencia.nombre ? row.item.dependencia.nombre : '-'
              }}
            </b-col>
            <b-col>
              <b>Grupo:</b>
              {{ row.item.dependencia && row.item.dependencia.grupo ? row.item.dependencia.grupo : '-' }}
            </b-col>
            <b-col>
              <b>Email:</b> {{
                row.item.email ? row.item.email: '-'
              }}
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b>Roles: </b>
              {{ mapeoRolesArray(row.item.roles) }}
            </b-col>
          </b-row>
        </b-card>
      </template>
    </b-table>
    <b-pagination
      v-model="currentPage"
      :total-rows="rows"
      :per-page="10"
      aria-controls="usuariostabla"
      @change="getData($event)"
    />
  </b-container>
</template>

<script>

import axios from 'axios'
import { mapGetters } from 'vuex'
import usuarioModal from './usuarioModal'
import Swal from 'sweetalert2'

export default {
  name: 'UsuariosVue',
  components: { usuarioModal },
  middleware: 'auth',
  data () {
    return {
      isLoading: false,
      url: 'api/user/search',
      total: 0,
      modal: usuarioModal,
      fields: [
        {
          key: 'id',
          label: 'ID',
          sortable: true,
          class: 'text-center'
        },
        {
          key: 'nombre_completo',
          label: 'Nombre Completo',
          sortable: true,
          class: 'text-center'
        },
        {
          key: 'documento.numero',
          label: 'Documento',
          sortable: true,
          class: 'text-center'
        },
        {
          key: 'roles',
          label: 'Roles',
          sortable: true,
          class: 'text-center',
          formatter: value => this.transformarRolesArray(value)
        },
        {
          key: 'perito',
          label: 'Perito',
          sortable: true,
          class: 'text-center',
          formatter: value => {
            return value ? 'SI' : 'NO'
          }
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
        name: '',
        email: '',
        roles: '',
        dependencia: {
          id: ''
        },
        documento: {
          id: '',
          tipo_documento: 'DNI',
          numero: ''
        },
        nombre_completo: '',
        perito: false
      },
      totalRows: 1,
      currentPage: 1,
      perPage: 5,
      pageOptions: [5, 10, 15],
      sortBy: '',
      sortDesc: false,
      sortDirection: 'asc',
      opcionesPerito: [
        { value: null, text: 'Seleccione si es perito' },
        { value: 'NO', text: 'NO' },
        { value: 'SI', text: 'SI' }
      ],
      campoBusqueda: null,
      filtros: {
        nombre_usuario: null,
        email: null,
        nombre_completo_usuario: null,
        dni_usuario: null,
        dependencia: null,
        perito: null
      },
      dependencias: []
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
    this.getDependencias()
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
      this.filtros.nombre_usuario = null
      this.filtros.email = null
      this.filtros.nombre_completo_usuario = null
      this.filtros.dni_usuario = null
      this.filtros.dependencia = null
      this.filtros.perito = null
    },
    getData (event = 1) {
      const page = typeof event === 'number' ? event : 1
      this.mostrarSpinner(true)
      const url = this.url + '?page=' + page
      let data = null
      if (this.filtros.nombre_usuario || this.filtros.email || this.filtros.nombre_completo_usuario || this.filtros.dni_usuario || this.filtros.dependencia || this.filtros.perito) {
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
        title: '¿Desea eliminar el usuario?',
        text: 'Eliminar un usuario es permanente',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#F5365C',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          const url = 'api/user/eliminar/'
          const data = { id: row.id }
          this.mostrarSpinner(true)
          axios.delete(url, { data: data })
            .then(response => {
              this.$bvToast.toast('El usuario fue borrado correctamente', {
                title: 'Exito',
                autoHideDelay: 3000
              })
              this.getData()
              this.mostrarSpinner(false)
            }).catch(error => {
            this.mostrarSpinner(false)
            this.mostrarErrores(error.response.data.errors)
            })
        }
      })
    },
    blanquearClave (event, row) {
      Swal.fire({
        title: '¿Desea blanquear la contraseña del usuario?',
        text: 'La contraseña pasara a ser igual al número de documento del usuario',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#F5365C',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          this.mostrarSpinner(true)
          axios.put('api/user/blanquear-clave/' + row.id)
            .then(response => {
              this.mostrarSpinner(false)
              this.$bvToast.toast('La contraseña fue blanqueada correctamente')
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
        }, 2000)
      } else {
        this.isLoading = false
      }
    },
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    getDependencias () {
      this.mostrarSpinner(true)
      const url = '/api/dependencias'
      axios.get(url).then((result) => {
        this.dependencias = result.data.data.slice()
        if (!this.permissions.includes('crear_para_todas_las_dependencias')) {
          this.dependencias = this.dependencias.filter(delegacion => delegacion.id === this.user.dependencia_id)
        }
        this.mostrarSpinner(false)
      })
    },
    transformarRolesArray (array) {
      const aux = this.mapeoRolesArray(array)
      if (aux.indexOf(', ') === -1) {
        return aux
      } else {
        return aux.substring(0, aux.indexOf(', ') + 1).concat(' ...')
      }
    },
    mapeoRolesArray (array) {
      return array.map(item => {
        return item.name
      }).toString()
        .replace('["', '')
        .replaceAll(',', ', ')
        .replace('"]', '')
    }
  }
}
</script>

<style scoped>

</style>
