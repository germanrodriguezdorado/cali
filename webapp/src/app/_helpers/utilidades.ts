import { Injectable } from '@angular/core';
import { formatDate } from "@angular/common";
import * as $ from 'jquery' 


@Injectable()
export class Utilidades {


    fecha_actual: Date;


    constructor(){
        this.fecha_actual = new Date();
    }




    semasforo(tipo: string){
      if(tipo == "danger") return "#D9534E";
      if(tipo == "warning") return "#F0AD4E";
      if(tipo == "info") return "#5CC1DE";
      if(tipo == "success") return "#5CB95B";
      if(tipo == "primary") return "#3379B7";
      if(tipo == "default") return "#777777";
    }

 


    validarEmail(email : string): boolean{
        const validEmailRegEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (validEmailRegEx.test(email)) {
            return true;
        }else {
          return false;
        }
    }




   

    validarCi(ci: string): boolean{
        if(ci.length != 8) return false;
        ci = this.clean_ci(ci);
        var dig = ci[ci.length - 1];
        ci = ci.replace(/[0-9]$/, '');
        return (dig == this.validation_digit(ci));
    }

    validation_digit(ci: string): string{
        var a = 0;
        var i = 0;
        if(ci.length <= 6){
          for(i = ci.length; i < 7; i++){
            ci = '0' + ci;
          }
        }
        for(i = 0; i < 7; i++){
          a += (parseInt("2987634"[i]) * parseInt(ci[i])) % 10;
        }
        if(a%10 === 0){
          return "0";
        }else{
          var n = 10 - a % 10;
          return n.toString();
        }
      }
      
     
      
      
      
      clean_ci(ci: string): string{
        return ci.replace(/\D/g, '');
      }



   











    // UTILIDADES DE FECHAS


    //Ejemplo:
    //Entrada:
    //Fecha: "2019-10-09"

    //Salida
    //"09/10/2019"
    conversionFecha1(fecha: string){
      var y = (fecha.substring(0, 4));
      var m = (fecha.substring(5, 7));
      var d = (fecha.substring(8, 10));
      return d + "/" + m + "/" + y;
    }


     // Valida una fecha en formato "dd/mm/YYYY"
     validarFecha(value: string) : boolean {
        if(value.length != 10) return false;
        var re = /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;
        var flag = re.test(value);
        return flag;
    }


    // Fecha "d/m/Y" a FULL FECHA (Ej. Domingo 02 de Junio de 2019)
    aFechaFull(fecha: string){
        var y = (fecha.substring(6, 10));
        var m = (fecha.substring(3, 5));
        var d = (fecha.substring(0, 2));
        var fecha_ok = y + "-" + m + "-" + d;
        var dateString = fecha_ok + 'T03:00:00'; 
        var obj_fecha = new Date(dateString);
        var dia = formatDate(obj_fecha, "EEEE", "es");
        var fecha2 = formatDate(obj_fecha, "d", "es");
        var mes = formatDate(obj_fecha, "MMMM", "es");
        var anio = formatDate(obj_fecha, "y", "es");
        dia = dia[0].toUpperCase() + dia.substr(1).toLowerCase();
        mes = mes[0].toUpperCase() + mes.substr(1).toLowerCase();
        return (dia + " " + fecha2 + " de " + mes + " de " + anio);
    }



        // Fecha "d/m/Y" a FULL FECHA (Ej. Domingo 02 de Junio)
        aFechaFullSinAnio(fecha: string){
           if(fecha == "") return "";
            var y = (fecha.substring(6, 10));
            var m = (fecha.substring(3, 5));
            var d = (fecha.substring(0, 2));
            var fecha_ok = y + "-" + m + "-" + d;
            var dateString = fecha_ok + 'T03:00:00'; 
            var obj_fecha = new Date(dateString);
            var dia = formatDate(obj_fecha, "EEEE", "es");
            var fecha2 = formatDate(obj_fecha, "d", "es");
            var mes = formatDate(obj_fecha, "MMMM", "es");
            dia = dia[0].toUpperCase() + dia.substr(1).toLowerCase();
            mes = mes[0].toUpperCase() + mes.substr(1).toLowerCase();
            return (dia + " " + fecha2 + " de " + mes);
        }




        // Entrada: Fecha Y-m-d : string
        // Salida: Array = { "year": 2018, "month": 8, "day": 15 }
        fechaAArray(fecha: string){
            var y = (fecha.substring(0, 4));
            var m = (fecha.substring(5, 7));
            var d = (fecha.substring(8, 10));
            var respuesta = {
                "year": parseInt(y),
                "month": parseInt(m),
                "day": parseInt(d)
            }
            return respuesta;
        }


        // Entrada: Array = { "year": 2018, "month": 8, "day": 15 }
        // Salida: Fecha Y-m-d : string
        arrayAFecha(array){
            return array.year + "-" + array.month + "-" + array.day;
        }




        // Salida: Día actual en formato DD
        diaActual(){
            var h = this.fecha_actual.toISOString().substring(0,10);
            return h.substring(8, 10);
        }


        // Salida: Mes actual en formato MM
        mesActual(){
            var h = this.fecha_actual.toISOString().substring(0,10);
            return h.substring(5, 7);
        }

        // Salida: Año actual en formato YYYY
        anioActual(){
            var h = this.fecha_actual.toISOString().substring(0,10);
            return h.substring(0, 4);
        }     
        
        

        // Valida una fecha de nacimiento
        // Entrada: dd/mm/aaaa
        validarFechaNacimiento(fecha_nacimiento: string): boolean{
            if(fecha_nacimiento.length != 10) return false;

            return true;
        }



        mesNumeroAMesTexto(mes_numero: string){

          var respuesta = "";
          var mn = parseInt(mes_numero);

          if(mn == 1) respuesta = "Enero";
          if(mn == 2) respuesta = "Febrero";
          if(mn == 3) respuesta = "Marzo";
          if(mn == 4) respuesta = "Abril";
          if(mn == 5) respuesta = "Mayo";
          if(mn == 6) respuesta = "Junio";
          if(mn == 7) respuesta = "Julio";
          if(mn == 8) respuesta = "Agosto";
          if(mn == 9) respuesta = "Setiembre";
          if(mn == 10) respuesta = "Octubre";
          if(mn == 11) respuesta = "Noviembre";
          if(mn == 12) respuesta = "Diciembre";

          return respuesta;
        }




      randomString(length) {
          var result           = '';
          var characters       = 'AiCjkl0123456789';
          var charactersLength = characters.length;
          for ( var i = 0; i < length; i++ ) {
             result += characters.charAt(Math.floor(Math.random() * charactersLength));
          }
          return result;
       }













       validarPassword(password1: string, password2: string) {

        if (password1 != password2) { return false; }
        if (password1.length < 8) { return false; }
        if (/.*[a-z].*/.test(password1) == false) { return false; } // Lowercase letters
        if (/.*[A-Z].*/.test(password1) == false) { return false; } // Uppercase letters
        if (/.*[0-9].*/.test(password1) == false) { return false; } // Numbers
        if (/.*[^a-zA-Z0-9].*/.test(password1) == false) { return false; } // Special characters (inc. space)
        return true;
      }




      cargarScripts(){
        $('.navbar-toggler').off('click');
        $('.navbar-toggler').on("click", function() {
          $('.sidebar-offcanvas').toggleClass('active');
        });
        if ($('.sidebar-offcanvas').hasClass('active')) $('.sidebar-offcanvas').removeClass('active');
       
      }







      deNumeroALetra(numero: number){

        var respuesta = "-";
    
        if(numero == 1) respuesta = "A";
        if(numero == 2) respuesta = "B";
        if(numero == 3) respuesta = "C";
        if(numero == 4) respuesta = "D";
        if(numero == 5) respuesta = "E";
        if(numero == 6) respuesta = "F";
        if(numero == 7) respuesta = "G";
        if(numero == 8) respuesta = "H";
        if(numero == 9) respuesta = "I";
        if(numero == 10) respuesta = "J";
        if(numero == 11) respuesta = "K";
        if(numero == 12) respuesta = "L";
        if(numero == 13) respuesta = "M";
        if(numero == 14) respuesta = "N";
        if(numero == 15) respuesta = "O";
        if(numero == 16) respuesta = "P";
        if(numero == 17) respuesta = "Q";
        if(numero == 18) respuesta = "R";
        if(numero == 19) respuesta = "S";
        if(numero == 20) respuesta = "T";
        if(numero == 21) respuesta = "U";
        if(numero == 22) respuesta = "V";
        if(numero == 23) respuesta = "W";
        if(numero == 24) respuesta = "X";
        if(numero == 25) respuesta = "Y";
        if(numero == 26) respuesta = "Z";
    
        return respuesta;
    
      }




      darHorariosDisponibles(){
        var horarios_disponibles = [];
        horarios_disponibles.push("");
        horarios_disponibles.push("00:00");
        horarios_disponibles.push("00:30");
        horarios_disponibles.push("01:00");
        horarios_disponibles.push("01:30");
        horarios_disponibles.push("02:00");
        horarios_disponibles.push("02:30");
        horarios_disponibles.push("03:00");
        horarios_disponibles.push("03:30");
        horarios_disponibles.push("04:00");
        horarios_disponibles.push("04:30");
        horarios_disponibles.push("05:00");
        horarios_disponibles.push("05:30");
        horarios_disponibles.push("06:00");
        horarios_disponibles.push("06:30");
        horarios_disponibles.push("07:00");
        horarios_disponibles.push("07:30");
        horarios_disponibles.push("08:00");
        horarios_disponibles.push("08:30");
        horarios_disponibles.push("09:00");
        horarios_disponibles.push("09:30");
        horarios_disponibles.push("10:00");
        horarios_disponibles.push("10:30");
        horarios_disponibles.push("11:00");
        horarios_disponibles.push("11:30");
        horarios_disponibles.push("12:00");
        horarios_disponibles.push("12:30");
        horarios_disponibles.push("13:00");
        horarios_disponibles.push("13:30");
        horarios_disponibles.push("14:00");
        horarios_disponibles.push("14:30");
        horarios_disponibles.push("15:00");
        horarios_disponibles.push("15:30");
        horarios_disponibles.push("16:00");
        horarios_disponibles.push("16:30");
        horarios_disponibles.push("17:00");
        horarios_disponibles.push("17:30");
        horarios_disponibles.push("18:00");
        horarios_disponibles.push("18:30");
        horarios_disponibles.push("19:00");
        horarios_disponibles.push("19:30");
        horarios_disponibles.push("20:00");
        horarios_disponibles.push("20:30");
        horarios_disponibles.push("21:00");
        horarios_disponibles.push("21:30");
        horarios_disponibles.push("22:00");
        horarios_disponibles.push("22:30");
        horarios_disponibles.push("23:00");
            horarios_disponibles.push("23:30");
            
            return horarios_disponibles;
        }


     
    






      






}