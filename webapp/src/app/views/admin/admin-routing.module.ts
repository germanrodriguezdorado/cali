import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { NegociosComponent } from './negocios/negocios.component';

import { AuthGuard } from '@guards/auth.guard';

const routes: Routes = [
  {
    path: '', data: { title: 'Admin' },
    children: [
      { path: 'negocios', component: NegociosComponent, canActivate: [AuthGuard], data: { title: 'Negocios', roles: [2] }}
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class NegocioRoutingModule {}
