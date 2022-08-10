<template>
  <b-modal id="detalleModal" :title="infoModal.title" header-class="border-bottom-0" scrollable size="xl" footer-class="border-top-0" modal hide-footer>
    <div class="modal-body">
      <b-card class="text-center" header="Datos Generales" header-bg-variant="primary"
              header-text-variant="secondary"
      >
        <b-row v-if="infoModal.content.tipo_intervencion_tecnica">
          <b-col>
            <b>{{ infoModal.content.tipo_intervencion_tecnica ? infoModal.content.tipo_intervencion_tecnica.nombre : '-' }}</b>
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.fecha_sola && infoModal.content.hora_sola">
          <b-col>
            <b>Fecha: </b> {{ infoModal.content.fecha_sola }}
          </b-col>
          <b-col>
            <b>Hora: </b> {{ infoModal.content.hora_sola }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.fecha_hora_registro && infoModal.content.fecha_hora_solicitud">
          <b-col>
            <b>Fecha Solicitud: </b> {{ infoModal.content.fecha_hora_solicitud }}
          </b-col>
          <b-col>
            <b>Fecha Registro: </b> {{ infoModal.content.fecha_hora_registro }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.legajo_cuerpo_medico_forense">
          <b-col>
            <b>Legajo Cuerpo Medico Forense: </b>{{ infoModal.content.legajo_cuerpo_medico_forense }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.expediente">
          <b-col>
            <b>Número Expediente:</b> {{
              infoModal.content.expediente.numero_expediente ? infoModal.content.expediente.numero_expediente : '-'
            }}
          </b-col>
          <b-col>
            <b>Origen:</b> {{
              infoModal.content.expediente.origen ? infoModal.content.expediente.origen : '-'
            }}
          </b-col>
        </b-row>
        <b-row>
          <b-col>
            <b>Delegación:</b>
            {{ infoModal.content.delegacion_dependencia && infoModal.content.delegacion_dependencia.nombre ? infoModal.content.delegacion_dependencia.nombre : '-' }}
          </b-col>
          <b-col>
            <b>Grupo:</b>
            {{ infoModal.content.delegacion_dependencia && infoModal.content.delegacion_dependencia.grupo ? infoModal.content.delegacion_dependencia.grupo : '-' }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.interviniento_dependencia">
          <b-col>
            <b>Interviniente:</b> {{
              infoModal.content.interviniento_dependencia && infoModal.content.interviniento_dependencia.nombre ? infoModal.content.interviniento_dependencia.nombre : '-'
            }}
          </b-col>
          <b-col>
            <b>Grupo:</b>
            {{ infoModal.content.interviniento_dependencia && infoModal.content.interviniento_dependencia.grupo ? infoModal.content.interviniento_dependencia.grupo : '-' }}
          </b-col>
        </b-row>
        <b-row>
          <b-col v-if="infoModal.content.delito">
            <b>Delito: </b> {{ infoModal.content.delito ? infoModal.content.delito.nombre : '-' }}
          </b-col>
          <b-col v-if="infoModal.content.tipo_colision">
            <b>Tipo Colisión: </b> {{ infoModal.content.tipo_colision ? infoModal.content.tipo_colision.nombre : '-' }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.funcionario">
          <b-col>
            <b>Funcionario: </b> {{ infoModal.content.funcionario }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.descripcion">
          <b-col>
            <b>Descripción: </b> <p>
              {{ infoModal.content.descripcion }}
            </p>
          </b-col>
        </b-row>
      </b-card>
      <b-card v-if="infoModal.content.oficios && infoModal.content.oficios.length >= 1" class="text-center" header="Oficios" header-bg-variant="primary" header-text-variant="secondary">
        <b-row class="text-center" align-h="center">
          <b-table responsive="lg" striped hover :items="infoModal.content.oficios" :fields="camposOficios" />
        </b-row>
      </b-card>
      <b-card v-if="infoModal.content.cadaver_nombre||infoModal.content.cadaver_documento||infoModal.content.observaciones" class="text-center" header="Datos del Cadaver" header-bg-variant="primary"
              header-text-variant="secondary"
      >
        <b-row v-if="infoModal.content.cadaver_nombre||infoModal.content.cadaver_documento">
          <b-col><b>Nombre: </b> {{ infoModal.content.cadaver_nombre ? infoModal.content.cadaver_nombre : '-' }}</b-col>
          <b-col v-if="infoModal.content.cadaver_documento && infoModal.content.cadaver_documento.numero">
            <b>{{
              infoModal.content.cadaver_documento && infoModal.content.cadaver_documento.tipo_documento ? infoModal.content.cadaver_documento.tipo_documento : '-'
            }}:</b> {{
              infoModal.content.cadaver_documento && infoModal.content.cadaver_documento.numero ? infoModal.content.cadaver_documento.numero : '-'
            }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.observaciones">
          <b-col>
            <b>Observaciones: </b> <p>
              {{ infoModal.content.observaciones }}
            </p>
          </b-col>
        </b-row>
      </b-card>
      <b-card v-if="infoModal.content.plano||infoModal.content.foto||infoModal.content.video||infoModal.content.indicios" class="text-center" header="Muestras" header-bg-variant="primary"
              header-text-variant="secondary"
      >
        <b-container style="max-width: 70%;">
          <b-row class="text-center" style="margin-bottom: 15px;">
            <b-col><b>Plano: </b>{{ infoModal.content.plano ? 'SI' : 'NO' }}</b-col>
            <b-col><b>Foto: </b>{{ infoModal.content.foto ? 'SI' : 'NO' }}</b-col>
            <b-col><b>Video: </b>{{ infoModal.content.video ? 'SI' : 'NO' }}</b-col>
          </b-row>

          <span v-if="infoModal.content.indicios && infoModal.content.indicios.length >= 1">
            <b-row class="text-center" align-h="center">
              <b-table responsive="lg" striped hover :items="infoModal.content.indicios" :fields="camposIndicios" />
            </b-row>
          </span>
        </b-container>
      </b-card>
      <b-card v-if="infoModal.content.perito_usuario||infoModal.content.tecnico_revelador_usuario||infoModal.content.secretario_usuario||infoModal.content.planimetrista_usuario||infoModal.content.fotografo_usuario||infoModal.content.video_usuario"
              class="text-center" header="Peritos" header-bg-variant="primary" header-text-variant="secondary"
      >
        <b-row>
          <b-col />
          <b-col><b>Nombre</b></b-col>
          <b-col><b>DNI</b></b-col>
          <b-col><b>Dependencia</b></b-col>
        </b-row>
        <b-row v-if="infoModal.content.perito_usuario">
          <b-col><b>Perito </b></b-col>
          <b-col>
            {{
              infoModal.content.perito_usuario.nombre_completo ? infoModal.content.perito_usuario.nombre_completo : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.perito_usuario.documento && infoModal.content.perito_usuario.documento.numero ? infoModal.content.perito_usuario.documento.numero : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.perito_usuario.dependencia && infoModal.content.perito_usuario.dependencia.nombre ? infoModal.content.perito_usuario.dependencia.nombre : '-'
            }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.acompanante_usuario">
          <b-col><b>Acompanante </b></b-col>
          <b-col>
            {{
              infoModal.content.acompanante_usuario.nombre_completo ? infoModal.content.acompanante_usuario.nombre_completo : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.acompanante_usuario.documento && infoModal.content.acompanante_usuario.documento.numero ? infoModal.content.acompanante_usuario.documento.numero : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.acompanante_usuario.dependencia && infoModal.content.acompanante_usuario.dependencia.nombre ? infoModal.content.acompanante_usuario.dependencia.nombre : '-'
            }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.tecnico_revelador_usuario">
          <b-col><b>Técnico Revelador </b></b-col>
          <b-col>
            {{
              infoModal.content.tecnico_revelador_usuario.nombre_completo ? infoModal.content.tecnico_revelador_usuario.nombre_completo : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.tecnico_revelador_usuario.documento && infoModal.content.tecnico_revelador_usuario.documento.numero ? infoModal.content.tecnico_revelador_usuario.documento.numero : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.tecnico_revelador_usuario.dependencia && infoModal.content.tecnico_revelador_usuario.dependencia.nombre ? infoModal.content.tecnico_revelador_usuario.dependencia.nombre : '-'
            }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.secretario_usuario">
          <b-col><b>Secretario </b></b-col>
          <b-col>
            {{
              infoModal.content.secretario_usuario.nombre_completo ? infoModal.content.secretario_usuario.nombre_completo : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.secretario_usuario.documento && infoModal.content.secretario_usuario.documento.numero ? infoModal.content.secretario_usuario.documento.numero : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.secretario_usuario.dependencia && infoModal.content.secretario_usuario.dependencia.nombre ? infoModal.content.secretario_usuario.dependencia.nombre : '-'
            }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.planimetrista_usuario">
          <b-col><b>Planimetrista </b></b-col>
          <b-col>
            {{
              infoModal.content.planimetrista_usuario.nombre_completo ? infoModal.content.planimetrista_usuario.nombre_completo : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.planimetrista_usuario.documento && infoModal.content.planimetrista_usuario.documento.numero ? infoModal.content.planimetrista_usuario.documento.numero : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.planimetrista_usuario.dependencia && infoModal.content.planimetrista_usuario.dependencia.nombre ? infoModal.content.planimetrista_usuario.dependencia.nombre : '-'
            }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.fotografo_usuario">
          <b-col><b>Fotógrafo </b></b-col>
          <b-col>
            {{
              infoModal.content.fotografo_usuario.nombre_completo ? infoModal.content.fotografo_usuario.nombre_completo : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.fotografo_usuario.documento && infoModal.content.fotografo_usuario.documento.numero ? infoModal.content.fotografo_usuario.documento.numero : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.fotografo_usuario.dependencia && infoModal.content.fotografo_usuario.dependencia.nombre ? infoModal.content.fotografo_usuario.dependencia.nombre : '-'
            }}
          </b-col>
        </b-row>
        <b-row v-if="infoModal.content.video_usuario">
          <b-col><b>Camarógrafo </b></b-col>
          <b-col>
            {{
              infoModal.content.video_usuario.nombre_completo ? infoModal.content.video_usuario.nombre_completo : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.video_usuario.documento && infoModal.content.video_usuario.documento.numero ? infoModal.content.video_usuario.documento.numero : '-'
            }}
          </b-col>
          <b-col>
            {{
              infoModal.content.video_usuario.dependencia && infoModal.content.video_usuario.dependencia.nombre ? infoModal.content.video_usuario.dependencia.nombre : '-'
            }}
          </b-col>
        </b-row>
      </b-card>
      <b-card v-if="infoModal.content.informes_laboratorio && infoModal.content.informes_laboratorio.length >= 1" class="text-center" header="Informes Laboratorio" header-bg-variant="primary"
              header-text-variant="secondary"
      >
        <b-container style="max-width: 70%;">
          <b-row class="text-center" align-h="center">
            <b-table responsive="lg" striped hover :items="infoModal.content.informes_laboratorio" :fields="camposInformesLaboratorio" />
          </b-row>
        </b-container>
      </b-card>
      <b-card v-if="infoModal.content.detenidos && infoModal.content.detenidos.length >= 1" class="text-center" header="Detenidos" header-bg-variant="primary"
              header-text-variant="secondary"
      >
        <b-container style="max-width: 70%;">
          <b-row class="text-center" align-h="center">
            <b-table responsive="lg" striped hover :items="infoModal.content.detenidos" :fields="camposDetenidosVictimas" />
          </b-row>
        </b-container>
      </b-card>
      <b-card v-if="infoModal.content.victimas && infoModal.content.victimas.length >= 1" class="text-center" header="Victimas" header-bg-variant="primary"
              header-text-variant="secondary"
      >
        <b-container style="max-width: 70%;">
          <b-row class="text-center" align-h="center">
            <b-table responsive="lg" striped hover :items="infoModal.content.victimas" :fields="camposDetenidosVictimas" />
          </b-row>
        </b-container>
      </b-card>
      <b-card class="text-center" header="Lugar" header-bg-variant="primary"
              header-text-variant="secondary"
      >
        <b-row v-if="infoModal.content.lugar">
          <b-col>
            <b>Departamento: </b>{{ infoModal.content.lugar.departamento ? infoModal.content.lugar.departamento : '-' }}
          </b-col>
          <b-col><b>Localidad: </b>{{ infoModal.content.lugar.localidad ? infoModal.content.lugar.localidad : '-' }}</b-col>
          <b-col><b>Barrio: </b>{{ infoModal.content.lugar.barrio ? infoModal.content.lugar.barrio : '-' }}</b-col>
        </b-row>
        <b-row v-if="infoModal.content.lugar">
          <b-col><b>Calle: </b>{{ infoModal.content.lugar.calle ? infoModal.content.lugar.calle : '-' }}</b-col>
          <b-col><b>Número: </b>{{ infoModal.content.lugar.numero ? infoModal.content.lugar.numero : '-' }}</b-col>
          <b-col>
            <b>Número Departamento: </b>{{
              infoModal.content.lugar.numero_departamento ? infoModal.content.lugar.numero_departamento : '-'
            }}
          </b-col>
        </b-row>
      </b-card>
    </div>
  </b-modal>
</template>

<script>
export default {
  name: 'DetalleModal',
  props: {
    infoModal: {
      type: Object,
      default: () => ({
        title: '',
        content: ''
      })
    }
  },
  data () {
    return {
      camposIndicios: [
        {
          key: 'tipo_indicio.nombre',
          label: 'Tipo Indicio'
        },
        {
          key: 'cantidad',
          label: 'Cantidad'
        },
        {
          key: 'detalle',
          label: 'Detalle'
        }
      ],
      camposInformesLaboratorio: [
        {
          key: 'tipo_informes_laboratorio.nombre',
          label: 'Tipo Informe'
        },
        {
          key: 'numero_informe',
          label: 'Número Informe'
        }
      ],
      camposDetenidosVictimas: [
        {
          key: 'nombre',
          label: 'Nombre'
        },
        {
          key: 'apellido',
          label: 'Apellido'
        },
        {
          key: 'documento.tipo_documento',
          label: 'Tipo'
        },
        {
          key: 'documento.numero',
          label: 'Numero'
        },
        {
          key: 'fecha_nacimiento',
          label: 'Fecha Nac',
          formatter: value => {
            const fecha = value.split(' ')[0].split('-')
            return fecha[2] + '-' + fecha[1] + '-' + fecha[0]
          }
        }
      ],
      camposOficios: [
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
          key: 'observacion',
          label: 'Observaciones',
          class: 'text-center'
        },
        {
          key: 'estado_oficio.nombre',
          label: 'Estado',
          class: 'text-center'
        }
      ]
    }
  }
}
</script>

<style scoped>

</style>
