import { Component, OnInit, ViewChild } from '@angular/core';
import { ModalDirective } from 'ngx-bootstrap/modal';
import { NgxSpinnerService } from "ngx-spinner";

@Component({
  templateUrl: 'agenda.component.html'
})
export class ClienteAgendaComponent implements OnInit {


  @ViewChild('myModal') public myModal: ModalDirective;

  constructor(private NgxSpinnerService: NgxSpinnerService) { }


  ngOnInit() {
   
  }

}
