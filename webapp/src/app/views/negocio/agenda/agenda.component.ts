import { Component, OnInit, ViewChild } from '@angular/core';
import { ModalDirective } from 'ngx-bootstrap/modal';
import { NgxSpinnerService } from "ngx-spinner";

@Component({
  templateUrl: 'agenda.component.html'
})
export class AgendaComponent implements OnInit {


  @ViewChild('myModal') public myModal: ModalDirective;

  constructor(private NgxSpinnerService: NgxSpinnerService) { }


  ngOnInit() {
    /** spinner starts on init */
    this.NgxSpinnerService.show();
 
    setTimeout(() => {
      /** spinner ends after 5 seconds */
      this.NgxSpinnerService.hide();
    }, 2000);
  }

}
