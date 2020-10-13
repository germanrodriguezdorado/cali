import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { LocationStrategy, HashLocationStrategy } from '@angular/common';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule, HTTP_INTERCEPTORS } from "@angular/common/http";
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule, NgbTimeAdapter, NgbDatepickerI18n, NgbDateParserFormatter } from '@ng-bootstrap/ng-bootstrap';


import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { PERFECT_SCROLLBAR_CONFIG } from 'ngx-perfect-scrollbar';
import { PerfectScrollbarConfigInterface } from 'ngx-perfect-scrollbar';

const DEFAULT_PERFECT_SCROLLBAR_CONFIG: PerfectScrollbarConfigInterface = {
  suppressScrollX: true
};

import { AppComponent } from './app.component';

// Import containers
import { DefaultLayoutComponent } from './containers';


import { HomeComponent } from '@views/home/home.component';
import { AgendarComponent } from '@views/agendar/agendar.component';
import { P404Component } from '@views/error/404.component';
import { P500Component } from '@views/error/500.component';
import { LoginComponent } from '@views/login/login.component';
import { RegisterComponent } from '@views/register/register.component';


import { NgbTimeStringAdapter } from '@helpers/timepicker-adapter';
import { NgbDateFRParserFormatter } from '@helpers/datepicker-adapter';
import { JwtInterceptor } from '@helpers/jwt.interceptor';
import { Utilidades } from "@helpers/utilidades";
import { VersionCheckService } from "@services/version-check.service";
import { ToastService } from '@services/toast.service';
import { OwlDateTimeModule, OwlNativeDateTimeModule } from '@danielmoncada/angular-datetime-picker';



const APP_CONTAINERS = [
  DefaultLayoutComponent
];

import {
  AppAsideModule,
  AppBreadcrumbModule,
  AppHeaderModule,
  AppFooterModule,
  AppSidebarModule,
} from '@coreui/angular';

// Import routing module
import { AppRoutingModule } from './app.routing';

// Import 3rd party components
import { BsDropdownModule } from 'ngx-bootstrap/dropdown';
import { TabsModule } from 'ngx-bootstrap/tabs';
import { ChartsModule } from 'ng2-charts';
import { NgSelectModule } from '@ng-select/ng-select';
import { ToastrModule } from "ngx-toastr";
import { CustomToast } from '@helpers/custom.toast';
import { NgxSpinnerModule } from "ngx-spinner";

@NgModule({
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    AppRoutingModule,
    AppAsideModule,
    AppBreadcrumbModule.forRoot(),
    AppFooterModule,
    AppHeaderModule,
    AppSidebarModule,
    PerfectScrollbarModule,
    BsDropdownModule.forRoot(),
    TabsModule.forRoot(),
    ChartsModule,
    NgSelectModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    NgxSpinnerModule,
    ToastrModule.forRoot({
      toastComponent: CustomToast // added custom toast!
    }),
    OwlDateTimeModule, 
    OwlNativeDateTimeModule,
  ],
  declarations: [
    AppComponent,
    ...APP_CONTAINERS,
    HomeComponent,
    AgendarComponent,
    P404Component,
    P500Component,
    LoginComponent,
    RegisterComponent,
    CustomToast,
    ToastService
  ],
  providers: [
    {provide: LocationStrategy, useClass: HashLocationStrategy},
    { provide: HTTP_INTERCEPTORS, useClass: JwtInterceptor, multi: true},
    { provide: Utilidades, useClass: Utilidades },
    { provide: VersionCheckService, useClass: VersionCheckService },
    { provide: NgbTimeAdapter, useClass: NgbTimeStringAdapter },
    { provide: NgbDateParserFormatter, useClass: NgbDateFRParserFormatter},
  ],
  bootstrap: [ AppComponent ],
  entryComponents: [ CustomToast ]
})
export class AppModule { }
