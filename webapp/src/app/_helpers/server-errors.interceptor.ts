import { Injectable } from '@angular/core'
import {
    HttpEvent,
    HttpInterceptor,
    HttpHandler,
    HttpRequest,
    HttpResponse,
    HttpErrorResponse
   } from '@angular/common/http';
   import { Observable, throwError } from 'rxjs';
   import { retry, catchError } from 'rxjs/operators';
   import { ToastService } from "@services/toast.service";
   import { NgxSpinnerService } from 'ngx-spinner';
  
   

   import { AuthService } from '@services/auth.service';
   

   @Injectable()
   export class HttpErrorInterceptor implements HttpInterceptor {

    


    constructor(
      private authenticationService: AuthService,
      private toastService: ToastService,
      private spinner: NgxSpinnerService,
      ){}
   

    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
      return next.handle(request)
        .pipe(
          retry(0),
          catchError((error: HttpErrorResponse) => {
            
            let errorMessage = '';
            if (error.error instanceof ErrorEvent) {
              // client-side error
              errorMessage = `Error: ${error.error.message}`;
            } else {
              // server-side error
              //this.toastService.notificar("error", error.error, []);
              this.toastService.notificar("error", "Ha ocurrido un error", []);
              this.spinner.hide();
              //errorMessage = `Error Code: ${error.status}\nMessage: ${error.message}`;
            }
            
        

            if (error.status === 401) {
              // auto logout if 401 response returned from api
              this.authenticationService.logout();
              location.reload(true);
            }

            // if (error.status === 500) {
            //   alert("dsdsds")
            // }


            return throwError(errorMessage);
          })
        )
    }

   
   }