import { Component, OnInit, ViewChild } from '@angular/core';
import { ModalDirective } from 'ngx-bootstrap/modal';
import { NgxSpinnerService } from "ngx-spinner";
import { DatePipe } from "@angular/common";
import { NegocioService } from "@services/negocio.service";

@Component({
  templateUrl: 'agenda.component.html',
  providers: [DatePipe]
})
export class NegocioAgendaComponent implements OnInit {

  fecha: Date;
  agendas: any[];
  cargando: boolean;
  agenda_seleccionada: string;


  @ViewChild('modalAtendido') public modalAtendido: ModalDirective;
  @ViewChild('modalNoConcurre') public modalNoConcurre: ModalDirective;
  @ViewChild('modalCancelar') public modalCancelar: ModalDirective;

  constructor(
    private NgxSpinnerService: NgxSpinnerService,
    private DatePipe: DatePipe,
    private NegocioService: NegocioService,
    ) { }


  ngOnInit() {
    this.cargando = false;
    this.fecha = new Date();
    this.agendas = [];
    this.agenda_seleccionada = "";
    this.buscar();
  
  }


  buscar(){
    this.NgxSpinnerService.show();
    this.cargando = true;
    this.agendas = [];
    var data = {
      "fecha": this.DatePipe.transform(this.fecha, "yyyy-MM-dd")
    }
    this.NegocioService.darAgenda(data).subscribe(respuesta => {
      this.agendas = respuesta;
      this.NgxSpinnerService.hide();
      this.cargando = false;
    });
  }




  mostrarModal(modal: string, agenda: string){
    

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

  }



  atendido(){
    this.NgxSpinnerService.show();
    var data = {
      "agenda": this.agenda_seleccionada
    }
    this.NegocioService.marcarAtendido(data).subscribe(respuesta => {
      this.modalAtendido.hide();
      this.buscar();
    });
  }

  noConcurre(){
    this.NgxSpinnerService.show();
    var data = {
      "agenda": this.agenda_seleccionada
    }
    this.NegocioService.marcarNoConcurre(data).subscribe(respuesta => {
      this.modalNoConcurre.hide();
      this.buscar();
    });
  }

  cancelar(){
    this.NgxSpinnerService.show();
    var data = {
      "agenda": this.agenda_seleccionada
    }
    this.NegocioService.marcarCancelar(data).subscribe(respuesta => {
      this.modalCancelar.hide();
      this.buscar();
    });
  }




}
