// Angular
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NgModule } from '@angular/core';

import { ListadoComponent } from './listado.component';



// Components Routing
import { BaseRoutingModule } from './propiedades-routing.module';

import { NgSelectModule } from '@ng-select/ng-select';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    BaseRoutingModule,
    NgSelectModule
  ],
  declarations: [
    ListadoComponent
  ]
})
export class PropiedadesModule { }
