import { Component, OnInit, ViewChild } from '@angular/core';
import { ModalDirective } from 'ngx-bootstrap/modal';
import { NgxSpinnerService } from "ngx-spinner";
import { NegocioService } from "@services/negocio.service";

@Component({
  templateUrl: 'negocios.component.html'
})
export class NegociosComponent implements OnInit {

  negocios;
  negocio_id: string;


  @ViewChild('modalConfirmar') public modalConfirmar: ModalDirective;

  constructor(
    private NgxSpinnerService: NgxSpinnerService,
    private NegocioService: NegocioService
    ) { }


  ngOnInit() {
    this.negocios = [];
    this.negocio_id = "0";
    this.buscar();
  }


  buscar(){
    this.NgxSpinnerService.show();
    this.negocios = [];
    this.NegocioService.darNegocios().subscribe(respuesta => {
      this.negocios = respuesta;
      this.NgxSpinnerService.hide();
    });
  }


  eliminar(id: string){
    this.negocio_id = id;
    this.modalConfirmar.show();
  }


  confirmar(){
    this.NgxSpinnerService.show();
    var data = {
      "negocio_id": this.negocio_id
    }
    this.NegocioService.eliminarNegocio(data).subscribe(respuesta => {
      this.modalConfirmar.hide();
      this.buscar();
    });
  }

}
