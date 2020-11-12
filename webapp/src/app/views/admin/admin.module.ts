// Angular
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NgModule } from '@angular/core';

import { NegociosComponent } from './negocios/negocios.component';

// Collapse Component
import { CollapseModule } from 'ngx-bootstrap/collapse';
import { ModalModule } from 'ngx-bootstrap/modal';



// Components Routing
import { NegocioRoutingModule } from './admin-routing.module';
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
    NegociosComponent,
  ]
})
export class AdminModule { }
