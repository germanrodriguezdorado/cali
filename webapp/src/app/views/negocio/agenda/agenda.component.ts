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


  @ViewChild('myModal') public myModal: ModalDirective;

  constructor(
    private NgxSpinnerService: NgxSpinnerService,
    private DatePipe: DatePipe,
    private NegocioService: NegocioService,
    ) { }


  ngOnInit() {
    this.cargando = false;
    this.fecha = new Date();
    this.agendas = [];
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

}
