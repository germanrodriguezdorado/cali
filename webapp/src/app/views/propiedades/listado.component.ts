import { Component, OnInit } from '@angular/core';
import { NgxSpinnerService } from "ngx-spinner";

@Component({
  templateUrl: 'listado.component.html'
})
export class ListadoComponent implements OnInit {

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
