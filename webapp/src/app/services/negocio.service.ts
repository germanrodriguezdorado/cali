import { Injectable } from '@angular/core';
import { HttpClient, HttpResponse } from "@angular/common/http";
import { Observable, Subscriber } from 'rxjs';
import { tap, map, filter } from 'rxjs/operators';
import { HashLocationStrategy } from '@angular/common';
import { environment } from '@environments/environment';




@Injectable({
  providedIn: 'root'
})


export class NegocioService {

  constructor(private http: HttpClient) { 

  }



    // PRIVADOS DEL NEGOCIO

    darInfo(){
        return this.http.get(environment.api_path + "/api_hc/negocio/informacion");
    }

    guardarInfo(data: any){
      return this.http.post< any >(environment.api_path + "/api_hc/negocio/guardar_info/", data);
    }

    darAgenda(data: any){
      return this.http.post<any>(environment.api_path + "/api_hc/negocio/agenda", data);
    }

    marcarAtendido(data: any){
      return this.http.post<any>(environment.api_path + "/api_hc/negocio/marcar_atendido", data);
    }

    marcarNoConcurre(data: any){
      return this.http.post<any>(environment.api_path + "/api_hc/negocio/marcar_no_concurre", data);
    }

    marcarCancelar(data: any){
      return this.http.post<any>(environment.api_path + "/api_hc/negocio/marcar_cancelar", data);
    }

    

    





    // PUBLICOS

    buscar(data: any){
      return this.http.post< any >(environment.api_path + "/api_hc/p/buscar_negocio", data);
    }

    buscarHorarios(data: any){
      return this.http.post< any >(environment.api_path + "/api_hc/p/buscar_horarios", data);
    }

    agendar(data: any){
      return this.http.post< any >(environment.api_path + "/api_hc/p/agendar", data);
    }

    agendarConfirmacion(data: any){
      return this.http.post< any >(environment.api_path + "/api_hc/p/confirmacion", data);
    }


  // Acceso de Clientes

//   filtrarCliente(filtros){
//     return this.http.post< any >(environment.api_path + "/api_bolsamax/cliente/orden_trabajo/filtrar/", filtros);
//   }


//   crearCliente(datos){
//     return this.http.post< any >(environment.api_path + "/api_bolsamax/cliente/orden_trabajo/crear/", datos);
//   }

//   editar(datos){
//     return this.http.post< any >(environment.api_path + "/api_bolsamax/cliente/orden_trabajo/editar/", datos);
//   }


//   eliminarCliente(id : number){
//     return this.http.delete< number >(environment.api_path + "/api_bolsamax/cliente/orden_trabajo/eliminar/" + id);
//   }


//   traerCliente(id: number){
//     return this.http.get(environment.api_path + "/api_bolsamax/cliente/orden_trabajo/traer/" + id);
//   }


  


//   actualizarCliente(data){
//     return this.http.post< any >(environment.api_path + "/api_bolsamax/cliente/orden_trabajo/actualizar/", data);
//   }



//   traerFoto(otlog_id: number): Observable<Blob> {

  
//     var filter = {
//       "id": otlog_id
//     }

// 		return this.http.post<any>(environment.api_path + '/api_bolsamax/cliente/orden_trabajo/traer_foto/', filter, { responseType: 'blob' as 'json' }).pipe(map((res) => {
// 			if (res.status !== 204) {
// 				return <Blob> res;
// 			}
// 		}));

//   }
  





//   reporteBuscarCliente(data) {
// 		return this.http.post<any>(environment.api_path + '/api_bolsamax/cliente/orden_trabajo/reporte/', data);
//   }
  

//   reporteExportarCliente(data): Observable<Blob> {
// 		return this.http.post<any>(environment.api_path + '/api_bolsamax/cliente/orden_trabajo/reporte_excel/', data, { responseType: 'blob' as 'json' }).pipe(map((res) => {
// 			if (res.status !== 204) {
// 				return <Blob> res;
// 			}
// 		}));
//   }





//   reporteBuscarAdmin(data) {
// 		return this.http.post<any>(environment.api_path + '/api_bolsamax/admin/orden_trabajo/reporte/', data);
//   }
  

//   reporteExportarAdmin(data): Observable<Blob> {
// 		return this.http.post<any>(environment.api_path + '/api_bolsamax/admin/orden_trabajo/reporte_excel/', data, { responseType: 'blob' as 'json' }).pipe(map((res) => {
// 			if (res.status !== 204) {
// 				return <Blob> res;
// 			}
// 		}));
//   }





  
  






 




//   // USUARIO ADMIN


//   crearAdmin(datos){
//     return this.http.post< any >(environment.api_path + "/api_bolsamax/admin/orden_trabajo/crear/", datos);
//   }



//   filtrarAdmin(filtros){
//     return this.http.post< any >(environment.api_path + "/api_bolsamax/admin/orden_trabajo/filtrar/", filtros);
//   }


//   traerAdmin(id: number){
//     return this.http.get(environment.api_path + "/api_bolsamax/admin/orden_trabajo/traer/" + id);
//   }


//   actualizarAdmin(data){
//     return this.http.post< any >(environment.api_path + "/api_bolsamax/admin/orden_trabajo/actualizar/", data);
//   }


//   eliminarAdmin(id: number){
//     return this.http.get(environment.api_path + "/api_bolsamax/admin/orden_trabajo/eliminar/" + id);
//   }

//   darFinalizacion(orden_id: number){
//     return this.http.get(environment.api_path + "/api_bolsamax/admin/orden_trabajo/dar_finalizacion/" + orden_id);
//   }

//   actualizarFinalizacion(data){
//     return this.http.post< any >(environment.api_path + "/api_bolsamax/admin/orden_trabajo/actualizar_finalizacion/", data);
//   }







//     // USUARIO MOVIL

//     filtrarMovil(){
//       return this.http.post< any >(environment.api_path + "/api_bolsamax/movil/orden_trabajo/filtrar/", null);
//     }


//     traerMovil(id: number){
//       return this.http.get(environment.api_path + "/api_bolsamax/movil/orden_trabajo/traer/" + id);
//     }


//     actualizarMovil(data){
//       return this.http.post< any >(environment.api_path + "/api_bolsamax/movil/orden_trabajo/actualizar/", data);
//     }







//     // COMMON
//     darFoto(data: any){
//       return this.http.post< any >(environment.api_path + "/api_bolsamax/common/orden_trabajo/dar_foto/", data);
//     }










}