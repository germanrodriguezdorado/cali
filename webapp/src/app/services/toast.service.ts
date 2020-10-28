import { Injectable, Component } from '@angular/core';
import { ToastrService } from 'ngx-toastr'; 




@Injectable({
  providedIn: "root"
})


@Component({
  selector: 'app-root',
  template: `
  <h1><a>Click</a></h1>
  <div toastContainer></div>
`
})


export class ToastService {


  config;
    

  constructor(private toastr: ToastrService) { 

    // https://www.npmjs.com/package/ngx-toastr
    this.config = { 
      timeOut: 4000,
      positionClass: "toast-bottom-right",
      preventDuplicates: true,
      progressBar: true
      //easing: 'ease-in'
    }
    

  }

  notificar(tipo:string, titulo:string, mensaje:[]){   
    var str : string = "";
    mensaje.forEach(element => { str += element });    
    if(tipo == "success") this.toastr.success(titulo,str,this.config);
    if(tipo == "warning") this.toastr.warning(titulo,str,this.config);
    if(tipo == "error") this.toastr.error(titulo,str,this.config);  
  }

  

}
