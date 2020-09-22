// Angular
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NgModule } from '@angular/core';

import { ListadoComponent } from './listado.component';
import { NuevaComponent } from './nueva.component';

// Collapse Component
import { CollapseModule } from 'ngx-bootstrap/collapse';



// Components Routing
import { PropiedadesRoutingModule } from './propiedades-routing.module';

import { NgSelectModule } from '@ng-select/ng-select';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    CollapseModule.forRoot(),
    PropiedadesRoutingModule,
    NgSelectModule
  ],
  declarations: [
    ListadoComponent,
    NuevaComponent,
  ]
})
export class PropiedadesModule { }
