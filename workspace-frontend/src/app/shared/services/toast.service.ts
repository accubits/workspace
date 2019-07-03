import { Injectable } from '@angular/core';
declare var toastr:any
@Injectable()
export class ToastService {

  constructor() {
    this.setting();
   }
   Success(title:string,message?:string){
    toastr.clear();
    toastr.success(title,message);
  }
  Warning(title:string,message?:string){
    toastr.clear();
    toastr.warning(title,message);
  }
  Error(title:string,message?:string){
    toastr.clear();
    toastr.error(title,message);
  }

  Info(title:string,message?:string){
    toastr.clear();
    toastr.info(title,message);
  }
  setting(){
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-bottom-right",
      "preventDuplicates": true,
      "onclick": null,
      "showDuration": "5000",
      "hideDuration": "5000",
      "timeOut": "5000",
      "extendedTimeOut": "5000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
  }
  }
}
