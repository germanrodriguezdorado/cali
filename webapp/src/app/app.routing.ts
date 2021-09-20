import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

// Import Containers
import { DefaultLayoutComponent } from './containers';

import { P404Component } from '@views/error/404.component';
import { P500Component } from '@views/error/500.component';
import { LoginComponent } from '@views/login/login.component';
import { HomeComponent } from '@views/home/home.component';
import { AgendarComponent } from '@views/agendar/agendar.component';
import { AgendarConfirmacionComponent } from '@views/agendarConfirmacion/agendarConfirmacion.component';
import { RegisterComponent } from '@views/register/register.component';
import { RegisterCheckComponent } from '@views/registerCheck/registerCheck.component';
import { PasswordRecoveryS1Component } from "@views/passwordRecoveryS1/passwordRecoveryS1.component";
import { PasswordRecoveryS2Component } from "@views/passwordRecoveryS2/passwordRecoveryS2.component";

export const routes: Routes = [
  { path: '', component: HomeComponent, data: { title: 'Home' }},
  { path: 'tutorial', component: HomeComponent, data: { title: 'Home' }},
  { path: 's/:negocio', component: AgendarComponent, data: { title: 'Agendar' }},
  { path: 'confirmar/:token', component: AgendarConfirmacionComponent, data: { title: 'Agendar' }},
  { path: '404', component: P404Component, data: { title: 'Page 404'} },
  { path: '500', component: P500Component, data: { title: 'Page 500'} },
  { path: 'login', component: LoginComponent, data: { title: 'Login Page' }},
  { path: 'register', component: RegisterComponent, data: { title: 'Register Page' }},
  { path: 'register-check/:token', component: RegisterCheckComponent, data: { title: 'Register Check Page' }},
  { path: "password-recovery-step1", component: PasswordRecoveryS1Component },
  { path: "password-recovery-step2/:token", component: PasswordRecoveryS2Component },
  { path: '', component: DefaultLayoutComponent,
    children: [
      { path: 'negocio', loadChildren: () => import('@views/negocio/negocio.module').then(m => m.NegocioModule)},
      { path: 'admin', loadChildren: () => import('@views/admin/admin.module').then(m => m.AdminModule)},
      // { path: 'buttons', loadChildren: () => import('./views/buttons/buttons.module').then(m => m.ButtonsModule)},
      // { path: 'charts', loadChildren: () => import('./views/chartjs/chartjs.module').then(m => m.ChartJSModule)},
      // { path: 'dashboard', loadChildren: () => import('./views/dashboard/dashboard.module').then(m => m.DashboardModule) },
      // { path: 'icons', loadChildren: () => import('./views/icons/icons.module').then(m => m.IconsModule) },
      // { path: 'notifications', loadChildren: () => import('./views/notifications/notifications.module').then(m => m.NotificationsModule)},
      // { path: 'widgets', loadChildren: () => import('./views/widgets/widgets.module').then(m => m.WidgetsModule) }
    ]
  },
  { path: '**', component: P404Component }
];

@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {}
