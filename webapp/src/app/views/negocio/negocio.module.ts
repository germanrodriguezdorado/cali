// Angular
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NgModule } from '@angular/core';

import { AgendaComponent } from './agenda/agenda.component';
import { TrabajosComponent } from './trabajos/trabajos.component';
import { HorariosComponent } from './horarios/horarios.component';
import { InformacionComponent } from './informacion/informacion.component';

// Collapse Component
import { CollapseModule } from 'ngx-bootstrap/collapse';
import { ModalModule } from 'ngx-bootstrap/modal';



// Components Routing
import { NegocioRoutingModule } from './negocio-routing.module';

import { NgSelectModule } from '@ng-select/ng-select';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    CollapseModule.forRoot(),
    ModalModule.forRoot(),
    NegocioRoutingModule,
    NgSelectModule
  ],
  declarations: [
    AgendaComponent,
    TrabajosComponent,
    InformacionComponent,
    HorariosComponent
  ]
})
export class NegocioModule { }
