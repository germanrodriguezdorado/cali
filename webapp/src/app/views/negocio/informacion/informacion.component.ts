import { Component, OnInit } from '@angular/core';
import { ToastService } from "@services/toast.service";



@Component({
  templateUrl: 'informacion.component.html'
})
export class InformacionComponent implements OnInit {

  
  constructor(
    private ToastService: ToastService
  ) { }

  isCollapsed: boolean = false;
  iconCollapse: string = 'icon-arrow-up';

  collapsed(event: any): void {
    // console.log(event);
  }

  expanded(event: any): void {
    // console.log(event);
  }

  toggleCollapse(): void {
    this.isCollapsed = !this.isCollapsed;
    this.iconCollapse = this.isCollapsed ? 'icon-arrow-down' : 'icon-arrow-up';
  }


  ngOnInit() {
    
  }

  guardar(){
    this.ToastService.notificar("warning", "Por favor, complete todos los campos requeridos.", []);
  }

}
