// Angular
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NgModule } from '@angular/core';

import { ClienteAgendaComponent } from './agenda/agenda.component';
import { ClienteInformacionComponent } from './informacion/informacion.component';

// Collapse Component
import { CollapseModule } from 'ngx-bootstrap/collapse';
import { ModalModule } from 'ngx-bootstrap/modal';



// Components Routing
import { NegocioRoutingModule } from './cliente-routing.module';
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
    ClienteAgendaComponent,
    ClienteInformacionComponent
  ]
})
export class ClienteModule { }
