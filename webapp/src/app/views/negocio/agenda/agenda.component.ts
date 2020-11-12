import { Component, OnInit, ViewChild } from '@angular/core';
import { ModalDirective } from 'ngx-bootstrap/modal';
import { NgxSpinnerService } from "ngx-spinner";
import { DatePipe } from "@angular/common";
import { NegocioService } from "@services/negocio.service";
import { Utilidades } from "@helpers/utilidades";

@Component({
  templateUrl: 'agenda.component.html',
  providers: [DatePipe]
})
export class NegocioAgendaComponent implements OnInit {

  fecha: Date;
  agendas: any[];
  cargando: boolean;
  agenda_seleccionada: [];
  motivo_cancelacion: string;
  bloquear_email: boolean;
  tomar_cliente: string;
  mostrar_horarios_libres: boolean;


  @ViewChild('modalAtendido') public modalAtendido: ModalDirective;
  @ViewChild('modalNoConcurre') public modalNoConcurre: ModalDirective;
  @ViewChild('modalCancelar') public modalCancelar: ModalDirective;
  @ViewChild('modalTomar') public modalTomar: ModalDirective;

  constructor(
    private NgxSpinnerService: NgxSpinnerService,
    private DatePipe: DatePipe,
    private NegocioService: NegocioService,
    public Utilidades: Utilidades
    ) { }


  ngOnInit() {
    this.cargando = false;
    this.fecha = new Date();
    this.agendas = [];
    this.agenda_seleccionada = [];
    this.motivo_cancelacion = "";
    this.bloquear_email = false;
    this.tomar_cliente = "";
    this.mostrar_horarios_libres = false;
    this.buscar();
  
  }


  buscar(){
    this.NgxSpinnerService.show();
    this.cargando = true;
    this.agendas = [];
    var data = {
      "fecha": this.DatePipe.transform(this.fecha, "yyyy-MM-dd"),
      "mostrar_horarios_libres": this.mostrar_horarios_libres
    }
    this.NegocioService.darAgenda(data).subscribe(respuesta => {
      this.agendas = respuesta;
      this.NgxSpinnerService.hide();
      this.cargando = false;
    });
  }




  mostrarModal(modal: string, agenda: []){
    

    this.agenda_seleccionada = agenda;


    if(modal == "atendido"){
      this.modalAtendido.show();
    }

    if(modal == "no concurre"){
      this.modalNoConcurre.show();
    }

    if(modal == "cancelar"){
      this.modalCancelar.show();
    }

    if(modal == "tomar"){
      this.modalTomar.show();
    }

  }



  atendido(){
    this.NgxSpinnerService.show();
    var data = {
      "agenda": this.agenda_seleccionada['id']
    }
    this.NegocioService.marcarAtendido(data).subscribe(respuesta => {
      this.modalAtendido.hide();
      this.buscar();
    });
  }

  noConcurre(){
    this.NgxSpinnerService.show();
    var data = {
      "agenda": this.agenda_seleccionada['id'],
      "bloquear": this.bloquear_email
    }
    this.NegocioService.marcarNoConcurre(data).subscribe(respuesta => {
      this.modalNoConcurre.hide();
      this.bloquear_email = false;
      this.buscar();
    });
  }

  cancelar(){
    this.NgxSpinnerService.show();
    var data = {
      "agenda": this.agenda_seleccionada['id'],
      "motivo": this.motivo_cancelacion
    }
    this.NegocioService.marcarCancelar(data).subscribe(respuesta => {
      this.modalCancelar.hide();
      this.motivo_cancelacion = "";
      this.buscar();
    });
  }



  tomar(){
    this.NgxSpinnerService.show();
    var data = {
      "fecha": this.DatePipe.transform(this.fecha, "yyyy-MM-dd"),
      "horario": this.agenda_seleccionada['horario'],
      "cliente": this.tomar_cliente
    }
    this.NegocioService.marcarTomado(data).subscribe(respuesta => {
      this.modalTomar.hide();
      this.tomar_cliente = "";
      this.buscar();
    });
  }




}
